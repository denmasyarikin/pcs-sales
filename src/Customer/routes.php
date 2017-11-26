<?php

$router->get('/', 'CustomerController@getList');
$router->group(['middleware' => 'manage:sales,customer'], function ($router) {
    $router->post('/', 'CustomerController@createCustomer');
    $router->put('/{id}', 'CustomerController@updateCustomer');
    $router->delete('/{id}', 'CustomerController@deleteCustomer');
});
