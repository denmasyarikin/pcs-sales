<?php

$router->get('/', 'PaymentController@getList');
$router->get('/{id}', 'PaymentController@getDetail');
$router->group(['middleware' => 'manage:sales,bank'], function ($router) {
    $router->post('/', 'PaymentController@createPayment');
    $router->put('/{id}', 'PaymentController@updatePayment');
    $router->delete('/{id}', 'PaymentController@deletePayment');
});
