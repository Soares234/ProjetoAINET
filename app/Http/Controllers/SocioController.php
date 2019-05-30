<?php

namespace App\Http\Controllers;

use App\Aeronave;
use App\Filtros\Filterable;
use App\Filtros\FiltrosUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use Mail;
use App\Movimento;
use App\Mail\VerifyMail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class SocioController extends Controller
{

    /**
     * @param Request $request
     */
    public function passwordUpdate(Request $request)
    {
        $user = Auth::user();
        $title = 'Mudar a password';
        //if (Hash::check($request->input('old_password'), $user->password)) {//Verifica se e igual ao erro da bd


        // $request['old_password']=Hash::make($request['old_password']);

        $value=Auth::user()->password;
            $user = $request->validate(
                [
                    'old_password' =>[ 'required',
                        function($attribute,$value,$fail){
                            if(!Hash::check( $value,Auth::user()->password)) {

                                $fail('Password não é igual há da base de dados!');
                            }
                            }],  //Tem de ser igual a has na DB
                    'password' => ['required','min:8','same:password_confirmation'],
                    'password_confirmation' => ['same:password','min:8'],
                ], [
                    'old_password.required' => 'Campo Obrigatório',
                    'password.required' => 'Campo obrigatório',
                    'password_confirmation.same' => 'As password não são iguais!'
                ]
            );

            $user['password']=Hash::make($request->input('password'));
            $user['password_inicial']=0;
            $user['remember_token']=str_random(40);
            $userModel=Auth::user();
        $userModel->fill($user);
        $userModel->save();
            return redirect()->action('SocioController@index')->with('message','Password alterada com sucesso');

    }

public function password(){
    $title='Mudar a password';
    $user=Auth::user();
    $errorDiferenca=0;
    return view('auth.passwords.change',compact('title','errorDiferenca'));
}
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
     * @param Request $request YOLO
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /*Os parâmetros possíveis são: num_socio,
nome_informal, email, tipo, direcao, quotas_pagas, ativo.*/
        $this->authorize('isAtivo',Auth::user());
        $users=User::where('id','>','0');
        if($request->input('num_socio')){ //num socio so devolve um, evitam se algum delay assim
            $users=$users->where('num_socio',$request->input('num_socio'));
        }
        else {
            if ($request->input('nome_informal') != null) {
                $users = $users->where('nome_informal', $request->input('name'));
            }
            if ($request->input('email')!=null){
                $users = $users->where('email',$request->input('email'));
            }
            if ($request->input('direcao')!=null){
                $users=$users->where('',$request->input('email'));
            }
            if ($request->input('tipo_socio')!=null){
                $users=$users->where('tipo_socio',$request->input('tipo_socio'));
            }
            if($request->input('quotas_pagas')!=null ){

            }
        }

        $title = 'Lista de Socios';
    $users=$users->get();
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
        $this->authorize('administrate',Auth::user());

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

        $user=User::create($user);
        Mail::to($user->email)->send(new VerifyMail($user));
        return redirect()->action('SocioController@index')->with('message','Sócio criado com sucesso');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('administrate',Auth::user());

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
        $this->authorize('view',$user);
        $this->authorize('isAtivo',$user);

        $title = $user->name;

        return view('socios.show-socio', compact('title', 'user'));


    }
    /**
     * Show the form for editing the specified resource.
     *
     *     * @param  string  $matricula
     *     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('edit',$user);
        $this->authorize('isAtivo',$user);


        $title = 'Editar Sócio';

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

        $this->authorize('edit',Auth::user());

        $user= $request->validate(
            [
                'name'=>'required|regex:/^[\pL\s]+$/u',
                'nome_informal'=>'required|max:40',//|regex:/^[\pL\s]+$/u',
                'num_socio'=>['required','integer',Rule::unique('users')->ignore($id)],
                'email'=>['required','email',Rule::unique('users')->ignore($id)],
                'sexo'=>'required',
                'data_nascimento'=>'required',
                'nif'=>['numeric','digits:9',Rule::unique('users')->ignore($id)],
                'telefone'=>['digits:20',Rule::unique('users')->ignore($id)],
                'tipo_socio'=>'required',
                'quota_paga'=>'min:0|max:1|between:0,1',
                'direcao'=>'min:0|max:1|between:0,1',
                'ativo'=>'min:0|max:1|between:0,1'],
                ['name.regex'=>'O nome não deverá conter caracteres especias nem números',
                    //'nome_informal.regex'=>'O nome não deverá conter caracteres especias nem números',
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

        $ficheiro = $request->file('image');
        if ($ficheiro!=null) {
            Storage::disk('public')->put('fotos/' . $userModel->foto_url , File::get($ficheiro));
        }
        $userModel->fill($user);
        $userModel->save();

        return redirect()->action('SocioController@index');
    }
    //ELES LEVAM SOFT DELETE SE ESTIVEREM ASSOCIADOS A MOVIMENTOS
    public function destroy($id)
    {
        $userModel = User::findOrFail($id);
        $this->authorize('administrate',Auth::user());

        $numero_de_socios = Movimento::where('piloto_id', '=', $userModel->id)->count();


        if($numero_de_socios == 0){
            $userModel->forceDelete();
        }else{
            $userModel->delete();
        }

        return redirect()->action('SocioController@index');



    }

}
