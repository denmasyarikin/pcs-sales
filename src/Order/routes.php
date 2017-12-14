<?php

$router->get('/draft', 'OrderController@getListDraft');
$router->get('/created', 'OrderController@getListCreated');
$router->get('/processing', 'OrderController@getListProcessing');
$router->get('/finished', 'OrderController@getListFinished');
$router->get('/closed', 'OrderController@getListArchived');
$router->get('/canceled', 'OrderController@getListCanceled');
$router->get('/{id}', 'OrderController@getDetail');
$router->get('/{id}/customer', 'CustomerController@getDetail');
$router->get('/{id}/item', 'ItemController@getList');
$router->get('/{id}/item/{item_id}', 'ItemController@getDetail');

$router->group(['middleware' => 'manage:sales,order'], function ($router) {
    $router->post('/', 'OrderController@createOrder');
    $router->put('/{id}', 'OrderController@updateOrder');
    $router->put('/{id}/status', 'OrderController@updateStatusOrder');
    $router->put('/{id}/cancel', 'OrderController@cancelOrder');
    $router->delete('/{id}', 'OrderController@deleteOrder');
    $router->post('/{id}/customer', 'CustomerController@updateCustomer');
    $router->post('/{id}/discount', 'AdjustmentController@applyDiscount');
    $router->post('/{id}/tax', 'AdjustmentController@applyTax');
    $router->post('/{id}/voucher', 'AdjustmentController@applyVoucher');

    $router->group(['prefix' => '/{id}/item'], function ($router) {
        $router->post('/', 'ItemController@createOrderItem');
        $router->put('/{item_id}', 'ItemController@updateOrderItem');
        $router->delete('/{item_id}', 'ItemController@deleteOrderItem');
    });
});
