<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class PilotoController extends Controller
{
    public function getLicenca($id){


        $user = User::findOrFail($id);
        $this->authorize('view',$user);

        if (file_exists(storage_path('app/docs_piloto/' . 'licenca_'.$user->id.'.pdf'))){

            return response()->download(storage_path('app/docs_piloto/' . 'licenca_'.$user->id.'.pdf'));
        }

        $errors['licenca_pdf'] = 'não existe a sua licenca';
        return view('socios.show-socio',compact('user','errors'));

    }

    public function getCertificado($id){


        $user = User::findOrFail($id);
        $this->authorize('view',$user);


        if (file_exists(storage_path('app/docs_piloto/' . 'certificado_'.$user->id.'.pdf'))){

            return response()->download(storage_path('app/docs_piloto/' . 'certificado_'.$user->id.'.pdf'));
        }

        $errors['certificado_pdf'] = 'não existe a sua licenca';
        return view('socios.show-socio',compact('user','errors'));

    }
}
