<?php

namespace App\Http\Controllers\Usuario;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Endereco\Endereco;
use App\User;
use App\Model\Sistema\Perfil;
use App\Model\Sistema\PerfilUsuario;
use App\ModelPadrao;
use Illuminate\Support\Facades\DB;

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
            DB::beginTransaction();
            Endereco::where('usuario_id', '=', $id)->delete();
            PerfilUsuario::where('usuario_id', '=', $id)->delete();
            User::where('id', '=', $id)->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['erro' => $e->getMessage()], 422);
        }
        
    }

    public function salvar($usuario, $mensagem) {
        try {
            DB::beginTransaction();
            $dadosPerfil['perfil_id'] = $usuario['perfil']['id'];
            $endereco = $usuario['endereco'];

            $usuario = ModelPadrao::salvar($usuario, new User());
            $dadosPerfil['usuario_id'] = $usuario['id'];

            PerfilUsuario::where('usuario_id', '=', $usuario['id'])->delete();
            ModelPadrao::salvar($dadosPerfil, new PerfilUsuario());

            $endereco['usuario_id'] = $usuario->id;
        
            ModelPadrao::salvar($endereco, new Endereco());
            DB::commit();
            return response()->json(['mensagem' => $mensagem, 'usuario' => $usuario ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['erro' => $e->getMessage()], 422);
        }
    }

    public function store(Request $request){
        $usuario = $request->input();
        $usuario['endereco'] = $request->input()['endereco'];
        $usuario['password'] = bcrypt($usuario['cpf']);
        $this->salvar($usuario, 'Usuário salvo com sucesso');  
    }

    public function update(Request $request) {
        $usuario = $request->input();
        $usuario['endereco'] = $request->input()['endereco'];
        $this->salvar($usuario, 'Usuário atualizado salvo com sucesso'); 
    }
}
