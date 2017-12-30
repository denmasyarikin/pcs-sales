<?php

$router->get('/group', ['as' => 'sales.product.group.list', 'uses' => 'GroupController@getList']);
$router->get('/group/{id}', ['as' => 'sales.product.group.detail', 'uses' => 'GroupController@getChildrenList']);
$router->group(['middleware' => 'manage:sales,product'], function ($router) {
    $router->post('group', ['as' => 'sales.product.group.create', 'uses' => 'GroupController@createGroup']);
    $router->put('group/{id}', ['as' => 'sales.product.group.update', 'uses' => 'GroupController@updateGroup']);
    $router->delete('group/{id}', ['as' => 'sales.product.group.delete', 'uses' => 'GroupController@deleteGroup']);
});

$router->get('/', ['as' => 'sales.product.list', 'uses' => 'ProductController@getList']);
$router->get('/{id}', ['as' => 'sales.product.detail', 'uses' => 'ProductController@getDetail']);
$router->get('/{id}/process', ['as' => 'sales.product.process.list', 'uses' => 'ProcessController@getList']);
$router->get('/{id}/media', ['as' => 'sales.product.media.list', 'uses' => 'MediaController@getList']);
$router->group(['middleware' => 'manage:sales,product'], function ($router) {
    $router->post('/', ['as' => 'sales.product.create', 'uses' => 'ProductController@createProduct']);
    $router->put('/{id}', ['as' => 'sales.product.update', 'uses' => 'ProductController@updateProduct']);
    $router->delete('/{id}', ['as' => 'sales.product.delete', 'uses' => 'ProductController@deleteProduct']);
    $router->group(['prefix' => '/{id}/process'], function ($router) {
        $router->post('/', ['as' => 'sales.product.process.create', 'uses' => 'ProcessController@createProcess']);
        $router->put('/{process_id}', ['as' => 'sales.product.process.update', 'uses' => 'ProcessController@updateProcess']);
        $router->delete('/{process_id}', ['as' => 'sales.product.process.delete', 'uses' => 'ProcessController@deleteProcess']);
    });
    $router->group(['prefix' => '/{id}/media'], function ($router) {
        $router->post('/', ['as' => 'sales.product.media.create', 'uses' => 'MediaController@createMedia']);
        $router->put('/{media_id}', ['as' => 'sales.product.media.update', 'uses' => 'MediaController@updateMedia']);
        $router->delete('/{media_id}', ['as' => 'sales.product.media.delete', 'uses' => 'MediaController@deleteMedia']);
    });
});
