<?php

namespace App\Http\Controllers\Sistema;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Sistema\Acao;

use App\Model\Sistema\FuncionalidadeAcao;
use App\Model\Sistema\PerfilFuncionalidadeAcao;
use App\ModelPadrao;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Model\Sistema\Config;

class AcaoController extends Controller
{
    // Iremos chamar a autenticação da API em todas
    // as funções desse Controller exceto no Login.
    public function __construct()
    {
    }
    
    public function index(Request $request) {
        return Acao::buscaTodasAcoesPorPagina(10);
    }

    public function show($id) {
   
        try {
            return response()->json(Acao::buscaPerfilPorId($id));
        } catch (Exception $e) {
            return response()->json(['erro' => $e->getMessage()], 422);
        }
    }

    public function destroy($id) {
        try {
            DB::beginTransaction();
            PerfilFuncionalidadeAcao::where('acao_id', '=', $id)->delete();
            FuncionalidadeAcao::where('acao_id', '=', $id)->delete();
            Acao::where('id', '=', $id)->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['erro' => $e->getMessage()], 422);
        }
        
    }

    public function salvar(Request $request) {
        $acao = $request->input();
        $acao['slug'] =  Config::criaSlug($acao['nome']);
        
        try {
            DB::beginTransaction();
            $acao = ModelPadrao::salvar($acao, new Acao());
            DB::commit();
            return response()->json(['mensagem' => 'Ação salva com sucesso', 'acao' => $acao ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['erro' => $e->getMessage()], 422);
        }
    }

    public function store(Request $request){
        $this->salvar($request, 'Ação salva com sucesso');
    }

    public function update(Request $request) {
        $this->salvar($request, 'Perfil salvo com sucesso');
    }

    public function dadosDominio() {
        return Acao::todos();
    }
}
