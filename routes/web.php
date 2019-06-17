<?php
use Illuminate\Redis;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/cro', 'CROController@index');
$router->get('/cro/{id}', 'CROController@show');
$router->post('/cro', 'CROController@store');
$router->put('/cro/{id}', 'CROController@update');
$router->delete('/cro/{id}', 'CROController@softDelete');
$router->update('/cro/{id}', 'CROController@restore');
$router->delete('/cro/{id}', 'CROController@destroy');
