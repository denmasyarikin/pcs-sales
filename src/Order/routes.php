<?php

$router->get('/created', 'OrderController@getListCreated');
$router->get('/processing', 'OrderController@getListProcessing');
$router->get('/finished', 'OrderController@getListFinished');
$router->get('/archived', 'OrderController@getListArchived');
$router->get('/canceled', 'OrderController@getListCanceled');
$router->get('/{id}', 'OrderController@getDetail');

$router->group(['middleware' => 'manage:sales,order'], function ($router) {
    $router->post('/', 'OrderController@createOrder');
    $router->put('/{id}', 'OrderController@updateOrder');
    $router->delete('/{id}', 'OrderController@deleteOrder');
});
