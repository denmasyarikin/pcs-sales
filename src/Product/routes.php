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
$router->get('/{id}/process', 'ProcessController@getList');
$router->group(['middleware' => 'manage:sales,product'], function ($router) {
    $router->post('/', 'ProductController@createProduct');
    $router->put('/{id}', 'ProductController@updateProduct');
    $router->delete('/{id}', 'ProductController@deleteProduct');
    $router->group(['prefix' => '/{id}/process'], function ($router) {
        $router->post('/', 'ProcessController@createProcess');
        $router->put('/{process_id}', 'ProcessController@updateProcess');
        $router->delete('/{process_id}', 'ProcessController@deleteProcess');
    });
});
