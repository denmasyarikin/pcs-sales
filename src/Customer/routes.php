<?php

$router->get('/', ['as' => 'sales.customer.list', 'uses' => 'CustomerController@getList']);
$router->get('/{id}', ['as' => 'sales.customer.detail', 'uses' => 'CustomerController@getDetail']);
$router->group(['middleware' => 'manage:sales,customer,write'], function ($router) {
    $router->post('/', ['as' => 'sales.customer.create', 'uses' => 'CustomerController@createCustomer']);
    $router->put('/{id}', ['as' => 'sales.customer.update', 'uses' => 'CustomerController@updateCustomer']);
    $router->delete('/{id}', ['as' => 'sales.customer.delete', 'uses' => 'CustomerController@deleteCustomer']);
});
