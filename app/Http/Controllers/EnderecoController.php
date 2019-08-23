<?php

namespace App\Http\Controllers;

use App\ModelPadrao;
use App\Endereco;
use Illuminate\Http\Request;
;

class EnderecoController extends Controller
{    
    /**
    * Responsável pelas requisições GET do recurso Endereço.
    *
    * @param Request $request Dados da requisição
    * @return json
    */
    public function index(Request $request)
    {
        return Endereco::buscaTodosEnderecoPorPagina(10);
    }
    
    /**
    * Responsável pelas requisições POST do recurso Endereço.
    *
    * @param Request $request Dados da requisição
    * @return json
    */
    public function store(Request $request)
    {
        $dados = (object) $request->endereco;

        if (isset($dados) && isset($dados->cep)) {
            $enderecoBanco = Endereco::buscaEnderecoPorCep($dados->cep);
        }

        if (isset($enderecoBanco) && isset($enderecoBanco->id)) {
            $dados->id = $enderecoBanco->id;
        }

        try {
            return response()->json(['mensagem' => 'Endereço salvo com sucesso', 'endereco' => ModelPadrao::salvar($dados, new Endereco())]);
        } catch (Exception $e) {
            return response()->json(['erro' => $e->getMessage()], 422);
        }
    }
}
