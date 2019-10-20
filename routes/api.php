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

$this->group(['prefix' => 'v1'], function () {

    /*
    | Acesso autenticado e autorizado
    */
    $this->group([ 'middleware' => ['auth:api', 'autorizado']], function () {
        $this->resource('usuario', 'Usuario\UsuarioController');
        $this->resource('funcionalidade', 'Sistema\FuncionalidadeController');
        $this->resource('perfil', 'Sistema\PerfilController');
        $this->resource('acao', 'Sistema\AcaoController');
    });
    /*
    | Fim dos Middlewares auth:api e autorizado
    */

    /*
    | Acesso autenticado
    */
    $this->group([ 'middleware' => ['auth:api']], function () {
        $this->get('autorizacao/{funcionalidade}', 'Sistema\PerfilController@verificaAutorizacao')->middleware('auth:api');
        $this->get('perfildadosdominio', 'Sistema\PerfilController@dadosDominio')->middleware('auth:api');
        $this->get('acaodadosdominio', 'Sistema\AcaoController@dadosDominio')->middleware('auth:api');
        $this->get('funcionalidadescomacoes', 'Sistema\FuncionalidadeController@funcionalidadesComAcoes');


        $this->post('listafuncionalidades', 'Sistema\FuncionalidadeController@salvaListaFuncionalidades')->middleware('autorizado');

        $this->resource('criarusuario', 'CadastroUsuarioController');

//$this->post('alterar', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    });
    /*
    | Fim do middleware auth:api
    */

});








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
 