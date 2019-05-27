<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function isAtivo(User $user) {
        return $user->ativo ==1 && $user->hasVerifiedEmail();
    }
    public function view (User $loggedUser, User $user){
        return ($loggedUser->id == $user->id) || ($loggedUser->direcao == 1);
    }

    public function edit (User $loggedUser, User $user){
        return ($loggedUser->id == $user->id) || ($loggedUser->direcao == 1);
    }

    public function administrate(User $loggedUser){
        return $loggedUser->direcao == 1;
    }

    public function isPiloto(User $user){
        return $user->piloto == 1;
    }

}
