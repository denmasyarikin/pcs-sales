<?php

$router->get('/', 'ProductController@getList');
$router->get('/{id}', 'ProductController@getDetail');
$router->group(['middleware' => 'manage:sales,product'], function ($router) {
    $router->post('/', 'ProductController@createProduct');
    $router->put('/{id}', 'ProductController@updateProduct');
    $router->delete('/{id}', 'ProductController@deleteProduct');
});
