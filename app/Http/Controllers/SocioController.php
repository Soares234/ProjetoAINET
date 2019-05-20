<?php

namespace App\Http\Controllers;

use App\Aeronave;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Movimento;
use Illuminate\Validation\Rule;

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
        $date[4] = '-';
        $date[7] = '-';
    }else{
        $date[4]= '/';
        $date[7]='/';
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
             'email'=>'required|email|unique:users',
             'sexo'=>'required',
             'data_nascimento'=>'required',
             'nif'=>'required|numeric|unique:users',
             'telefone'=>'required|numeric|unique:users',
             'tipo_socio'=>'required',
                'quota_paga'=>'min:0|max:1|between:0,1|integer',
                'direcao'=>'min:0|max:1|between:0,1|integer',
                'ativo'=>'min:0|max:1|between:0,1|integer',
                'endereco'=>'required'],
            ['name.regex'=>'O nome não deverá conter caracteres especias nem números',
             'nome_informal.regex'=>'O nome não deverá conter caracteres especias nem números',
             'nif.numeric'=>'O nif deverá ser apenas numérico',
             'telefone.numeric'=>'O numero de telefone deverá ser um número!',

            ]
        );

        $user['password'] = Hash::make((string)$request->input('data_nascimento'));
        //echo  $this->parseData($request->input('data_nascimento'),0);

        $last_user_numb = User::orderBy('num_socio','desc')->take(1)->value('num_socio');
        // latest da order by da tabela invertida, o ultimo valor passa a first e
        // como tal o num socio mais alto esta no topo da tabela, ordena pelo criterio num socio;
        //echo "<br> $last_user_numb";

        $user['num_socio']=$last_user_numb + 1;

        if(!array_key_exists ( "quota_paga", $user )){
            $user['quota_paga']=0;
        }
        if(!array_key_exists('direcao',$user)){
            $user['direcao']=0;
        }
        if(!array_key_exists('ativo',$user)){
            $user['ativo']=0;
        }
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
        $user = User::findOrFail($id);

        $title = $user->name;

        return view('socios.show-socio', compact( 'title', 'user'));
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
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $matricula
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //dd($request);

        $user= $request->validate(
            [
                'name'=>'required|regex:/^[\pL\s]+$/u',
                'nome_informal'=>'required|regex:/^[\pL\s]+$/u',
                'num_socio'=>['required','integer',Rule::unique('users')->ignore($id)],
                'email'=>['required','email',Rule::unique('users')->ignore($id)],
                'sexo'=>'required',
                'data_nascimento'=>'required',
                'nif'=>['required','numeric',Rule::unique('users')->ignore($id)],
                'telefone'=>['required',Rule::unique('users')->ignore($id)],
                'tipo_socio'=>'required',
                'quota_paga'=>'min:0|max:1|between:0,1',
                'direcao'=>'min:0|max:1|between:0,1',
                'ativo'=>'min:0|max:1|between:0,1'],
                ['name.regex'=>'O nome não deverá conter caracteres especias nem números',
                    'nome_informal.regex'=>'O nome não deverá conter caracteres especias nem números',
                    'nif.numeric'=>'O nif deverá ser apenas numérico',
                    'telefone.numeric'=>'O numero de telefone deverá ser um número!'
                ]
        );

        if(!array_key_exists ( "quota_paga", $user )){
            $user['quota_paga']=0;
        }
        if(!array_key_exists('direcao',$user)){
            $user['direcao']=0;
        }
        if(!array_key_exists('ativo',$user)){
            $user['ativo']=0;
        }

        //dd($user,$request);
        $userModel = User::findOrFail($id);

        $userModel->fill($user);
        $userModel->save();

        return redirect()->action('SocioController@index');
    }
    //ELES LEVAM SOFT DELETE SE ESTIVEREM ASSOCIADOS A MOVIMENTOS
    public function destroy($id)
    {
        $userModel = User::findOrFail($id);

        $numero_de_socios = Movimento::where('piloto_id', '=', $userModel->id)->count();


        if($numero_de_socios == 0){
            $userModel->forceDelete();
        }else{
            $userModel->delete();
        }

        return redirect()->action('SocioController@index');



    }

}
