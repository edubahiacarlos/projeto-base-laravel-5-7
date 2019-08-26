<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Perfil;
use App\PerfilUsuario;
use App\Acao;
use App\Funcionalidade;
use App\FuncionalidadeAcao;
use App\PerfilFuncionalidadeAcao;

class UserSeeder extends Seeder
{

    public function __construct() {
        DB::table((new FuncionalidadeAcao)->getTable())->delete();
        DB::table((new PerfilFuncionalidadeAcao)->getTable())->delete();
        DB::table((new Acao)->getTable())->delete();
        DB::table((new Funcionalidade)->getTable())->delete();
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usuario = $this->criarUsuario();
        $perfil = $this->criarPerfil();
        
        $this->criarPerfilUsuario($perfil->id, $usuario->id);
        $this->criarAcao();
        $this->criarFuncionalidade();
        $this->criarFuncionalidadeAcao();
    }

     private function criarUsuario() {
         $usuario = DB::table((new User)->getTable())
                            ->where('email', '=', 'edubahia.carlos@hotmail.com')
                            ->select('id', 'email')
                            ->first();

        if (!isset($usuario)) {
            $usuario = new User();
            $usuario->name = 'Carlos Eduardo';
            $usuario->sobrenome = 'Souza de Oliveira';
            $usuario->cpf = '81608250563';
            $usuario->email = 'edubahia.carlos@hotmail.com';
            $usuario->password = bcrypt(123456);
            $usuario = $usuario->save();
        }

        return $usuario;
     }

     private function criarPerfil() {
        $perfil = DB::table((new Perfil)->getTable())
                           ->where('slug', '=', 'administrador')
                           ->select('id')
                           ->first();

       if (!isset($perfil)) {
           $perfil = new Perfil();
           $perfil->nome = 'Administrador';
           $perfil->slug = 'administrador';
           $perfil = $perfil->save();
       }

       return $perfil;
    }

    private function criarPerfilUsuario($perfilId, $usuarioId) {
        $perfilUsuario = DB::table((new PerfilUsuario)->getTable())
                           ->where('perfil_id', '=', $perfilId)
                           ->where('usuario_id', '=', $usuarioId)
                           ->select('id')
                           ->first();

       if (!isset($perfilUsuario)) {
           $perfilUsuario = new PerfilUsuario();
           $perfilUsuario->usuario_id = $usuarioId;
           $perfilUsuario->perfil_id = $perfilId;
           $perfilUsuario = $perfilUsuario->save();
       }

       return $perfilUsuario;
    }

    private function criarAcao() {
        $acao = new Acao();
        $acao->slug = 'criar';
        $acao->nome = 'Criar';
        $acao->save();

        $acao = new Acao();
        $acao->slug = 'apagar';
        $acao->nome = 'Apagar';
        $acao->save();

        $acao = new Acao();
        $acao->slug = 'atualizar';
        $acao->nome = 'Editar';
        $acao->save();

        $acao = new Acao();
        $acao->slug = 'visualizar';
        $acao->nome = 'Visualizar';
        $acao->save();

        $acao = new Acao();
        $acao->slug = 'listar';
        $acao->nome = 'Listar';
        $acao->save();
       
    }

    private function criarFuncionalidade() {
        

        $acao = new Funcionalidade();
        $acao->slug = 'acao';
        $acao->nome = 'Ação';
        $acao->save();

        $acao = new Funcionalidade();
        $acao->slug = 'usuario';
        $acao->nome = 'Usuário';
        $acao->save();

        $acao = new Funcionalidade();
        $acao->slug = 'perfil';
        $acao->nome = 'Perfil';
        $acao->save();

        $acao = new Funcionalidade();
        $acao->slug = 'funcionalidade';
        $acao->nome = 'Funcionalidade';
        $acao->save();       
    }

    private function criarFuncionalidadeAcao() {
        

        $listaFuncionalidades = DB::table((new Funcionalidade)->getTable())->select('id')->get()->toArray();

        
        foreach ($listaFuncionalidades as $funcionalidade) {
            $listaAcoes = DB::table((new Acao)->getTable())->select('id')->get()->toArray();

            foreach ($listaAcoes as $acao) {
                $funcionalidadeAcao = new FuncionalidadeAcao();   
                $funcionalidadeAcao->funcionalidade_id = $funcionalidade->id;
                $funcionalidadeAcao->acao_id = $acao->id;
                $funcionalidadeAcao->save();
                $this->criarPerfilFuncionalidadeAcao($funcionalidade->id, $acao->id);
            }
        }
    }

    public function criarPerfilFuncionalidadeAcao($funcionalidadeId, $acaoId) {
        $perfilAdmin = DB::table((new Perfil)->getTable())->where('slug', '=', 'administrador')->select('id')->first();

        if (isset($perfilAdmin)) {

            $perfilFuncionalidadeAcao = new PerfilFuncionalidadeAcao();
            $perfilFuncionalidadeAcao->perfil_id = $perfilAdmin->id;
            $perfilFuncionalidadeAcao->funcionalidade_id = $funcionalidadeId;
            $perfilFuncionalidadeAcao->acao_id = $acaoId;
            $perfilFuncionalidadeAcao->save();
        }
    }
}
