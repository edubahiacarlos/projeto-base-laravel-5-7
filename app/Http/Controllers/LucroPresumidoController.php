<?php

namespace App\Http\Controllers;

use App\LucroPresumido;
use Illuminate\Http\Request;
use Exception;

class LucroPresumidoController extends Controller
{
    /**
    * Responsável pelas requisições GET do recurso Lucro Presumido.
    *
    * @param Request $request Dados da requisição
    * @return json
    */
    public function index(Request $request)
    {
        try {
            return response()->json(['codigoHtml' => LucroPresumido::consultarTabelaLucroPresumido()]);
        } catch (Exception $e) {
            return response()->json(['erro' => $e->getMessage()], 422);
        }
    }
}
