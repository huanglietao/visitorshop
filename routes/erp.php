<?php
/**
 * erp后台相关路由
 *
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2019/7/30
 */


Route::domain(config('app.erp_url'))->group(function(){
    Route::namespace('Erp')->group(function () {
        /***无需登录验证路由 start****/


        //登录界面 start
        Route::get('login/index','LoginController@index');
        Route::any('login/validatelogin','LoginController@validatelogin');
        Route::post('login/savelogin', 'LoginController@savelogin'); //请求登录
        Route::post('login/sendsms', 'LoginController@sendsms'); //请求发送手机验证码
        //登录界面 end


        //极验验证码demo测试
        Route::get('gt_demo','GeetTestController@index');
        Route::get('/start/{type}/{t}','GeetTestController@start');

        Route::any('capital/alipaynotify','Finance\RechargeController@alipaynotify');//充值回调
        Route::any('capital/alipayreturn','Finance\RechargeController@alipayreturn');//充值成功返回页面
        Route::any('capital/retry/{id}','Finance\RechargeController@retry');//重推页面



        Route::any('print/index','Printscom\PrintsController@index');//打单页面
        Route::any('print/check','Printscom\PrintsController@check');//打单页面
        Route::any('printscom/get-print-data','Printscom\PrintsController@printData');//打单页面
        Route::any('printscom/delivery','Printscom\PrintsController@delivery');//打单页面


        Route::post('cmb/notify','Pay\CmbController@notify');//招行异步通知
        Route::any('recharge/checkreturn','Finance\RechargeController@checkreturn');//招行支付页轮询

        Route::any('print/print_deliver','Printscom\PrintsDeliverController@index');//订单打单发货
        Route::any('print/printdata','Printscom\PrintsDeliverController@printData');//打单数据
        Route::any('print/delivery','Printscom\PrintsDeliverController@delivery');//打单发货逻辑
        Route::any('print/other','Printscom\PrintsOtherController@index');//手动回写物流单
        Route::any('print/write_back','Printscom\PrintsOtherController@writeBack');//手动回写物流单接口
        Route::any('print/other_delivery','Printscom\PrintsDeliverController@otherDelivery');//顺丰和自提发货
        Route::any('print/tips','Printscom\PrintsDeliverController@tips');//打单tips路由







        Route::any('tradeprint/index','Printscom\TradePrintsController@index');//贸易订单打单页面
        Route::any('tradeprint/check','Printscom\TradePrintsController@check');//贸易订单打单页面
        Route::any('tradeprint/get-print-data','Printscom\TradePrintsController@printData');//贸易订单打单页面
        Route::any('tradeprint/delivery','Printscom\TradePrintsController@delivery');//贸易订单打单页面


        /***无需登录验证路由 end****/

        Route::middleware('erp.auth')->group(function(){
            //需登录验证路由
            Route::get('', 'IndexController@index');
            Route::get('index', 'IndexController@index');

            //demo 模块 start
            Route::get('demo', 'DemoController@index'); //列表
            Route::post('demo/list', 'DemoController@table'); //记录
            Route::post('demo/add', 'DemoController@add'); //添加
            Route::get('demo/form', 'DemoController@form'); //添加
            Route::post('demo/save', 'DemoController@save'); //添加

            //demo 模块 end

            //dashboard 模块 start
            Route::get('dashboard', 'DashboardController@index');
            Route::get('dashboard/logout', 'DashboardController@logout');

            //dashboard 模块 end

            //资金管理 start
            Route::get('finance/record','Finance\RecordController@index');//充值记录
            Route::any('finance/record/list', 'Finance\RecordController@table');//充值记录列表
            Route::any('finance/record/form', 'Finance\RecordController@form');//充值记录列表详情
            Route::get('finance/recharge','Finance\RechargeController@index');//账户充值
            Route::get('finance/pay','Finance\RechargeController@pay');//账户充值


            //资金管理 end

            //对账管理 start
            Route::get('reconciliation/bill','Reconciliation\BillController@index');//客户对账单
            Route::post('reconciliation/list','Reconciliation\BillController@table');//客户对账单表格
            Route::get('reconciliation/export','Reconciliation\BillController@export');//客户对账单导出
            //对账管理 end

            Route::get('test/record','test\recordController@index');//测试
            Route::post('test/record/list', 'test\recordController@table'); //测试

            //订单管理 start
            Route::get('orders','Order\OrderController@index');//客户对账单订单
            Route::post('orders/list','Order\OrderController@table');//客户对账单订单表格
            Route::get('orders/export','Order\OrderController@export');//客户对账单订单导出
            //订单管理 end

            //导入订单管理 start
            Route::get('/import','Order\ImportController@index');//导入订单页面
            Route::post('/import/list','Order\ImportController@table');//导入订单列表
            Route::get('/import/form', 'Order\ImportController@form'); //添加、更新表单
            Route::post('/import/save', 'Order\ImportController@save'); //保存
            Route::post('/import/ExcelImport','Order\ImportController@ExcelImport');//导入订单
            Route::post('/import/tips', 'Order\ImportController@tips');//导入订单提示
            //导入订单管理 end

        });

    });
});
