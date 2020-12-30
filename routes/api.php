<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::domain(env('API_URL','api.my.com'))->middleware('api.auth')->group(function(){
    Route::namespace('Api')->group(function () {

        Route::get('index', 'TestController@index');

        Route::post('material/upload', 'Editor\MaterialController@upload');

        Route::post('pcdiy/agent/get-agent-info', 'Editor\UserController@getAgentInfo');
        Route::any('common/global/get-server-time', 'Editor\GlobalController@getServerTime');

        Route::post('common/global/get-location-list', 'Editor\GlobalController@getLocationList');

        //商品相关
        Route::post('pcdiy/goods/get-goods', 'Editor\GoodsController@getGoods');
        Route::post('pcdiy/goods/get-goods-size-list', 'Editor\GoodsController@getGoodsSizeList');
        Route::post('pcdiy/goods/get-goods-thickness', 'Editor\GoodsController@getGoodsThickness');
        Route::post('pcdiy/goods/get-goods-list', 'Editor\GoodsController@getGoodsList');
        Route::any('pcdiy/goods/get-goods-type-list', 'Editor\GoodsController@getGoodsTypeList');

        //模板、布局、素材相关
        Route::post('pcdiy/material/get-material-style-list', 'Editor\MaterialController@GetMaterialStyleList');
        Route::post('pcdiy/material/get-material-list', 'Editor\MaterialController@GetMaterialList');

        Route::post('pcdiy/template/get-template-theme-list', 'Editor\TemplateController@GetTemplateThemeList');
        Route::post('pcdiy/template/get-template-list', 'Editor\TemplateController@GetTemplateList');
        Route::post('pcdiy/template/get-template', 'Editor\TemplateController@GetTemplate');
        Route::post('pcdiy/template/get-template-page-data', 'Editor\TemplateController@GetTemplatePageData');
        Route::post('pcdiy/template/save-template-data', 'Editor\TemplateController@SaveTemplateData');
        Route::post('pcdiy/template/save-template-thumb', 'Editor\TemplateController@SaveTemplateThumb');

        Route::post('pcdiy/template/create-template', 'Editor\TemplateController@createTemplate');

        Route::post('pcdiy/layout/get-layout-kind-list', 'Editor\LayoutController@GetLayoutKindList');
        Route::post('pcdiy/layout/get-layout-list', 'Editor\LayoutController@GetLayoutList');
        Route::post('pcdiy/layout/get-layout-data', 'Editor\LayoutController@GetLayoutData');
        Route::post('pcdiy/layout/save-layout-data', 'Editor\LayoutController@SaveLayoutData');

        //oss上传相关
        Route::post('pcdiy/upload/get-upload-sign', 'Editor\GlobalController@GetUploadSign');

        //客户图片相关接口
        Route::post('pcdiy/photo/save-photo', 'Editor\PhotoController@SavePhoto');
        Route::post('pcdiy/photo/get-photo-list', 'Editor\PhotoController@GetPhotoList');
        Route::post('pcdiy/photo/delete-photos', 'Editor\PhotoController@DeletePhotos'); //删除图片

        //管理员登录相关接口
        Route::post('pcdiy/user/get-admin-info', 'Editor\UserController@getAdminInfo');

        //商户相关接口
        Route::post('pcdiy/supplier/get-supplier-info', 'Editor\MerchantController@getSupplierInfo');
        Route::post('pcdiy/supplier/supplier-login', 'Editor\MerchantController@SupplierLogin');

        //作品相关接口
        Route::any('pcdiy/work/get-work-id', 'Editor\WorksController@GetWorkId');
        Route::post('pcdiy/work/save-work-data', 'Editor\WorksController@SaveWorkData');
        Route::post('pcdiy/work/get-work', 'Editor\WorksController@GetWorks');
        Route::post('pcdiy/work/save-work-thumb', 'Editor\WorksController@SaveWorkThumb');
        Route::post('com/work/save-work-data', 'Editor\WorksController@SaveComlWorkData'); //商印刷保存作品接口

        //合成相关接口
        Route::post('pcdiy/compound/get-compound-queue', 'Editor\CompoundController@GetCompoundQueue');
        Route::post('pcdiy/compound/update-compound-queue', 'Editor\CompoundController@UpdateCompoundQueue');
        Route::post('pcdiy/compound/get-compound-queue-list', 'Editor\CompoundController@GetCompoundQueueList');
        Route::post('pcdiy/compound/get-compound-settings', 'Editor\CompoundController@GetCompoundSettings');
        Route::post('pcdiy/compound/save-compound-settings', 'Editor\CompoundController@SaveCompoundSettings');
        Route::post('pcdiy/compound/sync-compound-settings', 'Editor\CompoundController@syncCmsSetting');

        //文件下载接口
        Route::post('download/list', 'FileDownloadController@getQueueList'); //获取待下载队列
        Route::post('download/update-queue', 'FileDownloadController@UpdateQueues'); //更新下载队列状态

        //稿件创建订单接口
        Route::post('outer/order', 'Outer\OrderController@createOrder');

        Route::post('pcdiy/provider/login', 'Editor\SupplierController@login');//供货商登录接口
        Route::post('pcdiy/provider/get-download-queues', 'Editor\SupplierController@getDownloadQueues');//获取待下载队列记录
        Route::post('pcdiy/provider/update-download-queue', 'Editor\SupplierController@updateDownloadQueue');//更新下载队列记录状态



        //erp创建生产中订单接口
        Route::post('outer/erp/get-order-info', 'Outer\ErpController@getCreateOrderInfo');//erp创建生产中订单接口

        //外协发货回写接口
        Route::post('outer/delivery/write-back', 'Outer\DeliveryController@writeBack');
        Route::post('outer/delivery/manual-write-back', 'Outer\DeliveryController@manualWriteBack');//手动发货回写到外协
        Route::post('outer/delivery/tb-write-back', 'Outer\DeliveryController@tbDeliveryWrite');//手动回写淘宝发货

        /*//测试接口
        Route::get('outer/move_erp_order_no', 'Outer\ErpController@moveOrderNo');*/


    });
    //类天猫业务的第三方接口
    Route::namespace('Api\Sync')->group(function () {
        Route::post('tb/order/info', 'Taobao\OrderController@info');
        Route::post('tb/tcm/get-group', 'Taobao\SubscribeController@getGroup');
        Route::post('tb/tcm/consume', 'Taobao\SubscribeController@consumeMessage');
        Route::post('tb/logistics/offline-send', 'Taobao\LogisticsController@offlineSend');
        Route::post('tb/logistics/resend', 'Taobao\LogisticsController@resend');//物流单号重新回写接口
        //淘宝订单备注信息回写
        Route::post('tb/order/update-memo', 'Taobao\OrderController@updateMemo');
        //电子页单接口
        Route::post('tb/cainiao/print-data', 'Taobao\CainiaoController@printData');
        //获取订单图片接口
        Route::post('tb/order/get-order-pic', 'Taobao\OrderController@getPictureInfo');


        // array('POST', '/tb/logistics/offline-send', 'App.Taobao_Logistics.OfflineSend'),
    });
});