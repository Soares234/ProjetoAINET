<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Aeronave;
use App\Rules\Number_between;

class AeronaveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $aeronaves = Aeronave::All();
        $title = 'Lista de Aeronaves';

        return view('list-aeronaves', compact('title', 'aeronaves'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Adicionar nova Aeronave';
        $aeronave= new Aeronave();

        return view('add-aeronave',compact('title','aeronave'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $matricula = $request->matricula;


        //dd($request);

        $aeronave = $request->validate(
            ['matricula' => 'required|unique:aeronaves|regex:/^[A-Z]{1,2}-[A-Z]{3,4}$/u',
             'marca'=> 'required|max:40|regex:/^[\pL\s]+$/u',
             'modelo'=>'required|max:40|regex:/^[-\pL\s0-9]+$/u',
             'num_lugares'=>'required|integer|between:1,100',
             'conta_horas'=>'required|integer|min:0',
             'preco_hora'=>'required|numeric|min:0'],
            ['matricula.regex' => 'Matricula deverá ser do seguinte formato AB(C)-WXY(Z), 
            em que o valor entre parenteses é opcional',
             'marca.regex'=>'Marca só poderá conter Letras e espaços',
             'modelo.regex'=>'Modelo poderá conter Letras, número e hífens (-)',
             'num_lugares.regex'=>'Número de lugares deverá ser entre 1 e 100']);

        //dd($request);

        Aeronave::create($aeronave);
        return redirect()->action('AeronaveController@index')->with('message','Aeronave criada com sucesso');
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
