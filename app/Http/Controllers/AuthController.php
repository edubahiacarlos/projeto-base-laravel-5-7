<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Model\Sistema\Perfil;
use App\User;
use Mail;

class AuthController extends Controller
{
    // Iremos chamar a autenticação da API em todas
    // as funções desse Controller exceto no Login.
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }
    // Autenticação padrão, porém com o JWT.
    public function login(Request $request)
    {

        $this->validacao($request);
        $credentials = $request->only(['email', 'password']);
        //dd($credentials);
        if (! $token = JWTAuth::attempt($credentials)) {
            
            return response()->json(['status' => 401, 'mensagem' => 'Credenciais Inválidas!'], 401);
        }
		
        return response()->json([
            'status' => 200,
			'mensagem' => 'Usuário logado no sistema!',
            'usuarioLogado' => Auth::user(),
            'controleAcesso' => Perfil::teste(Auth::user()->id),
            'token' => 'Bearer ' . $token
        ]);
    }
    // Renovação de Token
    public function refresh()
    {
        $token = JWTAuth::getToken();
        $newToken = JWTAuth::refresh($token);
        return response()->json([
            'token' => $newToken
        ]);
    }
    // Retorna as informações da sessão atual
    public function me(){
        return response()->json(Auth::user());
    }
    // Invalida a sessão atual
    public function logout(){
        $token = JWTAuth::getToken();
        JWTAuth::invalidate($token);
        return response()->json([
           'status' => 'success'
        ]);
    }
    
    public function validacao (Request $request) {
        $request->validate([
            'password' => 'required',
            'email' => 'required'
        ]);
    }
}
