<?php

namespace App\Http\Controllers\Usuario;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Endereco;
use App\User;
use App\Perfil;
use App\PerfilUsuario;
use App\ModelPadrao;

class UsuarioController extends Controller
{
    // Iremos chamar a autenticação da API em todas
    // as funções desse Controller exceto no Login.
    public function __construct()
    {
    }
    
    public function index(Request $request) {
        return User::buscaTodosUsuariosPorPagina(2);
    }

    public function show($id) {
   
        try {
            $usuario = User::buscarUsuarioPorId($id);
            $usuario->perfil = Perfil::perfilUsuario($id);
            $usuario->endereco = Endereco::buscaEnderecoDoUsuario($id);
            return response()->json($usuario);
        } catch (Exception $e) {
            return response()->json(['erro' => $e->getMessage()], 422);
        }
    }

    public function destroy($id) {
        try {
            Endereco::where('usuario_id', '=', $id)->delete();
            User::where('id', '=', $id)->delete();
        } catch (Exception $e) {
            return response()->json(['erro' => $e->getMessage()], 422);
        }
        
    }

    public function store(Request $request){
        $usuario = $request->input();
        $usuario['password'] = bcrypt(123);

        $usuario = ModelPadrao::salvar($usuario, new User());
       
        $dadosPerfil['usuario_id'] = $usuario['id'];
        $dadosPerfil['perfil_id'] = $atualizarUsuario['perfil']['id'];

        PerfilUsuario::where('usuario_id', '=', $usuario['id'])->delete();
        ModelPadrao::salvar($dadosPerfil, new PerfilUsuario());

        $endereco = $request->input()['endereco'];
        $endereco['usuario_id'] = $usuario->id;
       
        ModelPadrao::salvar($endereco, new Endereco());

        try {
            return response()->json(['mensagem' => 'Usuário salvo com sucesso', 'usuario' => $usuario ]);
        } catch (Exception $e) {
            return response()->json(['erro' => $e->getMessage()], 422);
        }
    }

    public function update(Request $request) {
     
        $atualizarUsuario = $request->input();
      
        //dd($atualizarUsuario);
        $usuario = ModelPadrao::salvar($atualizarUsuario, new User());

        $dadosPerfil['usuario_id'] = $usuario['id'];
        $dadosPerfil['perfil_id'] = $atualizarUsuario['perfil']['id'];

        PerfilUsuario::where('usuario_id', '=', $usuario['id'])->delete();
        ModelPadrao::salvar($dadosPerfil, new PerfilUsuario());

        $atualizarEndereco = $atualizarUsuario['endereco'];
        $atualizarEndereco['usuario_id'] = $usuario->id;

        ModelPadrao::salvar($atualizarEndereco, new Endereco());

        try {
            return response()->json(['mensagem' => 'Usuário atualizado com sucesso', 'usuario' => $usuario ]);
        } catch (Exception $e) {
            return response()->json(['erro' => $e->getMessage()], 422);
        }
    }
}
