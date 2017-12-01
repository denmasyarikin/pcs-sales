<?php

$router->get('/group', 'GroupController@getList');
$router->get('/group/{id}', ['uses' => 'GroupController@getChildrenList']);
$router->group(['middleware' => 'manage:sales,product'], function ($router) {
    $router->post('group', 'GroupController@createGroup');
    $router->put('group/{id}', 'GroupController@updateGroup');
    $router->delete('group/{id}', 'GroupController@deleteGroup');
});

$router->get('/', 'ProductController@getList');
$router->get('/{id}', 'ProductController@getDetail');
$router->group(['middleware' => 'manage:sales,product'], function ($router) {
    $router->post('/', 'ProductController@createProduct');
    $router->put('/{id}', 'ProductController@updateProduct');
    $router->delete('/{id}', 'ProductController@deleteProduct');
});
