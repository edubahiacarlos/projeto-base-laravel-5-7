<?php

namespace App\Http\Controllers;

use App\ModelPadrao;
use App\User;
use Illuminate\Http\Request;
;

class CadastroUsuarioController extends Controller
{
    // Iremos chamar a autenticação da API em todas
    // as funções desse Controller exceto no Login.
    public function __construct()
    {
        
    }
    // Autenticação padrão, porém com o JWT.
    public function store(Request $request)
    {
        $this->validacao($request);
        $dados = (object) $request->usuario;
        $dados->password = bcrypt($dados->senha);
        
        return ModelPadrao::salvar($dados, new User(), 'Usuário(a) ' . $dados->name .  ' cadastrado(a) com sucesso');
    }
    
    public function validacao (Request $request) {
        $request->validate([
            'usuario.name' => 'required',
            'usuario.email' => 'required|unique:users,email',
            'usuario.senha' => 'required'
        ]);
    }
}
