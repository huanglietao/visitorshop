<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});
Route::get('/test/goods', function () {
    return view('welcome');
});*/

Route::post('/tips_success', 'BaseController@tipsSuccess');
Route::post('/tips_warn', 'BaseController@tipsWarn');


Route::any('ajax', 'AjaxController@getAreas');//地址组件路由
Route::any('/ajax/upload', 'AjaxController@upload');//图片上传
Route::any('/ajax/del', 'AjaxController@del');//图片删除


$http_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : null;
$prefix =  explode('.',$http_host)[0];

if(explode('.',config('app.agent_url'))[0] == $prefix)
    require_once 'agent.php';      // 分销平台路由

if(explode('.',config('app.merchant_url'))[0] == $prefix)
    require_once 'merchant.php';    // 商户平台路由

if(explode('.',config('app.manage_url'))[0] == $prefix)
    require_once 'manage.php';    // 大后台路由

if(explode('.',config('app.factory_url'))[0] == $prefix)
    require_once 'sp.php';        // 工厂/供货商路由

if(explode('.',config('app.api_url'))[0] == $prefix)
    require_once 'api.php';        // api路由

if(explode('.',config('app.erp_url'))[0] == $prefix)
    require_once 'erp.php';        // erp路由

if(explode('.',config('app.manage_url'))[0] == $prefix)
    require_once 'manage.php';        // api路由

if(explode('.',config('app.erpkf_url'))[0] == $prefix)
    require_once 'erpkf.php';        // erpkf路由



