<?php

namespace App;
use Illuminate\Support\Facades\DB;
use Exception;


class ModelPadrao
{
    /**
    *Salva ou atualiza qualquer objeto que seja um Model  Eloquent.
    * 
    * @param $dados {*} Dados para gravação ou atualização.
    * @param $model {Instance Class} Instancia de um Model Eloquent.
    * @return {Object} Objeto que foi salvo no banco.
    */
    public static function salvar($dados, $model)
    {
        $dados = (array) $dados;
        
        if (isset($dados) && isset($dados['id'])) {
            $dadosBanco = $model::find($dados['id']);
        } else {
            $dadosBanco = $model;
        }
        
        $dadosBanco->fill($dados);
        $dadosBanco->save();
        return $dadosBanco;
    }
}
