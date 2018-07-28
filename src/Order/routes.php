<?php

$router->get('/'.(RE ? 'all' : '0001'), ['as' => 'sales.order.all', 'uses' => 'OrderController@getListAll']);
$router->get('/'.(RE ? 'draft' : '0002'), ['as' => 'sales.order.draft', 'uses' => 'OrderController@getListDraft']);
$router->get('/'.(RE ? 'created' : '0003'), ['as' => 'sales.order.created', 'uses' => 'OrderController@getListCreated']);
$router->get('/'.(RE ? 'processing' : '0004'), ['as' => 'sales.order.processing', 'uses' => 'OrderController@getListProcessing']);
$router->get('/'.(RE ? 'finished' : '0005'), ['as' => 'sales.order.finished', 'uses' => 'OrderController@getListFinished']);
$router->get('/'.(RE ? 'closed' : '0006'), ['as' => 'sales.order.closed', 'uses' => 'OrderController@getListClosed']);
$router->get('/'.(RE ? 'taken' : '0007'), ['as' => 'sales.order.taken', 'uses' => 'OrderController@getListTaken']);
$router->get('/'.(RE ? 'canceled' : '0008'), ['as' => 'sales.order.canceled', 'uses' => 'OrderController@getListCanceled']);
$router->get('/'.(RE ? 'counter' : '0009'), ['as' => 'sales.order.counter', 'uses' => 'OrderController@getCounter']);
$router->get('/'.(RE ? 'counters' : '0010'), ['as' => 'sales.order.counters', 'uses' => 'OrderController@getCounters']);
$router->get('/'.(RE ? 'overs' : '0011'), ['as' => 'sales.order.overs', 'uses' => 'OrderController@getOvers']);
$router->get('/'.(RE ? 'debt' : '0012'), ['as' => 'sales.order.debt', 'uses' => 'OrderController@getDebt']);
$router->get('/'.(RE ? 'cs' : '0013'), ['as' => 'sales.order.cs', 'uses' => 'OrderController@getCustomerServices']);
$router->get('/{id}', ['as' => 'sales.order.detail', 'uses' => 'OrderController@getDetail']);
$router->get('/{id}/'.(RE ? 'customer' : '0014'), ['as' => 'sales.order.customer.detail', 'uses' => 'CustomerController@getDetail']);
$router->get('/{id}/'.(RE ? 'item' : '0015'), ['as' => 'sales.order.item.list', 'uses' => 'ItemController@getList']);
$router->get('/{id}/'.(RE ? 'item' : '0015').'/{item_id}', ['as' => 'sales.order.item.detail', 'uses' => 'ItemController@getDetail']);
$router->get('/{id}/'.(RE ? 'history' : '0016'), ['as' => 'sales.order.history.list', 'uses' => 'HistoryController@getList']);
$router->get('/{id}/'.(RE ? 'attachment' : '0017'), ['as' => 'sales.order.attachment.list', 'uses' => 'AttachmentController@getList']);

$router->group(['middleware' => 'manage:sales,order,write'], function ($router) {
    $router->post('/', ['as' => 'sales.order.create', 'uses' => 'OrderController@createOrder']);
    $router->put('/{id}', ['as' => 'sales.order.update', 'uses' => 'OrderController@updateOrder']);
    $router->put('/{id}/'.(RE ? 'status' : '0018'), ['as' => 'sales.order.update_status', 'uses' => 'OrderController@updateStatusOrder']);
    $router->put('/{id}/'.(RE ? 'cancel' : '0019'), ['as' => 'sales.order.cancel', 'uses' => 'OrderController@cancelOrder']);
    $router->delete('/{id}', ['as' => 'sales.order.delete', 'uses' => 'OrderController@deleteOrder']);
    $router->post('/{id}/'.(RE ? 'customer' : '0014'), ['as' => 'sales.order.customer.create', 'uses' => 'CustomerController@updateCustomer']);
    $router->post('/{id}/'.(RE ? 'discount' : '0020'), ['as' => 'sales.order.discount', 'uses' => 'AdjustmentController@applyDiscount']);
    $router->post('/{id}/'.(RE ? 'tax' : '0021'), ['as' => 'sales.order.tax', 'uses' => 'AdjustmentController@applyTax']);
    $router->post('/{id}/'.(RE ? 'voucher' : '0022'), ['as' => 'sales.order.voucher', 'uses' => 'AdjustmentController@applyVoucher']);
    $router->put('/{id}/'.(RE ? 'change_due_date' : '0023'), ['as' => 'sales.order.change_due_date', 'uses' => 'OrderController@changeDueDate']);
    $router->put('/{id}/'.(RE ? 'change_estimated_finish_date' : '0024'), ['as' => 'sales.order.change_estimated_finish_date', 'uses' => 'OrderController@changeEstimatedFinishDate']);

    $router->group(['prefix' => '/{id}/'.(RE ? 'history' : '0016')], function ($router) {
        $router->post('/', ['as' => 'sales.order.history.create', 'uses' => 'HistoryController@createHistory']);
        $router->put('/{history_id}', ['as' => 'sales.order.history.update', 'uses' => 'HistoryController@updateHistory']);
        $router->delete('/{history_id}', ['as' => 'sales.order.history.delete', 'uses' => 'HistoryController@deleteHistory']);
    });

    $router->group(['prefix' => '/{id}/'.(RE ? 'attachment' : '0017')], function ($router) {
        $router->post('/', ['as' => 'sales.order.attachment.create', 'uses' => 'AttachmentController@createAttachment']);
        $router->put('/{attachment_id}', ['as' => 'sales.order.attachment.update', 'uses' => 'AttachmentController@updateAttachment']);
        $router->delete('/{attachment_id}', ['as' => 'sales.order.attachment.delete', 'uses' => 'AttachmentController@deleteAttachment']);
    });

    $router->group(['prefix' => '/{id}/'.(RE ? 'item' : '0015')], function ($router) {
        $router->post('/', ['as' => 'sales.order.item.create', 'uses' => 'ItemController@createOrderItem']);
        $router->put('/{item_id}', ['as' => 'sales.order.item.update', 'uses' => 'ItemController@updateOrderItem']);
        $router->delete('/{item_id}', ['as' => 'sales.order.item.delete', 'uses' => 'ItemController@deleteOrderItem']);
    });
});

$router->get('/{id}/invoice/{type}', ['as' => 'sales.order.invoice', 'uses' => 'InvoiceController@showInvoice']);
$router->get('/{id}/receipt/{type}', ['as' => 'sales.order.receipt', 'uses' => 'InvoiceController@showReceipt']);
