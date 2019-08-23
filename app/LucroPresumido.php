<?php

namespace App;

class LucroPresumido
{
    /**
    * Consulta o blog da Agilize e pega os dados da tabela Lucro Presumido.
    *
    * @return String 
    */
    public static function consultarTabelaLucroPresumido() {
        $url = 'https://blog.agilize.com.br/lucro-presumido/embutindo-os-impostos-no-valor-da-nota/';
        
        //Pega todos os dados do site
        $dadosSite = file_get_contents($url);

        //Reduz a quantidade dos dados.
        $dadoResumido = explode('<p style="color: #555555;"><span class="wysiwyg-font-size-medium">As alíquotas (impostos) mais comuns para os pequenos empresários de serviço são:</span></p>', $dadosSite);

        //Pega apenas a tabela Lucro Presumido e retorna o código html
        return explode('<h2 style="color: #555555;"><span class="wysiwyg-font-size-medium">', $dadoResumido[1])[1];
    }
}
