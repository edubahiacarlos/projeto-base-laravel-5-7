<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
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
        $this->middleware('guest');
    }

    public function reset(Request $request)
    {
        
        $response = $this->broker()->reset(
            $this->credentials($request),
            function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );
       
        if ($response == 'passwords.token') {
            return response()->json(['mensagem' => 'Token expirado'], 400);
        }

        if ($response == 'passwords.user') {
            return response()->json(['mensagem' => 'Email não cadastrado'], 400);
        }

        if ($response == 'passwords.password') {
            return response()->json(['mensagem' => 'As senhas não são iguais'], 400);
        }

        return response()->json(['mensagem' => 'Senha Alterada com sucesso'], 200);
    }
}
