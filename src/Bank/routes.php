<?php

$router->get('/', 'BankController@getList');
$router->group(['middleware' => 'manage:sales,bank'], function ($router) {
    $router->post('/', 'BankController@createBank');
    $router->put('/{id}', 'BankController@updateBank');
    $router->delete('/{id}', 'BankController@deleteBank');
});
