<?php
/**
 * erpkf后台相关路由
 *
 * @author: cjx
 * @version: 1.0
 * @date: 2020/01/06
 */


Route::domain(config('app.erpkf_url'))->group(function(){
    Route::namespace('Erpkf')->group(function () {

        //登录界面 start
        Route::get('login/index','LoginController@index');
        Route::get('/start/{type}/{t}','LoginController@start'); //初始化加载极验证码
        Route::post('login/savelogin', 'LoginController@savelogin'); //请求登录
        Route::post('login/login', 'LoginController@login'); //请求登录
        //登录界面 end

        Route::middleware('erpkf.auth')->group(function(){
            //需登录验证路由
            //首页 start
            Route::get('', 'IndexController@index');
            Route::get('index', 'IndexController@index');
            //首页 end

            //控制台模块 start
            Route::get('dashboard', 'DashboardController@index');
            Route::get('dashboard/logout', 'DashboardController@logout');

            //对账管理 start
            Route::get('reconciliation/bill','Reconciliation\BillController@index');//客户对账单
            Route::post('reconciliation/list','Reconciliation\BillController@table');//客户对账单表格
            Route::get('reconciliation/export','Reconciliation\BillController@export');//客户对账单导出
            //对账管理 end

            //权限管理 start
            Route::get('auth/rule','Auth\RuleController@index');//
            Route::post('auth/list', 'Auth\RuleController@table'); //记录
            Route::post('auth/add', 'Auth\RuleController@add'); //添加
            Route::get('auth/form', 'Auth\RuleController@form'); //添加
            Route::post('auth/save', 'Auth\RuleController@save'); //添加
            //权限管理 end


            //客服人员管理 start
            Route::get('auth/kfusers/index','Auth\KfUsersController@index');//列表
            Route::post('auth/kfusers/list', 'Auth\KfUsersController@table'); //列表加载记录
            Route::get('auth/kfusers/form', 'Auth\KfUsersController@form'); //添加、更新表单
            Route::post('auth/kfusers/save', 'Auth\KfUsersController@save'); //保存
            //客服人员管理 end

            //角色管理 start
            Route::get('auth/group','Auth\GroupController@index');//列表展示
            Route::post('auth/group/list', 'Auth\GroupController@table'); //记录
            Route::get('auth/group/form', 'Auth\GroupController@form'); //添加
            Route::post('auth/group/save', 'Auth\GroupController@save'); //添加
            Route::post('auth/group/tree', 'Auth\GroupController@tree'); //添加
            //角色管理 end
        });



    });
});