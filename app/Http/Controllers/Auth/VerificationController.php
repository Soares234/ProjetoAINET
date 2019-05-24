<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;
use App\User;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */
    public function verify($id){

            $user=User::findOrFail($id);
            if (!$user->ativo){
                $userModel['ativo']=1;
                $userModel['email_verified_at']=date('Y-m-d H:i:s');
                $user->fill($userModel);
                $user->save();
                $message="O seu email foi confirmado,já pode dar login!";
            }else{
                $message="O seu email aparenta já ter sido confirmado!";
            }
            return view('auth.email.verified');


    }
    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }
}
