<?php

namespace App\Http\Controllers\Sistema;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Sistema\Funcionalidade;
use App\Model\Sistema\FuncionalidadeAcao;
use App\Model\Sistema\PerfilFuncionalidadeAcao;
use App\Model\Sistema\Acao;
use App\ModelPadrao;
use App\Model\Sistema\Config;
use Illuminate\Support\Facades\DB;

class FuncionalidadeController extends Controller
{
    // Iremos chamar a autenticação da API em todas
    // as funções desse Controller exceto no Login.
    public function __construct()
    {
    }
    
    public function index(Request $request) {
        $lista = Funcionalidade::buscaTodasFuncionalidadesPorPagina(Config::itensPorPagina);
        $lista = FuncionalidadeAcao::verificaSeTemAcaoCadastrada($lista);
        return $lista;
    }

    public function show($id) {
        try {
            $funcionalidade = Funcionalidade::buscarFuncionalidadePorId($id);
            $funcionalidade->acoesSelecionadas = Acao::buscaAcaoPorFuncionalidade($id);
            return response()->json($funcionalidade);
        } catch (Exception $e) {
            return response()->json(['erro' => $e->getMessage()], 422);
        }
    }

    public function destroy($id) {
        try {
            DB::beginTransaction();
            FuncionalidadeAcao::where('funcionalidade_id', '=', $id)->delete();
            PerfilFuncionalidadeAcao::where('funcionalidade_id', '=', $id)->delete();
            Funcionalidade::where('id', '=', $id)->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['erro' => $e->getMessage()], 422);
        }
        
    }

    public function salvar(Request $request, $mensagem) {
        try {
            DB::beginTransaction();
            $funcionalidade = $request->input();
            $funcionalidade['slug'] = Config::criaSlug($funcionalidade['nome']);

            $acoesSelecionadas = $funcionalidade['acoesSelecionadas'];

            $funcionalidade = ModelPadrao::salvar($funcionalidade, new Funcionalidade());

            FuncionalidadeAcao::salvarLista($acoesSelecionadas, $funcionalidade['id']);
            DB::commit();
            return response()->json(['mensagem' => $mensagem, 'funcionalidade' => $funcionalidade ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['erro' => $e->getMessage()], 422);
        }
        
    }
    public function store(Request $request){
        $this->salvar($request, 'Funcionalidade salva com sucesso');        
    }

    public function update(Request $request) {
        $this->salvar($request, 'Funcionalidade salva com sucesso');
    }

    public function salvaListaFuncionalidades(Request $request) {
        try {
            DB::beginTransaction();
            Funcionalidade::salvaLista($request->input()['data']);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['erro' => $e->getMessage()], 422);
        }
    }

    public function funcionalidadesComAcoes() {
        $funcionalidades = Acao::acoesPorFuncionalidades(Funcionalidade::buscaTodasFuncionalidades());

        foreach($funcionalidades as &$funcionalidade) {
            $funcionalidade->acoesSelecionadas = [];
        }

        return $funcionalidades;
    }
}
