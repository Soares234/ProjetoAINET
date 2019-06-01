<?php

namespace App\Http\Controllers;

use App\AeronavesPilotos;
use App\Movimento;
use Illuminate\Http\Request;
use App\Aeronave;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        return view('aeronaves.list-aeronaves', compact('title', 'aeronaves'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('administrate',Auth::user());
        $this->authorize('isAtivo',Auth::user());
        $title = 'Adicionar nova Aeronave';
        $aeronave= new Aeronave();

        return view('aeronaves.add-aeronave',compact('title','aeronave'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $matricula = $request->matricula;
        $this->authorize('administrate',Auth::user());
        $this->authorize('isAtivo',Auth::user());

        //dd($request);

        $aeronave = $request->validate(
            ['matricula' => 'required|unique:aeronaves|regex:/^[A-Z]{1,2}-[A-Z]{3,4}$/u',
             'marca'=> 'required|max:40',
             'modelo'=>'required|max:40|regex:/^[-\pL\s0-9]+$/u',
             'num_lugares'=>'required|integer|between:1,100',
             'conta_horas'=>'required|integer|min:0',
             'preco_hora'=>'required|numeric|min:0',                'precos.1' => 'required|numeric|min:1',
                'precos.2' => 'required|numeric|min:1',
                'precos.3' => 'required|numeric|min:1',
                'precos.4' => 'required|numeric|min:1',
                'precos.5' => 'required|numeric|min:1',
                'precos.6' => 'required|numeric|min:1',
                'precos.7' => 'required|numeric|min:1',
                'precos.8' => 'required|numeric|min:1',
                'precos.9' => 'required|numeric|min:1',
                'precos.10' => 'required|numeric|min:1',
                'tempos.1' => 'required|integer|min:1',
                'tempos.2' => 'required|integer|min:1',
                'tempos.3' => 'required|integer|min:1',
                'tempos.4' => 'required|integer|min:1',
                'tempos.5' => 'required|integer|min:1',
                'tempos.6' => 'required|integer|min:1',
                'tempos.7' => 'required|integer|min:1',
                'tempos.8' => 'required|integer|min:1',
                'tempos.9' => 'required|integer|min:1',
                'tempos.10' => 'required|integer|min:1'],
            ['matricula.regex' => 'Matricula deverá ser do seguinte formato AB(C)-WXY(Z), 
            em que o valor entre parenteses é opcional',
                'marca.regex' => 'Marca só poderá conter Letras e espaços',
                'modelo.regex' => 'Modelo poderá conter Letras, número e hífens (-)',
                'num_lugares.between' => 'Número de lugares deverá ser entre 1 e 100']
        );

        for ($i=1;$i<=10;$i++) {
            DB::table('aeronaves__valores')->insert([
                ['matricula' => $matricula],
                ['unidade_conta_horas' => $aeronave['conta_horas']],
                ['minutos'=>$aeronave['tempos.'.$i]],
                ['preco'=>$aeronave['precos.'.$i]]
            ]);
            $aux='tempos.'.$i;
            unset($aeronave,$aux);
            $aux='precos.'.$i;
        }
        //dd($request);

        Aeronave::create($aeronave);
        return redirect()->action('AeronaveController@index')->with('message','Aeronave criada com sucesso');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     *     * @param string $matricula
     *     * @return \Illuminate\Http\Response
     */
    public function edit($matricula)
    {
        $this->authorize('administrate',Auth::user());
        $this->authorize('isAtivo',Auth::user());
        $title = 'Editar Aeronave';
        $aeronave = Aeronave::findOrFail($matricula);

        return view('aeronaves.edit-aeronave', compact('title', 'aeronave'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $matricula
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $matricula)
    {
        $this->authorize('administrate', Auth::user());
        $this->authorize('isAtivo', Auth::user());
        $aeronave = $request->validate(

            ['marca' => ['required', 'max:40'],
                'matricula' => 'required|max:8',
                'modelo' => 'required|max:40|regex:/^[-\pL\s0-9]+$/u',
                'num_lugares' => 'required|integer|between:1,100',
                'conta_horas' => 'required|integer|min:0',
                'preco_hora' => 'required|numeric|min:0',
                'precos.1' => 'required|numeric|min:1',
                'precos.2' => 'required|numeric|min:1',
                'precos.3' => 'required|numeric|min:1',
                'precos.4' => 'required|numeric|min:1',
                'precos.5' => 'required|numeric|min:1',
                'precos.6' => 'required|numeric|min:1',
                'precos.7' => 'required|numeric|min:1',
                'precos.8' => 'required|numeric|min:1',
                'precos.9' => 'required|numeric|min:1',
                'precos.10' => 'required|numeric|min:1',
                'tempos.1' => 'required|integer|min:1',
                'tempos.2' => 'required|integer|min:1',
                'tempos.3' => 'required|integer|min:1',
                'tempos.4' => 'required|integer|min:1',
                'tempos.5' => 'required|integer|min:1',
                'tempos.6' => 'required|integer|min:1',
                'tempos.7' => 'required|integer|min:1',
                'tempos.8' => 'required|integer|min:1',
                'tempos.9' => 'required|integer|min:1',
                'tempos.10' => 'required|integer|min:1'],
            ['marca.regex' => 'Marca só poderá conter Letras e espaços',
                'modelo.regex' => 'Modelo poderá conter Letras, número e hífens (-)',
                'num_lugares.between' => 'Número de lugares deverá ser entre 1 e 100']
        );


        $listaValores = DB::table('aeronaves_valores')->where('matricula', '=', $matricula)->get();

        $i = 1;
        foreach ($listaValores as &$lista) {
            $lista->minutos = $aeronave['tempos.' . $i];
            $lista->preco = $aeronave['precos.' . $i];
            $tempos = 'tempos.' . $i;
            unset($aeronave, $tempos);
            $precos = 'precos' . $i;
            unset($aeronave, $precos);
            dd($lista);
            $lista->save();
            $i++;
        }


        $aeronaveModel = Aeronave::findOrFail($matricula);
        $aeronaveModel->fill($aeronave);
        $aeronaveModel->save();

        return redirect()->action('AeronaveController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $matricula
     * @return \Illuminate\Http\Response
     */
    public function destroy($matricula)
    {
        $this->authorize('administrate',Auth::user());
        $this->authorize('isAtivo',Auth::user());
        //$aeronave = Movimento::find($matricula)->movimentos;

        $numero_de_aeronaves = Movimento::where('aeronave', '=', $matricula)->count();

        //dd($aux_recebido);

        $aeronaveModel = Aeronave::findOrFail($matricula);
        /**Da complete  delete se a aeronave não tiver movimentos, else soft delete*/
        if ($numero_de_aeronaves == 0) {
            $aeronaveModel->forceDelete();
        } else {
            $aeronaveModel->delete();
        }

        return redirect()->action('AeronaveController@index');


        //User::destroy($id);
        //return redirect()->action('SocioController@index')->with('message','User deleted successfully');
    }

    public function indexPilotosAutorizados($id){

        $pilotos_aeronaves = DB::table('aeronaves_pilotos as ap')
            ->join('aeronaves as t1','ap.matricula','=','t1.matricula')
            ->join('users as t2','ap.piloto_id','=','t2.id')
            ->where('ap.matricula','=',$id)
            ->select('ap.*','t1.deleted_at as deleted_at','t2.nome_informal as nome_informal')
            ->get();

        $title = "Lista de Pilotos para a ".$id;
        return view('aeronaves.pilotos_aeronaves.list-pilotos',compact('title','pilotos_aeronaves'));
    }
    public function removePilotoFromAeronave(Request $request)
    {

        $this->authorize('administrate', Auth::user());

        $piloto_para_retirar = $request->validate([
                'id' => 'required|exists:aeronaves_pilotos,id',
                'matricula' => 'required|exists:aeronaves,matricula',
                'piloto_id' => 'required|exists:users,id|exists:aeronaves_pilotos,piloto_id',
            ]
        );

        $aeronavesPilotoModel = AeronavesPilotos::findOrFail($piloto_para_retirar['id']);

        $aeronavesPilotoModel->delete();

        return redirect()->action('AeronaveController@indexPilotosAutorizados', $piloto_para_retirar['matricula']);


    }

    public function addPilotoToAeronave(Request $request)
    {

        $this->authorize('administrate', Auth::user());

        $piloto_para_adicionar = $request->validate([
                'matricula' => 'required|exists:aeronaves,matricula',
                'piloto_id' => ['required', 'exists:users,id',
                    function ($atribute, $value, $fail) {
                        $user = User::findOrFail($value);

                        if ($user->tipo_socio != 'P') {
                            $fail("sócio não é piloto");
                        }

                    }
                ]
            ]
        );

        AeronavesPilotos::create($piloto_para_adicionar);

        return redirect()->action('AeronaveController@indexPilotosAutorizados', $piloto_para_adicionar['matricula']);
    }
}
