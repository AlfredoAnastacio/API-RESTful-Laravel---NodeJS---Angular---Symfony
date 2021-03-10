<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Cargando clases
use App\Http\Middleware\ApiAuthMiddleware;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/usuario/pruebas', 'UserController@pruebas');
Route::get('/categoria/pruebas', 'CategoryController@pruebas');
Route::get('/entrada/pruebas', 'PostController@pruebas');

// Endpoint de Autenticación
Route::post('/api/register', 'UserController@register');
Route::post('/api/login', 'UserController@login');

// Endpoints de Usuarios
Route::put('/api/user/update', 'UserController@update');
Route::post('/api/user/upload', 'UserController@upload')->middleware(ApiAuthMiddleware::class);
Route::get('/api/user/avatar/{filename}', 'UserController@getImage');
Route::get('/api/user/detail/{id}', 'UserController@detail');

// Controlador de Categorías
Route::resource('/api/category', 'CategoryController');

// Controlador de Entradas
Route::resource('/api/post', 'PostController');
Route::post('/api/post/upload', 'PostController@upload');
