<?php

namespace App;


use Illuminate\Database\Eloquent\Model;


class _Endereco extends Model
{
    
    protected $table = 'enderecos';
    
    protected $fillable = [
        'cep', 'logradouro', 'bairro', 'localidade', 'uf', 'ibge', 'id'
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
}
