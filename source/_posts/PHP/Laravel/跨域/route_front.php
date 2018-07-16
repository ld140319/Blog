<?php
Route::namespace('Index')->prefix("index")->group(function () {
    Route::get('/test', function (){
        return "hello, index dish";
    });
    Route::get('/test1', 'UserController@test');

    #login
    Route::get('/', 'IndexController@index');
    Route::post('/taken/get', ' IndexController@GetToken');
    Route::post('/login', 'IndexController@Login');
    Route::get('/logout', 'IndexController@Logout');

    //user
    Route::put('/users/{userId}', 'UserController@Modify')->where(['userId' => '[0-9]+']);
    Route::get('/users/{userId}', 'UserController@Detail')->where(['userId' => '[0-9]+']);

    //dish variety
    Route::get('/varietys/{varietyId}', 'VarietyController@Detail')->where(['varietyId' => '[0-9]+']);
    Route::get('/varietys/list', 'VarietyController@getVarietyList');

    //package
    Route::get('/packages/{packageId}', 'PackageController@Detail')->where(['packageId' => '[0-9]+']);
    Route::get('/packages/list', 'PackageController@getPackageList');

    //store
    Route::get('/stores/{storeId}', 'StoreController@Detail')->where(['storeId' => '[0-9]+']);
    Route::get('/stores/list', 'StoreController@getStoreList');

    #pay
    Route::get('/orders/prePay', 'WxPayController@PrePay');
    Route::post('/orders/pay', 'WxPayController@Pay');
    Route::any('/orders/pay_success_notify', 'WxPayController@PaySuccessNotify');
    Route::any('/orders/cancel_success_notify', 'WxPayController@CancelSuccessNotify');

    //orders
    Route::post('/orders/create', 'OrderController@Create');
    Route::get('/orders/{orderId}', 'OrderController@Detail')->where(['orderId' => '[0-9]+']);
    Route::put('/orders/{orderId}', 'OrderController@Modify')->where(['orderId' => '[0-9]+']);
    Route::get('/orders/list', 'OrderController@orderList');

});