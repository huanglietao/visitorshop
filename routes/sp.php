<?php
/**
 * 工厂/供应商路由
 *
 * @author: cjx
 * @version: 1.0
 * @date: 2020/05/14
 */

Route::domain(config('app.factory_url'))->group(function(){

    Route::namespace('Factory')->group(function (){

        Route::get('login/index', 'LoginController@index');
        Route::get('/start/{type}/{t}','LoginController@start'); //初始化加载极验证码
        Route::post('login/savelogin', 'LoginController@savelogin'); //请求登录
        Route::post('login/login', 'LoginController@login'); //请求登录
        Route::post('/getRuleRemarkAndBcrumb', 'BaseController@getRuleRemarkAndBcrumb'); //面包屑，提示信息

        Route::get('print/printSheet', 'ElectronicSheet\PrintController@lists'); //批量打单
        Route::post('print/printData', 'ElectronicSheet\PrintController@printData'); //打单数据
        Route::any('print/tips','ElectronicSheet\PrintController@tips');//打单tips路由
        Route::any('print/delivery','ElectronicSheet\PrintController@delivery');//打单发货逻辑
        Route::any('print/import','ElectronicSheet\PrintController@import');//导入

        Route::any('elec_print/index','ElectronicSheet\ElectronicsPrintController@index');//打单页面
        Route::any('print/check','ElectronicSheet\ElectronicsPrintController@check');//打单页面
        Route::any('printscom/get-print-data','ElectronicSheet\ElectronicsPrintController@printData');//打单页面
        Route::any('printscom/delivery','ElectronicSheet\ElectronicsPrintController@delivery');//打单页面


        Route::get('custom_print/{type}', 'CustomPrintController@index'); //导入打单页面
        Route::post('import_print/list', 'CustomPrintController@table'); //打单数据
        Route::post('custom_print/import', 'CustomPrintController@import');//打单导入表格模板下载
        Route::any('custom_print/tips','CustomPrintController@tips');//打单tips路由
        Route::post('custom_print/printData', 'CustomPrintController@printData'); //打单数据
        Route::post('custom_print/writeBack', 'CustomPrintController@writeBack'); //自定义打单回写单号
        Route::get('custom_print/print/export', 'CustomPrintController@export'); //导出数据
        Route::get('custom_print/print/info-edit/{pri_id}/{type}', 'CustomPrintController@infoEdit'); //自定义打单调整收件人发件人信息
        Route::post('custom_print/print/info-save', 'CustomPrintController@infoSave'); //自定义打单保存收件人发件人信息
        Route::get('custom_print/print/del/{id}', 'CustomPrintController@delete'); //删除

        //登录验证
        Route::middleware('factory.auth')->group(function() {

            Route::get('', 'IndexController@index'); //首页
            Route::get('index', 'IndexController@index'); //首页
            Route::get('dashboard/logout', 'LoginController@logOut'); //退出登录

            //订单模块star
            Route::get('order/list', 'Order\ListController@index');//订单列表
            Route::post('order/list/list', 'Order\ListController@table'); //列表加载记录
            Route::any('order/list/delivery/{id}', 'Order\ListController@delivery');//发货
            Route::get('order/list/download', 'Order\ListController@downloadFile');//下载

            Route::get('order/orders', 'Order\OrdersController@index');//erp推送订单列表
            Route::post('order/orders/list', 'Order\OrdersController@table'); //列表加载记录
            Route::get('order/orders/download', 'Order\OrdersController@downloadFile');//下载
            Route::any('order/orders/status', 'Order\OrdersController@changeStatus');//更新状态
            Route::post('order/orders/count', 'Order\OrdersController@getCount');//订单数量统计

            //订单模块end
            Route::get('reportform/produce', 'Reportform\ProduceController@index');//列表
            Route::post('reportform/produce/list', 'Reportform\ProduceController@table'); //列表加载记录
            Route::get('reportform/produce/export/{data}', 'Reportform\ProduceController@export'); //导出数据

        });
    });
});

