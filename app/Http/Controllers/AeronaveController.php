<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Aeronave;

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
            ['matricula' => 'required|regex:/^[A-Z]{1,2}-[A-Z]{3,4}$/u',
             'marca'=> 'required|max:40|regex:/^[\pL\s]+$/u',
             'modelo'=>'required|max:40|regex:/^[-\pL\s0-9]+$/u'],
            ['matricula.regex' => 'Matricula deverá ser do seguinte formato AA(A)-ZZZ(Z), 
            em que o valor entre parenteses pode ser opcional',
             'marca.regex'=>'Marca só poderá conter Letras e espaços',
             'modelo.regex'=>'Modelo poderá conter Letras, número e hífens (-)']);

        dd($request);

        /*
            num_lugares	int(11)
            preco_hora
        */

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
