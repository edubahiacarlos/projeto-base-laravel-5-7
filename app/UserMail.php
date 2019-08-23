<?php

namespace App;

use Illuminate\Support\Facades\DB;

use Tymon\JWTAuth\Contracts\JWTSubject;

class UserMail 
{
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->from('edubahia.carlos@hotmail.com')
                    ->view('auth.passwords.email')
                    ->with([
                        'user' => $this->user,
                    ]);
    }
}
