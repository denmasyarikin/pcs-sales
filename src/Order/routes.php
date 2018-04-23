<?php

$router->get('/all', ['as' => 'sales.order.all', 'uses' => 'OrderController@getListAll']);
$router->get('/draft', ['as' => 'sales.order.draft', 'uses' => 'OrderController@getListDraft']);
$router->get('/created', ['as' => 'sales.order.created', 'uses' => 'OrderController@getListCreated']);
$router->get('/processing', ['as' => 'sales.order.processing', 'uses' => 'OrderController@getListProcessing']);
$router->get('/finished', ['as' => 'sales.order.finished', 'uses' => 'OrderController@getListFinished']);
$router->get('/closed', ['as' => 'sales.order.closed', 'uses' => 'OrderController@getListClosed']);
$router->get('/taken', ['as' => 'sales.order.taken', 'uses' => 'OrderController@getListTaken']);
$router->get('/canceled', ['as' => 'sales.order.canceled', 'uses' => 'OrderController@getListCanceled']);
$router->get('/counter', ['as' => 'sales.order.counter', 'uses' => 'OrderController@getCounter']);
$router->get('/counters', ['as' => 'sales.order.counters', 'uses' => 'OrderController@getCounters']);
$router->get('/overs', ['as' => 'sales.order.overs', 'uses' => 'OrderController@getOvers']);
$router->get('/debt', ['as' => 'sales.order.debt', 'uses' => 'OrderController@getDebt']);
$router->get('/cs', ['as' => 'sales.order.cs', 'uses' => 'OrderController@getCustomerServices']);
$router->get('/{id}', ['as' => 'sales.order.detail', 'uses' => 'OrderController@getDetail']);
$router->get('/{id}/customer', ['as' => 'sales.order.customer.detail', 'uses' => 'CustomerController@getDetail']);
$router->get('/{id}/item', ['as' => 'sales.order.item.list', 'uses' => 'ItemController@getList']);
$router->get('/{id}/item/{item_id}', ['as' => 'sales.order.item.detail', 'uses' => 'ItemController@getDetail']);
$router->get('/{id}/history', ['as' => 'sales.order.history.list', 'uses' => 'HistoryController@getList']);
$router->get('/{id}/attachment', ['as' => 'sales.order.attachment.list', 'uses' => 'AttachmentController@getList']);

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
    $router->put('/{id}/change_due_date', ['as' => 'sales.order.change_due_date', 'uses' => 'OrderController@changeDueDate']);
    $router->put('/{id}/change_estimated_finish_date', ['as' => 'sales.order.change_estimated_finish_date', 'uses' => 'OrderController@changeEstimatedFinishDate']);

    $router->group(['prefix' => '/{id}/history'], function ($router) {
        $router->post('/', ['as' => 'sales.order.history.create', 'uses' => 'HistoryController@createHistory']);
        $router->put('/{history_id}', ['as' => 'sales.order.history.update', 'uses' => 'HistoryController@updateHistory']);
        $router->delete('/{history_id}', ['as' => 'sales.order.history.delete', 'uses' => 'HistoryController@deleteHistory']);
    });

    $router->group(['prefix' => '/{id}/attachment'], function ($router) {
        $router->post('/', ['as' => 'sales.order.attachment.create', 'uses' => 'AttachmentController@createAttachment']);
        $router->put('/{attachment_id}', ['as' => 'sales.order.attachment.update', 'uses' => 'AttachmentController@updateAttachment']);
        $router->delete('/{attachment_id}', ['as' => 'sales.order.attachment.delete', 'uses' => 'AttachmentController@deleteAttachment']);
    });

    $router->group(['prefix' => '/{id}/item'], function ($router) {
        $router->post('/', ['as' => 'sales.order.item.create', 'uses' => 'ItemController@createOrderItem']);
        $router->put('/{item_id}', ['as' => 'sales.order.item.update', 'uses' => 'ItemController@updateOrderItem']);
        $router->delete('/{item_id}', ['as' => 'sales.order.item.delete', 'uses' => 'ItemController@deleteOrderItem']);
    });
});
