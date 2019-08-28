<?php

namespace App\Http\Controllers\Sistema;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Sistema\Perfil;
use App\Model\Sistema\Funcionalidade;
use App\Model\Sistema\Acao;
use App\Model\Sistema\Config;
use App\Model\Sistema\PerfilFuncionalidadeAcao;


use App\User;
use App\ModelPadrao;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PerfilController extends Controller
{
    // Iremos chamar a autenticação da API em todas
    // as funções desse Controller exceto no Login.
    public function __construct()
    {
    }
    
    public function index(Request $request) {
        return Perfil::buscaTodosPerfisPorPagina(Config::itensPorPagina);
    }

    public function show($id) {
   
        try {
            $perfil = Perfil::buscaPerfilPorId($id);
            $perfil->funcionalidades = Funcionalidade::buscaTodasFuncionalidades();
            $perfil->funcionalidades = Acao::acoesSelecionadasDasFuncionalidadesPorPerfil($perfil->funcionalidades, $id);
            $perfil->funcionalidades = Acao::acoesPorFuncionalidades($perfil->funcionalidades);
            
            return response()->json($perfil);
        } catch (Exception $e) {
            return response()->json(['erro' => $e->getMessage()], 422);
        }
    }

    public function destroy($id) {
        try {
            PerfilFuncionalidadeAcao::where('perfil_id', '=', $id)->delete();
            Perfil::where('id', '=', $id)->delete();
        } catch (Exception $e) {
            return response()->json(['erro' => $e->getMessage()], 422);
        }
    }

    public function salvar(Request $request, $mensagem) {
        try {
            DB::beginTransaction();
            $perfil = $request->input();
            $perfil['slug'] = Config::criaSlug($perfil['nome']);
            $funcionalidades = $perfil['funcionalidades'];

            $perfil = ModelPadrao::salvar($perfil, new Perfil());
            PerfilFuncionalidadeAcao::salvaLista($funcionalidades, $perfil->id);
            DB::commit();
            return response()->json(['mensagem' => $mensagem, 'perfil' => $perfil ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['erro' => $e->getMessage()], 422);
        }
    }

    public function store(Request $request){
        $this->salvar($request, 'Perfil salvo com sucesso');  
    }

    public function update(Request $request) {
        $this->salvar($request, 'Perfil atualizado com sucesso');
    }

    public function verificaAutorizacao($funcionalidade) {
        return response()->json(Perfil::autorizado($funcionalidade, Auth::user()->id));
    }

    public function dadosDominio() {
        return Perfil::todos();
    }
}
