<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use app\User;

class SocioController extends Controller
{
    /**
     * @param $date  String de entrada, por questão de simplicidade o proximo param foi adicionado para gerir melhor o comportamento da função
     * @param $modo  Int decide como estamos a tranformar a data, 0 faz a transformação data xx/xx/xxxx para xx-xx-xxxx
     *
     *
     *@return string correta
     */
public function parseData($date, $modo){
    if ($modo){
        //Existem funções para isto mas menos pesado computacionalmente assim
        $date[2] = '-';
        $date[5] = '-';
    }else{
        $date[2]= '/';
        $date[5]='/';
    }
    return $date;
}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = DB::table('users')->paginate(20);
        //paginar 20 sócios por página
        //laravel 4. pages 21 & 33
        $title = 'Lista de Socios';

        return view('socios.list-socios', compact('title', 'users'));
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

        $user = $request->validate(
            ['name'=>'required|regex:/^[\pL\s]+$/u',
             'nome_informal'=>'required|regex:/^[\pL\s]+$/u',
             'email'=>'required|email',
             'sexo'=>'required',
             'data_nascimento'=>'required',
             'nif'=>'required|numeric',
             'telefone'=>'required|numeric',
             'tipo_socio'=>'required',
             'quota'=>'required',
             'direcao'=>'required',
             'ativo'=>'required'
            ],[
                'name.regex'=>'O nome não deverá conter caracteres especias nem números',
                'nome_informal.regex'=>'O nome não deverá conter caracteres especias nem números',
                'nif.numeric'=>'O nif deverá ser apenas numérico',
                'telefone.numeric'=>'O numero de telefone deverá ser um número!'
            ]

        );

        $user['password'] = Hash::make((string)$request->input('data_nascimento'));
        echo  $this->parseData($request->input('data_nascimento'),0);

        dd($user);
        $user['num_socio']=(string)1;//+ User::orderBy('created_at','desc')->first()->value('num_socio');// latest da order by da tabela invertida, o ultimo valor passa a first e como tal o num socio mais alto esta no topo da tabela, ordena pelo criterio Created_AT;


        //dd($request);
        dump($user);
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

        return view('socios.add-socio',compact('title','user'));
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

        return view('socios.edit-socio',compact('title','user'));
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
