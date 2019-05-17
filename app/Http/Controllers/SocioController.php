<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use app\Socio;

class SocioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $socios = Socio::All();
        $title = 'Lista de Socios';

        return view('list-socios', compact('title', 'socios'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id = $request->id;


        //dd($request);

        $socio = $request->validate(/**Sujeito a alterações, comentem se quiseres */

        );

        $socio->password = $request->data_nascimento;
        $socio->num_socio = ($request->latest()->first())+1; // latest da order by da tabela invertida, o ultimo valor passa a first e como tal o num socio mais alto esta no topo da tabela, ordena pelo criterio Created_AT;


        //dd($request);

        Socio::create($socio);
        return redirect()->action('SocioController@index')->with('message','Socio criado com sucesso');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Adicionar novo Sócio';
        $socio= new Socio();

        return view('add-socio',compact('title','socio'));
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
     *     * @param  string  $matricula
     *     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $title = 'Editar Sócio';
        $socio = Socio::findOrFail($id);

        return view('edit-socio',compact('title','socio'));
    }
    //ELES LEVAM SOFT DELETE SE ESTIVEREM ASSOCIADOS A MOVIMENTOS
    public function destroy($id)
    {



        $numero_de_socios = Movimento::where('socio', '=', $id)->count();



        $socioModel = User::findOrFail($id);

        if($numero_de_socios == 0){
            $socioModel->forceDelete();
        }else{
            $socioModel->delete();
        }

        return redirect()->action('SocioController@index');



    }
}
