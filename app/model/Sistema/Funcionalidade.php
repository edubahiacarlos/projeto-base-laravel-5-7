<?php

namespace App\Model\Sistema;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\ModelPadrao;

class Funcionalidade extends Model
{
    
    protected $table = 'funcionalidade';
    
    public $fillable = [
       'nome',
       'slug',
    ];

    /**
    * Busca todos os endereços por página.
    * Temos que colocar o atributo page na requisição para
    * o próprio Laravel fazer o tratamento da página que será carregada.
    * @param $quantidadePorPagina {int} Quantidade de registros por página.
    * @return array de Endereços
    */
    public static function buscaTodasFuncionalidadesPorPagina(int $quantidadePorPagina) {
        return Funcionalidade::orderBy('nome', 'asc')
                                ->select((new Funcionalidade)->getFillable())
                                ->addSelect('id')
                                ->paginate($quantidadePorPagina);
    }

    public static function buscaTodasFuncionalidades() {
        return DB::table((new Funcionalidade)->getTable())
                ->select((new Funcionalidade)->getFillable())
                ->addSelect('id')
                ->orderBy('nome', 'asc')
                ->get()->toArray();
    }

    public static function buscarFuncionalidadePorId(int $funcionalidadeId){
        return DB::table(( new Funcionalidade)->getTable())
                    ->where('id', '=', $funcionalidadeId)
                    ->select((new Funcionalidade)->getFillable())
                    ->addSelect('id')
                    ->first();
    }

    public static function salvaLista($listaFuncionalidades) {
        foreach ($listaFuncionalidades as $funcionalidade) {
            $model = new Funcionalidade();
            $existe = DB::table($model->getTable())->where('slug', '=', $funcionalidade['slug'])->first();
            
            if (isset($existe)) {
                continue;
            }

            $dados['slug'] = $funcionalidade['slug'];
            $dados['nome'] = $funcionalidade['nome'];
            ModelPadrao::salvar($dados, new Funcionalidade());
        }
    }
}
