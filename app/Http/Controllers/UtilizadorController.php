<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UtilizadorController extends Controller
{
    public function index()
    {
        $users = User::All();
        $title = 'Lista de Utilizadores';

        return view('list-utilizadores', compact('title', 'utilizadores'));
    }
    public function create()
    {
        $title = 'Adicionar novo Utilizador';
        $user= new User();

        return view('add-utilizador',compact('title','utilizador'));
    }
}
