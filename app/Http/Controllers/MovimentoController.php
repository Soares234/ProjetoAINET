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
        $movimentos = DB::table('movimentos')->paginate(14);
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
        $movimento = $request->validate([
            'data'=>'required',
                'hora_descolagem'=>'required',
                'hora_aterragem'=>'required',
                'conta_horas_inicio'=>'required',
                'conta_horas_fim'=>'required',
                'aeronave'=>'required',
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

        $movimento['tempo_voo'] = $movimento['conta_horas_fim'] - $movimento['conta_horas_inicio'];

        $aeronaveCollection = DB::table('aeronaves')->where('matricula','=',$movimento['aeronave'])->get();
        $aeronave = $aeronaveCollection->first();
        $movimento['preco_voo'] = $movimento['tempo_voo'] * $aeronave->preco_hora /6;

        $movimento['hora_descolagem'] = $movimento['data']. ' ' .$movimento['hora_descolagem'];
        $movimento['hora_aterragem'] = $movimento['data']. ' ' .$movimento['hora_aterragem'];

        $movimentosCollection = DB::table('movimentos')->where('aeronave','=',$movimento['aeronave'])->orderByDesc('num_servico')->get();
        if($movimentosCollection->count()==0){
            $movimento['num_servico'] = 1;
        }else{
            $movimento['num_servico'] = $movimentosCollection->first()->num_servico +1;
        }

        $movimento['confirmado'] = 0;

        //dd($piloto,$movimento,$movimentosCollection);

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
        //
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
