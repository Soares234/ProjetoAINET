<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use app\User;

class SocioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::All();
        $title = 'Lista de Socios';

        return view('list-socios', compact('title', 'users'));
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

        $user = $request->validate(/**Sujeito a alterações, comentem se quiseres */

        );

        $user->password = $request->data_nascimento;
        $user->num_socio = ($request->latest()->first())+1; // latest da order by da tabela invertida, o ultimo valor passa a first e como tal o num socio mais alto esta no topo da tabela, ordena pelo criterio Created_AT;


        //dd($request);

        User::create($user);
        return redirect()->action('SocioController@index')->with('message','Sócio criado com sucesso');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Adicionar novo Sócio';
        $user= new User();

        return view('add-socio',compact('title','user'));
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
        $user = User::findOrFail($id);

        return view('edit-socio',compact('title','user'));
    }
    //ELES LEVAM SOFT DELETE SE ESTIVEREM ASSOCIADOS A MOVIMENTOS
    public function destroy($id)
    {



        $numero_de_socios = Movimento::where('user', '=', $id)->count();



        $userModel = User::findOrFail($id);

        if($numero_de_socios == 0){
            $userModel->forceDelete();
        }else{
            $userModel->delete();
        }

        return redirect()->action('SocioController@index');



    }
}
