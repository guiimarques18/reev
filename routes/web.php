<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->post('survivors/{id}', 'SurvivorsController@postSurvivor');
$router->post('survivors/{id}/inventory', 'SurvivorsController@postSurvivorInventory'); // TODO
$router->get('survivors/{id}', 'SurvivorsController@getSurvivor');
$router->patch('survivors/{id}', 'SurvivorsController@patchSurvivor');
$router->get('survivors/{id}/reports', 'SurvivorsController@getReports'); // TODO

$router->post('survivors/{id}/infected', 'InfectedController@postInfected');

$router->post('survivors/{id}/trade', 'TradeController@postTrade'); // TODO