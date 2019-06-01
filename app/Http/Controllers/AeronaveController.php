<?php

namespace App\Http\Controllers;

use App\AeronavesPilotos;
use App\Movimento;
use Illuminate\Http\Request;
use App\Aeronave;
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
        $title = 'Adicionar nova Aeronave';
        $aeronave= new Aeronave();

        return view('aeronaves.add-aeronave',compact('title','aeronave'));
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
             'num_lugares.between'=>'Número de lugares deverá ser entre 1 e 100']
            );

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
     *     * @param  string  $matricula
     *     * @return \Illuminate\Http\Response
     */
    public function edit($matricula)
    {
        $title = 'Editar Aeronave';
        $aeronave = Aeronave::findOrFail($matricula);

        return view('aeronaves.edit-aeronave',compact('title','aeronave'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $matricula
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $matricula)
    {
        $aeronave = $request->validate(
            ['marca'=> 'required|max:40|regex:/^[\pL\s]+$/u',
                'modelo'=>'required|max:40|regex:/^[-\pL\s0-9]+$/u',
                'num_lugares'=>'required|integer|between:1,100',
                'conta_horas'=>'required|integer|min:0',
                'preco_hora'=>'required|numeric|min:0'],
            ['marca.regex'=>'Marca só poderá conter Letras e espaços',
                'modelo.regex'=>'Modelo poderá conter Letras, número e hífens (-)',
                'num_lugares.between'=>'Número de lugares deverá ser entre 1 e 100']
        );

        $aeronaveModel = Aeronave::findOrFail($matricula);
        $aeronaveModel->fill($aeronave);
        $aeronaveModel->save();

        return redirect()->action('AeronaveController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $matricula
     * @return \Illuminate\Http\Response
     */
    public function destroy($matricula)
    {

        //$aeronave = Movimento::find($matricula)->movimentos;

        $numero_de_aeronaves = Movimento::where('aeronave', '=', $matricula)->count();

        //dd($aux_recebido);

        $aeronaveModel = Aeronave::findOrFail($matricula);
        /**Da complete  delete se a aeronave não tiver movimentos, else soft delete*/
        if($numero_de_aeronaves == 0){
            $aeronaveModel->forceDelete();
        }else{
            $aeronaveModel->delete();
        }

        return redirect()->action('AeronaveController@index');


        //User::destroy($id);
        //return redirect()->action('SocioController@index')->with('message','User deleted successfully');
    }

    public function indexPilotosAutorizados($id){

        $pilotos_autorizados = DB::table('aeronaves_pilotos as ap')
            ->join('aeronaves as t1','ap.matricula','=','t1.matricula')
            ->join('users as t2','ap.piloto_id','=','t2.id')
            ->where('ap.matricula','=',$id)
            ->select('ap.*','t1.deleted_at as deleted_at','t2.nome_informal as nome_informal')
            ->get();

        $title = "Lista de Pilotos para a ".$id;
        return view('aeronaves.pilotos_aeronaves.list-pilotos',compact('title','pilotos_autorizados'));
    }
}
