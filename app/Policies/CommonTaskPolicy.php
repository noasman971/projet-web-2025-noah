<?php

namespace App\Policies;

use App\Models\User;

class CommonTaskPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    public function viewAnyStudent(User $user)
    {
        $school = $user->school()->pivot->role;


        if ($school === 'admin') {
            return true;
        }
        else{
            return false;
        }


    }
}
