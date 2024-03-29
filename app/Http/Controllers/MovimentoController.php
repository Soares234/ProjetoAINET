<?php
namespace App\Http\Controllers;

use App\Licenca;
use function foo\func;
use Illuminate\Http\Request;use \Datetime;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use App\Movimento;
use App\Aeronave;
use App\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class MovimentoController extends Controller {
    public function parseData($date){

        $aux =explode ( "/" , $date );
        $aux=array_reverse ( $aux );
        return implode ("-" ,$aux);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        //id, aeronave, data_inf, data_sup, natureza, confirmado,
        //piloto, instrutor

        $this->authorize('isAtivo',Auth::user());
        $title = 'Lista de Movimentos';
        $filters = DB::table('movimentos as mov')
            ->leftJoin('users as t1','mov.piloto_id','=','t1.id')
            ->leftJoin('users as t2','mov.instrutor_id','=','t2.id')
            ->select('mov.*','t1.name as piloto_nome','t1.nome_informal as piloto_nome_informal',
                't2.name as instrutor_nome','t2.nome_informal as instrutor_nome_informal');

        if($request->input('id')!=null){
            $filters=$filters->where('mov.id','=',$request->input('id'));
        }
        if($request->input('aeronave')!=null){
            $filters=$filters->where('aeronave','=',$request->input('aeronave'));
        }
        if($request->input('natureza')!=null){
            $filters=$filters->where('natureza','=',$request->input('natureza'));
        }
        if($request->input('confirmado')!=null){
            $filters=$filters->where('confirmado','=',$request->input('confirmado'));
        }
        if($request->input('piloto')!=null){
            $filters=$filters->where('piloto_id','like',"%".$request->input('piloto')."%");
        }
        if ($request->input('instrutor')){
            $filters=$filters->where('instrutor_id','like',"%".$request->input('instrutor')."%");
        }
        if ($request->input('data_inf')!=null){
            $ymd = DateTime::createFromFormat('d/m/Y', $request->input('data_inf'))->format('Y-m-d');
            $filters=$filters->whereDate('data','>=',$ymd);
        }
        if($request->input('data_sup')!=null){
            //datas abaixo desta, se já tiver filtrado o "acima de" vai produzir datas entre ambas
            $ymd = DateTime::createFromFormat('d/m/Y', $request->input('data_sup'))->format('Y-m-d');

            $filters=$filters->where('data','<=',$ymd);
        }
        if ($request->input('meus_movimentos')){
            $filters=$filters->where('piloto_id','=',Auth::user()->id)->orWhere('instrutor_id','=',Auth::user()->id);
        }




        $movimentos=$filters->paginate(20);
        return view('movimentos.list-movimentos', compact('title', 'movimentos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $this->authorize('administrate',Auth::user());
        $this->authorize('isAtivo',Auth::user());
        $title = 'Adicionar Novo Movimento';
        $movimento= new Movimento();

        $aeronaves = DB::table('aeronaves')->where('deleted_at','=',NULL)->get();
        $aerodromos = DB::table('aerodromos')->where('deleted_at','=',NULL)->get();

        return view('movimentos.add-edit-movimento',compact('title','movimento','aeronaves','aerodromos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //$this->authorize('administrate',Auth::user());

        //dd($request->data,$request->hora_descolagem,$request);
        $contaHorasAUX = $request->input('aeronave');
        $movimento = $request->validate([
                'data'=>'required|date_format:d/m/Y',
                'hora_descolagem'=>'required|date_format:H:i:s',
                'hora_aterragem'=>'required|date_format:H:i:s',
                'aeronave'=>['required',
                    function($atribute,$value,$fail){
                        $aux = DB::table('aeronaves')->where('matricula','=',$value)->get();
                        if($aux->count()==0){
                            $fail("Esta Aeronave não existe");
                        }
                    }],
                'conta_horas_inicio'=>'required|integer|min:0',
                'conta_horas_fim'=>'required|integer|min:0|gt:conta_horas_inicio',
                'num_diario'=>'integer|min:1',
                'num_servico'=>'required|integer',
                'piloto_id'=>['required','integer','exists:aeronaves_pilotos,piloto_id'],
                'natureza'=>['required',Rule::in('T','E','I')],
                'aerodromo_partida'=>'required|exists:aerodromos,code',
                'aerodromo_chegada'=>'required|exists:aerodromos,code',
                'num_aterragens'=>'required|integer|min:1',
                'num_descolagens'=>'required|integer|min:1',
                'num_pessoas'=>'required|integer|min:1',
                'tempo_voo'=>'required|integer|min:0',
                'observacoes',
                'preco_voo'=>'required|numeric|min:0',
                'modo_pagamento'=>['required',Rule::in('N','M','T','P')],
                'num_recibo'=>'required|max:20',
                'tipo_instrucao'=>['required_if:natureza,==,I',Rule::in('D','S',null)],
                'instrutor_id'=>["required_if:natureza,==,I",'nullable','exists:users,id,tipo_socio,P,instrutor,1'],
                'tipo_conflito',
                'justificacao_conflito'
            ]
        );
        $pilotoCollection = DB::table('users')->where('id','=',$movimento['piloto_id'])->get();
        $piloto = $pilotoCollection->first();

        $movimento['num_licenca_piloto'] = $piloto->num_licenca;
        $movimento['validade_licenca_piloto'] = $piloto->validade_licenca;
        $movimento['tipo_licenca_piloto'] = $piloto->tipo_licenca;
        $movimento['num_certificado_piloto'] = $piloto->num_certificado;
        $movimento['validade_certificado_piloto'] = $piloto->validade_certificado;
        $movimento['classe_certificado_piloto']= $piloto->classe_certificado;

        $aeronaveCollection = DB::table('aeronaves')->where('matricula','=',$movimento['aeronave'])->get();
        $aeronave = $aeronaveCollection->first();

        if($movimento['conta_horas_inicio']<$aeronave->conta_horas && !$request->exists('confirmado')){
            $errors['conta_horas_inicio'] = 'conta horas inicial deveria ter um valor igual a '.$aeronave->conta_horas;
            $errors['S'] = 1;
            return redirect()->action('MovimentoController@create')->withErrors($errors)->withInput($movimento);
        }
        if($movimento['conta_horas_inicio']>$aeronave->conta_horas && !$request->exists('confirmado')){
            $errors['conta_horas_inicio'] = 'conta horas inicial deveria ter um valor igual a '.$aeronave->conta_horas;
            $errors['B'] = 1;
            return redirect()->action('MovimentoController@create')->withErrors($errors)->withInput($movimento);
        }

        if($request->exists('confirmado')){
            if($request->input('confirmado')=='S'){
                $movimento['tipo_conflito']=$request->input('confirmado');
            }else{
                $movimento['tipo_conflito']=$request->input('confirmado');
            }

            $movimento['justificacao_conflito'] = $request->input('justificacao_conflito');
        }


        if($movimento['conta_horas_fim']<=$movimento['conta_horas_inicio']){
            $errors['conta_horas_fim'] = 'conta horas final tem que ter um valor superior a '.$movimento['conta_horas_inicio'];
            return redirect()->action('MovimentoController@create')->withErrors($errors)->withInput($movimento);
        }
        $movimento['tempo_voo'] =( $movimento['conta_horas_fim'] - $movimento['conta_horas_inicio'] )* 6;
        $movimento['preco_voo'] = $movimento['tempo_voo'] * $aeronave->preco_hora /60;

        $movimento['hora_descolagem'] = $movimento['data']. ' ' .$movimento['hora_descolagem'];
        $movimento['hora_aterragem'] = $movimento['data']. ' ' .$movimento['hora_aterragem'];

      /*  $movimentosCollection = DB::table('movimentos')->where('aeronave','=',$movimento['aeronave'])->orderByDesc('num_servico')->get();
        if($movimentosCollection->count()==0){
            $movimento['num_servico'] = 1;
        }else{
            $movimento['num_servico'] = $movimentosCollection->first()->num_servico +1;
        }*/

        $movimento['confirmado'] = 0;

        //dd($movimento);
        $movimento['data']=$this->parseData($movimento['data']);
        $movimento['hora_descolagem']=Date('Y-m-d H:i:s',strtotime($movimento['hora_descolagem']));
        $movimento['hora_aterragem']=Date('Y-m-d H:i:s',strtotime($movimento['hora_aterragem']));

        $movimento=Movimento::create($movimento);
        return redirect()->action('MovimentoController@index')->with('message','Movimento criado com sucesso');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('view',Auth::user());
        $title = 'Editar Movimento';
        $movimento= Movimento::findOrFail($id);
        $aeronaves = DB::table('aeronaves')->where('deleted_at','=',NULL)->get();
        $aerodromos = DB::table('aerodromos')->where('deleted_at','=',NULL)->get();
        return view('movimentos.edit-movimento',compact('title','movimento','aeronaves','aerodromos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $movimento = Movimento::findOrFail($id);

        $movimento->delete();


        return redirect()->action('MovimentoController@index');
    }
}
