<?php

$router->get('/', 'BankController@getList');
$router->get('/{id}', 'BankController@getDetail');
$router->group(['middleware' => 'manage:sales,bank'], function ($router) {
    $router->post('/', 'BankController@createBank');
    $router->put('/{id}', 'BankController@updateBank');
    $router->delete('/{id}', 'BankController@deleteBank');
});
