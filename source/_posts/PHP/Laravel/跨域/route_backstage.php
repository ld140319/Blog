<?php
Route::namespace('Admin')->prefix("admin")->group(function () {
    Route::get('/test', function (){
        return "hello, admin dish";
    });

    #login
    Route::get('/', 'IndexController@index');
    Route::post('/login', 'IndexController@Login');
    Route::get('/logout', 'IndexController@Logout');

    #file upload
    Route::post('/files/upload', 'FileController@Upload');

    //configuration
    Route::post('/configs', 'ConfigurationController@Create');
    Route::get('/configs/{configId}', 'ConfigurationController@Detail')->where(['configId' => '[0-9]+']);
    Route::put('/configs/{configId}', 'ConfigurationController@Modify')->where(['configId' => '[0-9]+']);  //Content-Type:application/x-www-form-urlencoded
    Route::delete('/configs/{configId}', 'ConfigurationController@Delete')->where(['configId' => '[0-9]+']);
    Route::get('/configs/list', 'ConfigurationController@getConfigList');

    //dish
    Route::post('/dishs', 'DishController@Create');
    Route::get('/dishs/{dishId}', 'DishController@Detail')->where(['dishId' => '[0-9]+']);
    Route::put('/dishs/{dishId}', 'DishController@Modify')->where(['dishId' => '[0-9]+']);
    Route::delete('/dishs/{dishId}', 'DishController@Delete')->where(['dishId' => '[0-9]+']);
    Route::get('/dishs/list', 'DishController@getDishList');

    //dish variety
    Route::post('/varietys', 'VarietyController@Create');
    Route::get('/varietys/{varietyId}', 'VarietyController@Detail')->where(['varietyId' => '[0-9]+']);
    Route::put('/varietys/{varietyId}', 'VarietyController@Modify')->where(['varietyId' => '[0-9]+']);
    Route::delete('/varietys/{varietyId}', 'VarietyController@Delete')->where(['varietyId' => '[0-9]+']);
    Route::get('/varietys/list', 'VarietyController@getVarietyList');

    //store
    Route::post('/stores', 'StoreController@Create');
    Route::get('/stores/{storeId}', 'StoreController@Detail')->where(['storeId' => '[0-9]+']);
    Route::put('/stores/{storeId}', 'StoreController@Modify')->where(['storeId' => '[0-9]+']);
    Route::delete('/stores/{storeId}', 'StoreController@Delete')->where(['storeId' => '[0-9]+']);
    Route::get('/stores/list', 'StoreController@getStoreList');

    //package
    Route::post('/packages', 'PackageController@Create');
    Route::get('/packages/{packageId}', 'PackageController@Detail')->where(['packageId' => '[0-9]+']);
    Route::put('/packages/{packageId}', 'PackageController@Modify')->where(['packageId' => '[0-9]+']);
    Route::delete('/packages/{packageId}', 'PackageController@Delete')->where(['packageId' => '[0-9]+']);
    Route::get('/packages/list', 'PackageController@getPackageList');

    #package discount
    Route::post('/packages/discount', 'PackageDiscountController@Create');
    Route::get('/packages/discount/{packageDiscountId}', 'PackageDiscountController@Detail')->where(['packageDiscountId' => '[0-9]+']);
    Route::put('/packages/discount/{packageDiscountId}', 'PackageDiscountController@Modify')->where(['packageDiscountId' => '[0-9]+']);
    Route::delete('/packages/discount/{packageDiscountId}', 'PackageDiscountController@Delete')->where(['packageDiscountId' => '[0-9]+']);
    Route::get('/packages/discount/list', 'PackageDiscountController@getPackageDiscountList');

    //administrators
    Route::post('/administrators', 'AdministratorController@Create');
    Route::get('/administrators/{administratorId}', 'AdministratorController@Detail')->where(['administratorId' => '[0-9]+']);
    Route::put('/administrators/{administratorId}', 'AdministratorController@Modify')->where(['administratorId' => '[0-9]+']);
    Route::delete('/administrators/{administratorId}', 'AdministratorController@Delete')->where(['administratorId' => '[0-9]+']);
    Route::get('/administrators/list', 'AdministratorController@getAdministratorList');

    //user
    Route::put('/users/{userId}', 'UserController@Modify')->where(['userId' => '[0-9]+']);
    Route::get('/users/{userId}', 'UserController@Detail')->where(['userId' => '[0-9]+']);
    Route::get('/users/list', 'UserController@getUserList');

    //orders
    Route::get('/orders/{orderId}', 'OrderController@Detail')->where(['administratorId' => '[0-9]+']);
    Route::put('/orders/{orderId}', 'OrderController@Modify')->where(['administratorId' => '[0-9]+']);
    Route::get('/orders/list', 'OrderController@orderList');
});