<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PerfilFuncionalidadeAcao extends Model
{
    
    protected $table = 'perfil_funcionalidade_acao';
    
    public $fillable = [
       'perfil_id',
       'funcionalidade_id',
       'acao_id'
    ];

    /**
    * Busca todos os endereços por página.
    * Temos que colocar o atributo page na requisição para
    * o próprio Laravel fazer o tratamento da página que será carregada.
    * @param $quantidadePorPagina {int} Quantidade de registros por página.
    * @return array de Endereços
    */
    public static function buscaTodosPerfisPorPagina(int $quantidadePorPagina) {
        return Perfil::orderBy('nome', 'asc')
                        ->paginate($quantidadePorPagina);
    }

    public static function buscaPerfilPorId($id){
        $perfil = DB::table((new Perfil)->getTable())
                    ->where('id', '=', $id)
                    ->select((new Perfil)->getFillable())
                    ->addSelect('id')
                    ->first();

        $perfil->funcionalidades = DB::table((new Funcionalidade)->getTable())
                                    ->select((new Funcionalidade)->getFillable())
                                    ->addSelect('id')
                                    ->orderBy('nome')
                                    ->get()->toArray();

        foreach ($perfil->funcionalidades as &$funcionalidade) {
            $funcionalidade->acoesSelecionadas = DB::table('acao as a')
                                                    ->distinct()
                                                    ->select('a.id', 'a.slug', 'a.nome')
                                                    ->join('perfil_funcionalidade_acao as pfa', 'a.id', '=', 'pfa.acao_id')
                                                    ->where('pfa.funcionalidade_id', '=', $funcionalidade->id)
                                                    ->where('pfa.perfil_id', '=', $perfil->id)
                                                    ->get()->toArray();
            
            $funcionalidade->acoes = DB::table('acao as a')
                                                    ->distinct()
                                                    ->select('a.id', 'a.slug', 'a.nome')
                                                    ->join('funcionalidade_acao as fa', 'a.id', '=', 'fa.acao_id')
                                                    ->where('fa.funcionalidade_id', '=', $funcionalidade->id)
                                                    ->get()->toArray();
        }
        
        return $perfil;
    }
}
