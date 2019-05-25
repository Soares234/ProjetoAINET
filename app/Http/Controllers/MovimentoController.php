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

        $licencas = DB::table('tipos_licencas')->get();

        return view('movimentos.add-edit-movimento',compact('title','movimento','licencas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->authorize('administrate',Auth::user());

        $id = $request->id;
        //dd($request);

        $movimento = $request->validate(
            ['data'=>'required',
             'horaDescolagem'=>'required',
             'horaAterragem'=>'required']
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
