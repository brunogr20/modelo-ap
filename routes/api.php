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


Route::get('cadastro/cidades/{uf}', 'ApiUsuarioController@cidades');
Route::post('/usuario/ocorrenciadados', "ApiUsuarioController@occurrences");
Route::post('/usuario/login', "ApiUsuarioController@auth");
Route::post('/usuario/cadastrar', "ApiUsuarioController@cadastrar");

Route::middleware('auth:api')->get('/user', function (Request $request) {

Route::post('/usuario/master', "ApiUsuarioController@userMaster");
Route::post('/usuario/pesquisa/master', "ApiUsuarioController@pesquisaMasterUsuarios");

Route::post('/master/listainternautas', "ApiInternautaController@indexListaInternautas");
Route::post('/master/deltar/internautaresposta', "ApiInternautaController@deletarInternauta");


Route::post('/gerenciar/emails', "ApiPesquisasEmailController@index");
Route::post('/gerenciar/emails/editar', "ApiPesquisasEmailController@editar");



 return $request->user();
});
