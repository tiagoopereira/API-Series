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

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->group(['prefix' => 'series'], function () use ($router) {
        $router->post('/', 'SeriesController@create');
        $router->get('/', 'SeriesController@index');
        $router->get('/{id}', 'SeriesController@show');
        $router->put('/{id}', 'SeriesController@update');
        $router->delete('/{id}', 'SeriesController@destroy');
    });

    $router->group(['prefix' => 'episodes'], function () use ($router) {
        $router->post('/', 'EpisodesController@create');
        $router->get('/', 'EpisodesController@index');
        $router->get('/{id}', 'EpisodesController@show');
        $router->put('/{id}', 'EpisodesController@update');
        $router->delete('/{id}', 'EpisodesController@destroy');
    });
});
