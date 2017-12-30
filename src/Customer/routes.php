<?php

$router->get('/', ['as' => 'sales.customer.list', 'CustomerController@getList']);
$router->get('/{id}', ['as' => 'sales.customer.detail', 'CustomerController@getDetail']);
$router->group(['middleware' => 'manage:sales,customer'], function ($router) {
    $router->post('/', ['as' => 'sales.customer.create', 'CustomerController@createCustomer']);
    $router->put('/{id}', ['as' => 'sales.customer.update', 'CustomerController@updateCustomer']);
    $router->delete('/{id}', ['as' => 'sales.customer.delete', 'CustomerController@deleteCustomer']);
});
