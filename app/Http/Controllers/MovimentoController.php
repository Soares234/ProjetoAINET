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
                'num_diario'=>'required|integer',
                'num_servico'=>'required|integer',
                'piloto_id'=>['required','integer',
                    function($attribute,$value,$fail){
                        $aux = DB::table('users')->where('id','=',$value)->get();
                        if($aux->count() == 0) {
                            $fail("Este Sócio não existe");
                        }
                        if($aux->first()->tipo_socio){
                            $fail("Este Sócio não é piloto");
                        }
                }],
                'natureza'=>'required',
                'aerodromo_partida'=>'required',
                'aerodromo_chegada'=>'required',
                'num_aterragens'=>'required|integer|min:1',
                'num_descolagens'=>'required|integer|min:1',
                'num_pessoas'=>'required|integer|min:1',
                'tempo_voo'=>'required|integer|min:1',
                'preco_voo'=>'required|numeric|min:1',
                'modo_pagamento'=>'required',
                'num_recibo'=>'required',
                'tipo_instrucao'=>'required',
                'instrutor_id',
                'num_licenca_instrutor',
                'validade_licenca_instrutor',
                'tipo_licenca_instrutor',
                'num_certificado_instrutor',
                'validade_certificado_instrutor',
                'classe_certificado_instrutor',
                'tipo_conflito',
                'justificacao_conflito'
                ]
        );
        //Movimento::create($movimento);
        //return redirect()->action('MovimentoController@index')->with('message','Movimento criado com sucesso');
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
