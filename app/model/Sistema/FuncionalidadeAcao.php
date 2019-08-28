<?php

namespace App\Model\Sistema;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\ModelPadrao;

class FuncionalidadeAcao extends Model
{
    
    protected $table = 'funcionalidade_acao';
    
    public $fillable = [
       'funcionalidade_id',
       'acao_id',
    ];

    public static function verificaSeTemAcaoCadastrada($listaFuncionalidades) {
        foreach($listaFuncionalidades as &$funcionalidade) {
            $funcionalidadeAcao = DB::table((new FuncionalidadeAcao)->getTable())
                                        ->select('id')
                                        ->where ('funcionalidade_id', '=', $funcionalidade->id)
                                        ->first();

            if (isset($funcionalidadeAcao)) {
                $funcionalidade['acaoCadastrada'] = true;
            } else {
                $funcionalidade['acaoCadastrada'] = false;
            }
        }

        return $listaFuncionalidades;
    }

    public static function salvarLista($listaAcoes, $funcionalidadeId) {
        DB::table((new FuncionalidadeAcao)->getTable())
                                            ->where('funcionalidade_id', '=', $funcionalidadeId)
                                            ->delete();
        foreach($listaAcoes as $acao ) {
            if (!isset($funcionalidadeAcaoBanco)) {
              //  dd($funcionalidadeId);
                $funcionalidadeAcao['funcionalidade_id'] = $funcionalidadeId;
                $funcionalidadeAcao['acao_id'] = $acao['id'];
               // dd($funcionalidadeAcao);
                ModelPadrao::salvar($funcionalidadeAcao, new FuncionalidadeAcao());
            }
        }
    }
}
