<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Acao extends Model
{
    
    protected $table = 'acao';
    
    public $fillable = [
       'nome',
       'slug',
    ];

    public static function buscaTodasAcoesPorPagina(int $quantidadePorPagina) {
        return Acao::orderBy('nome', 'asc')
                        ->select((new Acao)->getFillable())
                        ->addSelect('id')
                        ->paginate($quantidadePorPagina);
    }

    public static function buscaPerfilPorId($id){
        return DB::table((new Acao)->getTable())
                    ->where('id', '=', $id)
                    ->select((new Acao)->getFillable())
                    ->addSelect('id')
                    ->first();
    }

    public static function buscaAcaoPorFuncionalidade($funcionalidadeId) {
        $acao = new Acao();
        return DB::table($acao->getTable() . ' as a')
                    ->select($acao->getFillable())
                    ->addSelect('a.id')
                    ->join('funcionalidade_acao as fa', 'a.id', '=', 'fa.acao_id')
                    ->where('funcionalidade_id', '=', $funcionalidadeId)
                    ->get()->toArray();
    }

    public static function todos() {
        return DB::table((new Acao)->getTable())
                    ->select((new Acao)->getFillable())
                    ->addSelect('id')
                    ->orderBy('nome', 'asc')
                    ->get()->toArray();
    }
}
