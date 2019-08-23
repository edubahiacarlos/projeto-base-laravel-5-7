<?php

namespace App\Http\Controllers\Sistema;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Acao;
use App\FuncionalidadeAcao;
use App\PerfilFuncionalidadeAcao;
use App\ModelPadrao;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
            PerfilFuncionalidadeAcao::where('acao_id', '=', $id)->delete();
            FuncionalidadeAcao::where('acao_id', '=', $id)->delete();
            Acao::where('id', '=', $id)->delete();
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
        $acao = $request->input();
        $acao['slug'] = $this->sanitizeString($acao['nome']);

        $acao = ModelPadrao::salvar($acao, new Acao());

        try {
            return response()->json(['mensagem' => 'Ação salva com sucesso', 'acao' => $acao ]);
        } catch (Exception $e) {
            return response()->json(['erro' => $e->getMessage()], 422);
        }
    }

    public function update(Request $request) {
     
        $acao = $request->input();

        $acao['slug'] = $this->sanitizeString($acao['nome']);

        $acao = ModelPadrao::salvar($acao, new Acao());

        try {
            return response()->json(['mensagem' => 'Ação atualizada com sucesso', 'acao' => $acao ]);
        } catch (Exception $e) {
            return response()->json(['erro' => $e->getMessage()], 422);
        }
    }

    public function dadosDominio() {
        return Acao::todos();
    }
}
