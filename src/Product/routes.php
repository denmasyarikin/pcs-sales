<?php

$router->get('/'.(RE ? 'category' : '0001'), ['as' => 'sales.product.category.list', 'uses' => 'ProductCategoryController@getList']);
$router->get('/'.(RE ? 'category' : '0001').'/{id}', ['as' => 'sales.product.category.detail', 'uses' => 'ProductCategoryController@getDetail']);

$router->get('/', ['as' => 'sales.product.list', 'uses' => 'ProductController@getList']);
$router->get('/{id}', ['as' => 'sales.product.detail', 'uses' => 'ProductController@getDetail']);

$router->get('/{id}/'.(RE ? 'media' : '0002'), ['as' => 'sales.product.media.list', 'uses' => 'ProductMediaController@getList']);

$router->group(['prefix' => '/{id}/'.(RE ? 'process' : '0003')], function($router) {
    $router->get('/', ['as' => 'sales.product.process.list', 'uses' => 'ProcessController@getList']);
    $router->get('/{process_id}', ['as' => 'sales.product.process.detail', 'uses' => 'ProcessController@getDetail']);
    $router->get('/{process_id}/'.(RE ? 'opration' : '0005'), ['as' => 'sales.product.opration.list', 'uses' => 'OprationController@getList']);
    $router->get('/{process_id}/'.(RE ? 'opration' : '0005').'/{opration_id}', ['as' => 'sales.product.opration.detail', 'uses' => 'OprationController@getDetail']);
});

$router->get('/{id}/'.(RE ? 'configuration' : '0004'), ['as' => 'sales.product.configuration.list', 'uses' => 'ConfigurationController@getList']);

$router->group(['middleware' => 'manage:sales,product,write'], function ($router) {
    $router->post((RE ? 'category' : '0001'), ['as' => 'sales.product.category.create', 'uses' => 'ProductCategoryController@createCategory']);
    $router->put((RE ? 'category' : '0001').'/{id}', ['as' => 'sales.product.category.update', 'uses' => 'ProductCategoryController@updateCategory']);
    $router->delete((RE ? 'category' : '0001').'/{id}', ['as' => 'sales.product.category.delete', 'uses' => 'ProductCategoryController@deleteCategory']);

    $router->post('/', ['as' => 'sales.product.create', 'uses' => 'ProductController@createProduct']);
    $router->put('/{id}', ['as' => 'sales.product.update', 'uses' => 'ProductController@updateProduct']);
    $router->delete('/{id}', ['as' => 'sales.product.delete', 'uses' => 'ProductController@deleteProduct']);

    $router->group(['prefix' => '/{id}/'.(RE ? 'media' : '0002')], function ($router) {
        $router->post('/', ['as' => 'sales.product.media.create', 'uses' => 'ProductMediaController@createMedia']);
        $router->put('/{media_id}', ['as' => 'sales.product.media.update', 'uses' => 'ProductMediaController@updateMedia']);
        $router->put('/{media_id}/'.(RE ? 'primary' : '0006'), ['as' => 'sales.product.media.update_primary', 'uses' => 'ProductMediaController@updateMediaPrimary']);
        $router->delete('/{media_id}', ['as' => 'sales.product.media.delete', 'uses' => 'ProductMediaController@deleteMedia']);
    });

    $router->group(['prefix' => '/{id}/'.(RE ? 'process' : '0003')], function ($router) {
        $router->post('/', ['as' => 'sales.product.process.create', 'uses' => 'ProcessController@createProcess']);
        $router->put('/{process_id}', ['as' => 'sales.product.process.update', 'uses' => 'ProcessController@updateProcess']);
        $router->delete('/{process_id}', ['as' => 'sales.product.process.delete', 'uses' => 'ProcessController@deleteProcess']);
        
        $router->group(['prefix' => '/{process_id}/'.(RE ? 'opration' : '0005')], function ($router) {
            $router->post('/', ['as' => 'sales.product.opration.create', 'uses' => 'OprationController@createOpration']);
            $router->put('/{opration_id}', ['as' => 'sales.product.opration.update', 'uses' => 'OprationController@updateOpration']);
            $router->delete('/{opration_id}', ['as' => 'sales.product.opration.delete', 'uses' => 'OprationController@deleteOpration']);
        });
    });

    $router->group(['prefix' => '/{id}/'.(RE ? 'configuration' : '0004')], function ($router) {
        $router->post('/', ['as' => 'sales.product.configuration.create', 'uses' => 'ConfigurationController@createConfiguration']);
        $router->put('/{configuration_id}', ['as' => 'sales.product.configuration.update', 'uses' => 'ConfigurationController@updateConfiguration']);
        $router->delete('/{configuration_id}', ['as' => 'sales.product.configuration.delete', 'uses' => 'ConfigurationController@deleteConfiguration']);
    });
});
