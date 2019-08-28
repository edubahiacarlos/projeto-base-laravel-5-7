<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
 

$this->resource('endereco', 'EnderecoController')->middleware('auth:api');
$this->resource('lucropresumido', 'LucroPresumidoController')->middleware('auth:api');
$this->resource('usuarios', 'Usuario\UsuarioController')->middleware('auth:api');
$this->resource('funcionalidades', 'Sistema\FuncionalidadeController')->middleware('auth:api');
$this->resource('perfis', 'Sistema\PerfilController')->middleware('auth:api');
$this->resource('acoes', 'Sistema\AcaoController')->middleware('auth:api');

$this->get('autorizacao/{funcionalidade}', 'Sistema\PerfilController@verificaAutorizacao')->middleware('auth:api');
$this->get('perfildadosdominio', 'Sistema\PerfilController@dadosDominio')->middleware('auth:api');
$this->get('acaodadosdominio', 'Sistema\AcaoController@dadosDominio')->middleware('auth:api');
$this->get('funcionalidadescomacoes', 'Sistema\FuncionalidadeController@funcionalidadesComAcoes')->middleware('auth:api');


$this->post('listafuncionalidades', 'Sistema\FuncionalidadeController@salvaListaFuncionalidades')->middleware('auth:api');

$this->resource('criarusuario', 'CadastroUsuarioController');

//$this->post('alterar', 'Auth\ForgotPasswordController@sendResetLinkEmail');


Route::group(['prefix' => 'auth'], function () {
    Route::post('alterarsenha', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::post('trocarsenha', 'Auth\ResetPasswordController@reset')->name('reset');
});
        
$this->group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'AuthController@login')->name('login');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::post('logout', 'AuthController@logout');
});
 