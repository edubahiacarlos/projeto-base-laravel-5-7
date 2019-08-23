<?php

namespace App\Http\Controllers\Sistema;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Funcionalidade;

use App\FuncionalidadeAcao;
use App\PerfilFuncionalidadeAcao;
use App\Acao;
use App\ModelPadrao;

class FuncionalidadeController extends Controller
{
    // Iremos chamar a autenticação da API em todas
    // as funções desse Controller exceto no Login.
    public function __construct()
    {
    }
    
    public function index(Request $request) {
        $lista = Funcionalidade::buscaTodasFuncionalidadesPorPagina(10);
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
            FuncionalidadeAcao::where('funcionalidade_id', '=', $id)->delete();
            PerfilFuncionalidadeAcao::where('funcionalidade_id', '=', $id)->delete();
            Funcionalidade::where('id', '=', $id)->delete();
        } catch (Exception $e) {
            return response()->json(['erro' => $e->getMessage()], 422);
        }
        
    }

    function sanitizeString($str) {
        $str = preg_replace('/[áàãâä]/ui', 'a', $str);
        $str = preg_replace('/[éèêë]/ui', 'e', $str);
        $str = preg_replace('/[íìîï]/ui', 'i', $str);
        $str = preg_replace('/[óòõôö]/ui', 'o', $str);
        $str = preg_replace('/[úùûü]/ui', 'u', $str);
        $str = preg_replace('/[ç]/ui', 'c', $str);
        // $str = preg_replace('/[,(),;:|!"#$%&/=?~^><ªº-]/', '_', $str);
        $str = preg_replace('/[^a-z0-9]/i', '_', $str);
        $str = preg_replace('/_+/', '_', $str); // ideia do Bacco :)
        return strtolower($str);
    }

    public function store(Request $request){
        $funcionalidade = $request->input();
      //  $funcionalidade['slug'] = strtolower($funcionalidade['nome']);

        $funcionalidade['slug'] = $this->sanitizeString($funcionalidade['nome']);
        
        ModelPadrao::salvar($funcionalidade, new Funcionalidade());
       
       // $endereco = $request->input()['endereco'];
       // $endereco['usuario_id'] = $usuario->id;
       
        //ModelPadrao::salvar($endereco, new Endereco());

        try {
            return response()->json(['mensagem' => 'Funcionalidade salva com sucesso', 'funcionalidade' => $funcionalidade ]);
        } catch (Exception $e) {
            return response()->json(['erro' => $e->getMessage()], 422);
        }
    }

    public function update(Request $request) {
     
        $atualizarFuncionalidade = $request->input();

        $atualizarFuncionalidade['slug'] = $this->sanitizeString($atualizarFuncionalidade['nome']);
      
        $funcionalidade = ModelPadrao::salvar($atualizarFuncionalidade, new Funcionalidade());

        FuncionalidadeAcao::salvarLista($atualizarFuncionalidade['acoesSelecionadas'], $funcionalidade['id']);

        try {
            return response()->json(['mensagem' => 'Funcionalidade atualizada com sucesso', 'funcionalidade' => $funcionalidade ]);
        } catch (Exception $e) {
            return response()->json(['erro' => $e->getMessage()], 422);
        }
    }

    public function salvaListaFuncionalidades(Request $request) {
        Funcionalidade::salvaLista($request->input()['data']);
    }
}
