<?php
namespace App\Http\Controllers;

use App\Licenca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Movimento;
use App\Aeronave;
use App\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class MovimentoController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $title = 'Lista de Movimentos';
        $movimentos = DB::table('movimentos as mov')
            ->leftJoin('users as t1','mov.piloto_id','=','t1.id')
            ->leftJoin('users as t2','mov.instrutor_id','=','t2.id')
            ->select('mov.*','t1.name as piloto_nome','t1.nome_informal as piloto_nome_informal',
                't2.name as instrutor_nome','t2.nome_informal as instrutor_nome_informal')
            ->paginate(14);
        //dd($movimentos);
        return view('movimentos.list-movimentos', compact('title', 'movimentos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //$this->authorize('administrate',Auth::user());

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
            'data'=>'required',
                'hora_descolagem'=>'required',
                'hora_aterragem'=>'required',
                'aeronave'=>['required',
                    function($atribute,$value,$fail){
                        $aux = DB::table('aeronaves')->where('matricula','=',$value)->get();
                        if($aux->count()==0){
                            $fail("Esta Aeronave não existe");
                        }
                }],
                'conta_horas_inicio'=>'required',
                'conta_horas_fim'=>'required',
                'num_diario'=>'required|integer|min:1',
                'piloto_id'=>['required','integer',
                    function($attribute,$value,$fail){
                        $aux = DB::table('users')->where('id','=',$value)->get();
                        if($aux->count() == 0) {
                            $fail("Este Sócio não existe");
                        }elseif ($aux->first()->tipo_socio != 'P'){
                            $fail("Este Sócio não é piloto");
                        }
                }],
                'natureza'=>'required',
                'aerodromo_partida'=>'required',
                'aerodromo_chegada'=>'required',
                'num_aterragens'=>'required|integer|min:1',
                'num_descolagens'=>'required|integer|min:1',
                'num_pessoas'=>'required|integer|min:1',
                'tempo_voo',
                'preco_voo',
                'modo_pagamento'=>'required',
                'num_recibo'=>'required',
                'tipo_instrucao',
                'instrutor_id',
                'tipo_conflito',
                'justificacao_conflito'
                ]
        );
        //dd($movimento);

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
        //dd($movimento);

        if($movimento['conta_horas_fim']<=$movimento['conta_horas_inicio']){
            $errors['conta_horas_fim'] = 'conta horas final tem que ter um valor superior a '.$movimento['conta_horas_inicio'];
            return redirect()->action('MovimentoController@create')->withErrors($errors)->withInput($movimento);
        }
        $movimento['tempo_voo'] = $movimento['conta_horas_fim'] - $movimento['conta_horas_inicio'] * 6;
        $movimento['preco_voo'] = $movimento['tempo_voo'] * $aeronave->preco_hora /60;

        $movimento['hora_descolagem'] = $movimento['data']. ' ' .$movimento['hora_descolagem'];
        $movimento['hora_aterragem'] = $movimento['data']. ' ' .$movimento['hora_aterragem'];

        $movimentosCollection = DB::table('movimentos')->where('aeronave','=',$movimento['aeronave'])->orderByDesc('num_servico')->get();
        if($movimentosCollection->count()==0){
            $movimento['num_servico'] = 1;
        }else{
            $movimento['num_servico'] = $movimentosCollection->first()->num_servico +1;
        }

        $movimento['confirmado'] = 0;

        //dd($movimento);

        Movimento::create($movimento);
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
        $title = 'Editar Movimento';
        $movimento= Movimento::findOrFail($id);

        $aeronaves = DB::table('aeronaves')->where('deleted_at','=',NULL)->get();
        $aerodromos = DB::table('aerodromos')->where('deleted_at','=',NULL)->get();

        return view('movimentos.add-edit-movimento',compact('title','movimento','aeronaves','aerodromos'));
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
        //
    }
}
