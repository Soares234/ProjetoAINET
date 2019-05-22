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

    public function view (User $loggedUser, User $user){
        return ($loggedUser->id == $user->id) || ($loggedUser->direcao == 1);
    }

    public function administrate(User $loggedUser){
        return $loggedUser->direcao == 1;
    }

}
