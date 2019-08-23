<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Perfil extends Model
{
    
    protected $table = 'perfil';
    
    public $fillable = [
       'slug',
       'nome'
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

    public static function autorizado($funcionalidade, $usuarioId) {
        $perfil = DB::table('perfil as p')
                    ->select('p.id')
                    ->join('perfil_funcionalidade_acao as pfa', 'p.id', '=', 'pfa.perfil_id')
                    ->join('funcionalidade as f', 'pfa.funcionalidade_id', '=', 'f.id')
                    ->join('perfil_usuario as pu', 'p.id', '=', 'pu.perfil_id')
                    ->where('f.slug', '=', $funcionalidade)
                    ->where('pu.usuario_id', '=', $usuarioId)
                    ->first();

        
        if (isset($perfil)) {
            return true;
        }

        return false;
    }

    public static function teste($usuarioId) {
        $instanciaPerfilUsuario = new PerfilUsuario();
        $perfil = DB::table($instanciaPerfilUsuario->getTable())
                        ->where('usuario_id', '=', $usuarioId)
                        ->select('perfil_id')
                        ->first();

        return DB::table('funcionalidade as f')
                    ->select(
                        'f.id', 'f.nome as funcionalidade', 'f.slug',
                        DB::raw("
                            (select distinct
                                string_agg(a.slug::text, '-') as acoes
                                from acao a
                                inner join perfil_funcionalidade_acao pfa
                                on pfa.acao_id = a.id
                                and pfa.funcionalidade_id = f.id
                                and perfil_id = " . $perfil->perfil_id . "
                            )"
                        )
                    )->get()->toArray();
                    

    }

    public static function todos() {
        return DB::table((new Perfil)->getTable())
                    ->select((new Perfil)->getFillable())
                    ->addSelect('id')
                    ->get()->toArray();
    }

    public static function perfilUsuario($usuarioId) {
        return DB::table((new Perfil)->getTable() . ' as p')
                    ->select((new Perfil)->getFillable())
                    ->addSelect('p.id')
                    ->join('perfil_usuario as pu', 'p.id', '=', 'pu.perfil_id')
                    ->where('pu.usuario_id', '=', $usuarioId)
                    ->first();
    }
}
