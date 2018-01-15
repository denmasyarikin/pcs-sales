<?php

$router->get('/', ['as' => 'sales.payment.list', 'uses' => 'PaymentController@getList']);
$router->get('/{id}', ['as' => 'sales.payment.detail', 'uses' => 'PaymentController@getDetail']);
$router->group(['middleware' => 'manage:sales,payment,write'], function ($router) {
    $router->post('/', ['as' => 'sales.payment.create', 'uses' => 'PaymentController@createPayment']);
    $router->put('/{id}', ['as' => 'sales.payment.update', 'uses' => 'PaymentController@updatePayment']);
    $router->delete('/{id}', ['as' => 'sales.payment.delete', 'uses' => 'PaymentController@deletePayment']);
});
