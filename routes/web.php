<?php

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

$router->group(['prefix' => 'users'], function() use ($router){
    $router->get('/',['uses' => 'UserController@dataUsers']);

    $router->post('/',['uses' => 'UserController@addUser']);

    $router->get('/{user_id}',['uses' => 'UserController@profileUser']);

    $router->post('/{user_id}',['uses' => 'UserController@updateProfileUser']);

    $router->put('/to-male/{user_id}',['uses' => 'UserController@changeGenderToMale']);

    $router->put('/to-female/{user_id}',['uses' => 'UserController@changeGenderToFemale']);

    $router->delete('/delete/{user_id}',['uses' => 'UserController@deleteUser']);
});

$router->group(['prefix' => 'male-content','middleware'=>'maleOnly'], function() use ($router) {
    $router->get('/{user_id}', function(){
        return "Hello Man!";
});
});

$router->group(['prefix' => 'female-content','middleware'=>'femaleOnly'], function() use ($router){
    $router->get('/{user_id}', function()
    {
        return "Hello Woman!";
    });
});