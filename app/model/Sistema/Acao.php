<?php

namespace App\Model\Sistema;


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
                    ->orderBy('nome', 'asc')
                    ->get()->toArray();
    }

    public static function acoesSelecionadasDasFuncionalidadesPorPerfil($listaFuncionalidades, $perfilId) {

        foreach($listaFuncionalidades as &$funcionalidade) {
            $funcionalidade->acoesSelecionadas = DB::table('acao as a')
                                                    ->distinct()
                                                    ->select('a.id', 'a.slug', 'a.nome')
                                                    ->join('perfil_funcionalidade_acao as pfa', 'a.id', '=', 'pfa.acao_id')
                                                    ->where('pfa.funcionalidade_id', '=', $funcionalidade->id)
                                                    ->where('pfa.perfil_id', '=', $perfilId)
                                                    ->orderBy('nome', 'asc')
                                                    ->get()->toArray();
        }

        return $listaFuncionalidades;
    }

    public static function acoesPorFuncionalidades($listaFuncionalidades) {

        foreach($listaFuncionalidades as &$funcionalidade) {
            $funcionalidade->acoes = DB::table('acao as a')
                                        ->distinct()
                                        ->select('a.id', 'a.slug', 'a.nome')
                                        ->join('funcionalidade_acao as fa', 'a.id', '=', 'fa.acao_id')
                                        ->where('fa.funcionalidade_id', '=', $funcionalidade->id)
                                        ->orderBy('nome', 'asc')
                                        ->get()->toArray();
        }
        
        return $listaFuncionalidades;
    }

    public static function todos() {
        return DB::table((new Acao)->getTable())
                    ->select((new Acao)->getFillable())
                    ->addSelect('id')
                    ->orderBy('nome', 'asc')
                    ->get()->toArray();
    }
}
