<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');

    $router->get('/testResult', 'HomeController@testResult')->name('testResult');

    $router->get('/newUserApiDataStore', 'HomeController@newUserApiDataStore')->name('newUserApiDataStore');

    $router->get('/userPaymentApiDataStore', 'HomeController@userPaymentApiDataStore')->name('userPaymentApiDataStore');

    $router->get('/form/testForm', 'HomeController@testForm')->name('testForm');
});
