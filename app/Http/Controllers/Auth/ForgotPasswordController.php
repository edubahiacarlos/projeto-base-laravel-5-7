<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use App\User;
use App\UserMail;
use Mail;
use Hash;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function sendResetLinkEmail(Request $request)
    {
        
        $email = $request->get('email');
        $user = User::where('email', '=', $email)->first();

        if (!$user || !$user->email) {
            return response()->json(['mensagem' => 'Email não cadastrado'], 404);
        }

        $token = app('auth.password.broker')->createToken($user);

        if (!$token) {
            return response()->json(['mensagem' => 'Erro interno. Entre em contato com o administrador'], 500);
        }

        Mail::send('auth.passwords.email', ['hash' => $token, 'usuario' => $user, 'url' => $request->get('urlAPI')], function ($m) use ($user) {
            $m->from('edubahia.carlos@hotmail.com', 'Sistema ABC');

            $m->to($user->email, $user->name)->subject('Alterar Senha');
        });
        
        return response()->json(['mensagem' => 'Email enviado para ' . $user->email]);
    }

    public function broker()
    {
         return Password::broker('passwords');
    }
}
