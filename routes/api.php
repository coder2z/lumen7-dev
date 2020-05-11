<?php
/**
 * Created by PhpStorm.
 * User: myxy9
 * Date: 2020/5/11
 * Time: 9:42
 */

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
    return json_success('网站搭建成功');
});

$router->post('login', 'Api\AuthController@login');
$router->group(['namespace' => 'Api', 'middleware' => 'auth'], function () use ($router) {
    $router->post('logout', 'AuthController@logout');
    $router->get('info', 'AuthController@userInfo');
    $router->post('refresh', 'AuthController@refreshToken');
});