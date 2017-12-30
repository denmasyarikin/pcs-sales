<?php

$router->get('/', ['as' => 'sales.bank.list', 'uses' => 'BankController@getList']);
$router->get('/{id}', ['as' => 'sales.bank.detail', 'uses' => 'BankController@getDetail']);
$router->group(['middleware' => 'manage:sales,bank'], function ($router) {
    $router->post('/', ['as' => 'sales.bank.create', 'uses' => 'BankController@createBank']);
    $router->put('/{id}', ['as' => 'sales.bank.update', 'uses' => 'BankController@updateBank']);
    $router->delete('/{id}', ['as' => 'sales.bank.delete', 'uses' => 'BankController@deleteBank']);
});
