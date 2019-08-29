<?php

namespace App\Model\Endereco;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Endereco extends Model
{
    
    protected $table = 'endereco';
    
    public $fillable = [
       'usuario_id',
       'cep',
       'rua',
       'numero',
       'complemento',
       'bairro',
       'cidade',
       'estado',
       'ibge'
    ];

    /**
    * Busca todos os endereços por página.
    * Temos que colocar o atributo page na requisição para
    * o próprio Laravel fazer o tratamento da página que será carregada.
    * @param $quantidadePorPagina {int} Quantidade de registros por página.
    * @return array de Endereços
    */
    public static function buscaTodosEnderecoPorPagina(int $quantidadePorPagina) {
        return Endereco::orderBy('uf', 'asc')
                        ->orderBy('localidade', 'asc')
                        ->orderBy('logradouro', 'asc')
                        ->paginate($quantidadePorPagina);
    }

    /**
    * Busca um Endereço por CEP.
    *
    * @param $cep {string}
    * @return Endereco {Objeto}.
    */
    public static function buscaEnderecoPorCep(string $cep) {
        return Endereco::where('cep', '=', $cep)->first();
    }

    public static function buscaEnderecoDoUsuario($usuarioId){
        return DB::table('endereco')
                    ->where('usuario_id', '=', $usuarioId)
                    ->select((new Endereco)->getFillable())
                    ->addSelect('id')
                    ->first();
    }
}
