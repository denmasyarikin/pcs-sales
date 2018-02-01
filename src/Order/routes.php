<?php

$router->get('/draft', ['as' => 'sales.order.draft', 'uses' => 'OrderController@getListDraft']);
$router->get('/created', ['as' => 'sales.order.created', 'uses' => 'OrderController@getListCreated']);
$router->get('/processing', ['as' => 'sales.order.processing', 'uses' => 'OrderController@getListProcessing']);
$router->get('/finished', ['as' => 'sales.order.finished', 'uses' => 'OrderController@getListFinished']);
$router->get('/closed', ['as' => 'sales.order.closed', 'uses' => 'OrderController@getListArchived']);
$router->get('/canceled', ['as' => 'sales.order.canceled', 'uses' => 'OrderController@getListCanceled']);
$router->get('/{id}', ['as' => 'sales.order.detail', 'uses' => 'OrderController@getDetail']);
$router->get('/{id}/customer', ['as' => 'sales.order.customer.detail', 'uses' => 'CustomerController@getDetail']);
$router->get('/{id}/item', ['as' => 'sales.order.item.list', 'uses' => 'ItemController@getList']);
$router->get('/{id}/item/{item_id}', ['as' => 'sales.order.item.detail', 'uses' => 'ItemController@getDetail']);
$router->get('/{id}/history', ['as' => 'sales.order.history.list', 'uses' => 'HistoryController@getList']);

$router->group(['middleware' => 'manage:sales,order,write'], function ($router) {
    $router->post('/', ['as' => 'sales.order.create', 'uses' => 'OrderController@createOrder']);
    $router->put('/{id}', ['as' => 'sales.order.update', 'uses' => 'OrderController@updateOrder']);
    $router->put('/{id}/status', ['as' => 'sales.order.update_status', 'uses' => 'OrderController@updateStatusOrder']);
    $router->put('/{id}/cancel', ['as' => 'sales.order.cancel', 'uses' => 'OrderController@cancelOrder']);
    $router->delete('/{id}', ['as' => 'sales.order.delete', 'uses' => 'OrderController@deleteOrder']);
    $router->post('/{id}/customer', ['as' => 'sales.order.customer.create', 'uses' => 'CustomerController@updateCustomer']);
    $router->post('/{id}/discount', ['as' => 'sales.order.discount', 'uses' => 'AdjustmentController@applyDiscount']);
    $router->post('/{id}/tax', ['as' => 'sales.order.tax', 'uses' => 'AdjustmentController@applyTax']);
    $router->post('/{id}/voucher', ['as' => 'sales.order.voucher', 'uses' => 'AdjustmentController@applyVoucher']);
    $router->post('/{id}/history', ['as' => 'sales.order.history.create', 'uses' => 'HistoryController@createHistory']);
    $router->put('/{id}/history/{history_id}', ['as' => 'sales.order.history.update', 'uses' => 'HistoryController@updateHistory']);
    $router->delete('/{id}/history/{history_id}', ['as' => 'sales.order.history.delete', 'uses' => 'HistoryController@deleteHistory']);

    $router->group(['prefix' => '/{id}/item'], function ($router) {
        $router->post('/', ['as' => 'sales.order.item.create', 'uses' => 'ItemController@createOrderItem']);
        $router->put('/{item_id}', ['as' => 'sales.order.item.update', 'uses' => 'ItemController@updateOrderItem']);
        $router->delete('/{item_id}', ['as' => 'sales.order.item.delete', 'uses' => 'ItemController@deleteOrderItem']);
    });
});
