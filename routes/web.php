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
    $router->post('/auth/login', ['as' => 'auth.login', 'uses' => 'AuthController@login']);

    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->group(['prefix' => 'series'], function () use ($router) {
            $router->post('/', ['as'=> 'series.create', 'uses' => 'SeriesController@create']);
            $router->get('/', ['as' => 'series.index', 'uses' => 'SeriesController@index']);
            $router->get('/{id}', ['as' => 'series.show', 'uses' => 'SeriesController@show']);
            $router->put('/{id}', ['as' => 'series.update', 'uses' => 'SeriesController@update']);
            $router->delete('/{id}', ['as' => 'series.destroy', 'uses' => 'SeriesController@destroy']);

            $router->get('/{serieId}/episodes', ['as' => 'series.episodes', 'uses' => 'EpisodesController@getSerieEpisodes']);
        });

        $router->group(['prefix' => 'episodes'], function () use ($router) {
            $router->post('/', ['as' => 'episodes.create', 'uses' => 'EpisodesController@create']);
            $router->get('/', ['as' => 'episodes.index', 'uses' => 'EpisodesController@index']);
            $router->get('/{id}', ['as' => 'episodes.show', 'uses' => 'EpisodesController@show']);
            $router->put('/{id}', ['as' => 'episodes.update', 'uses' => 'EpisodesController@update']);
            $router->delete('/{id}', ['as' => 'episodes.destroy', 'uses' => 'EpisodesController@destroy']);
        });
    });
});
