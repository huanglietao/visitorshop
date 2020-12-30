<?php
/**
 * 平台后台相关路由
 *
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2019/7/30
 */
Route::domain(config('app.manage_url'))->group(function(){
    Route::namespace('Backend')->group(function () {
        Route::get('login/index', 'LoginController@index');
        Route::get('/start/{type}/{t}','LoginController@start'); //初始化加载极验证码
        Route::post('login/savelogin', 'LoginController@savelogin'); //请求登录
        Route::post('login/login', 'LoginController@login'); //请求登录
        Route::get('dashboard/logout', 'LoginController@logOut');
        Route::post('/getRuleRemarkAndBcrumb', 'BaseController@getRuleRemarkAndBcrumb'); //面包屑，提示信息

        Route::get('chos', 'LoginController@chos');

        //临时电子面单批打start
        Route::any('print/print_deliver','Printer@index'); //电子面单批打
        Route::any('print/tips','Printer@tips');//打单tips路由
        Route::any('print/printdata','Printer@printData');//打单数据
        Route::any('print/delivery','Printer@delivery');//打单发货逻辑
        Route::any('/printer/import', 'Printer@import'); //导入
        Route::any('/printer/test_delivery', 'Printer@testDelivery'); //导入
        Route::post('/printer/clear', 'Printer@clearOrder'); //清除异常订单

        //临时电子面单批打end

        Route::middleware('backend.auth')->group(function() {

        Route::get('', 'IndexController@index');
        Route::get('index', 'IndexController@index');



        //demo 模块 start
        Route::get('demo', 'DemoController@index'); //列表
        Route::post('demo/list', 'DemoController@table'); //记录
        Route::post('demo/add', 'DemoController@add'); //添加
        Route::get('demo/form', 'DemoController@form'); //添加
        Route::post('demo/save', 'DemoController@save'); //添加





        //demo 模块 end

       //操作日志 模块 start
        Route::get('operationlog', 'OperationLog\IndexController@index'); //列表
        Route::post('operationlog/index/list', 'OperationLog\IndexController@table'); //记录
        Route::get('operationlog/index/del/{id}', 'OperationLog\IndexController@delete'); //刪除
        Route::get('operationlog/index/form/{id}', 'OperationLog\IndexController@detail'); //详情
        Route::post('operationlog/index/save', 'OperationLog\IndexController@save'); //添加
        //操作日志 模块 end

        //错误日志 模块 start
        Route::get('systemerror', 'SystemError\IndexController@index'); //列表
        Route::post('systemerror/index/list', 'SystemError\IndexController@table'); //记录
        Route::get('systemerror/index/del/{id}', 'SystemError\IndexController@delete'); //刪除
        Route::get('systemerror/index/form/{id}', 'SystemError\IndexController@detail'); //详情
        Route::post('systemerror/index/save', 'SystemError\IndexController@save'); //添加
        //错误日志 模块 end

        //系统设置 模块 start
        Route::get('system/basics', 'System\BasicsController@index'); //列表
        Route::any('system/basics/save', 'System\BasicsController@save'); //添加、保存
        //系统设置 模块 end

        //dashboard 模块 start
        Route::get('dashboard', 'DashboardController@index');
        //dashboard 模块 end
        
        //商户管理 模块 start
        Route::get('/merchant/info', 'Merchant\InfoController@index'); //商户资料
        Route::get('/merchant/info/create', 'Merchant\InfoController@create'); //创建账号
        Route::post('/merchant/info/list', 'Merchant\InfoController@table'); //列表加载记录
        Route::get('/merchant/info/form', 'Merchant\InfoController@form'); //添加、更新表单
        Route::get('/merchant/info/del/{id}', 'Merchant\InfoController@delete'); //删除
        Route::post('/merchant/info/save', 'Merchant\InfoController@save'); //保存

        Route::get('/merchant/account', 'Merchant\AccountController@index'); //商户账号
        Route::post('/merchant/account/list', 'Merchant\AccountController@table'); //列表加载记录
        Route::get('/merchant/account/form', 'Merchant\AccountController@form'); //添加、更新表单
        Route::get('/merchant/account/del/{id}', 'Merchant\AccountController@delete'); //删除
        Route::post('/merchant/account/save', 'Merchant\AccountController@save'); //保存

        Route::get('/merchant/group', 'Merchant\GroupController@index'); //商户角色
        Route::post('/merchant/group/list', 'Merchant\GroupController@table'); //列表加载记录
        Route::get('/merchant/group/form', 'Merchant\GroupController@form'); //添加、更新表单
        Route::get('/merchant/group/del/{id}', 'Merchant\GroupController@delete'); //删除
        Route::post('/merchant/group/save', 'Merchant\GroupController@save'); //保存

        Route::get('/merchant/omsrule', 'Merchant\OmsRuleController@index'); //列表
        Route::post('/merchant/omsrule/list', 'Merchant\OmsRuleController@table'); //列表加载记录
        Route::get('/merchant/omsrule/form', 'Merchant\OmsRuleController@form'); //添加、更新表单
        Route::get('/merchant/omsrule/del/{id}', 'Merchant\OmsRuleController@delete'); //删除
        Route::post('/merchant/omsrule/save', 'Merchant\OmsRuleController@save'); //保存
        Route::post('/merchant/omsrule/updateField', 'Merchant\OmsRuleController@updateField'); //改变是否
        //商户管理 模块 end

        //登录界面 start
        Route::get('login','LoginController@index');
        //登录界面 end

        //dashboard 模块 start
        Route::get('dashboard', 'DashboardController@index');
        Route::post('dashboard/get_console_data', 'DashboardController@getConsoleData');//渲染控制台部分数据
        Route::post('dashboard/get_base_data', 'DashboardController@getBaseDate');//渲染控制台部分数据
        Route::post('dashboard/get_order_trend', 'DashboardController@getOrderTrend');//商户订单走势
        Route::post('dashboard/get_work_monitor', 'DashboardController@getWorkMonitor');//作品监控
        Route::post('dashboard/get_delivery_monitor', 'DashboardController@getDeliveryMonitor');//物流/发货统计

        Route::post('dashboard/get_order_sale_count', 'DashboardController@getOrderSalesCount');//渲染订单销售额
        //dashboard 模块 end

        //支付配置 模块 start
        Route::get('/system/payment', 'System\PaymentController@index');
        Route::post('/system/payment/list', 'System\PaymentController@table'); //列表加载记录
        Route::get('/system/payment/form', 'System\PaymentController@form'); //添加、更新表单
        Route::post('/system/payment/save', 'System\PaymentController@save'); //保存
        Route::get('/system/payment/del/{id}', 'System\PaymentController@delete'); //删除
        //支付配置 模块 end

        //邮件配置 模块 start
        Route::get('/system/smtp', 'System\SmtpController@index');
        Route::post('/system/smtp/list', 'System\SmtpController@table'); //列表加载记录
        Route::get('/system/smtp/form', 'System\SmtpController@form'); //添加、更新表单
        Route::get('/system/smtp/del/{id}', 'System\SmtpController@delete'); //删除
        Route::post('/system/smtp/save', 'System\SmtpController@save'); //保存
        //邮件配置 模块 end

        //物流配置 模块 start
        Route::get('/delivery/express', 'Delivery\ExpressController@index');
        Route::post('/delivery/express/list', 'Delivery\ExpressController@table'); //列表加载记录
        Route::get('/delivery/express/form', 'Delivery\ExpressController@form'); //添加、更新表单
        Route::get('/delivery/express/del/{id}', 'Delivery\ExpressController@delete'); //添加、更新表单
        Route::post('/delivery/express/save', 'Delivery\ExpressController@save'); //保存
        //物流配置 模块 end

        //异常代码模块 start
        Route::get('exception', 'ExceptionController@index'); //列表
        Route::post('exception/list','ExceptionController@table');//数据加载
        //异常代码模块 end

        //分类管理 模块 start
        Route::get('/category', 'CategoryController@index');
        Route::post('/category/list', 'CategoryController@table'); //列表加载记录
        Route::get('/category/form', 'CategoryController@form'); //添加、更新表单
        Route::get('/category/{id}', 'CategoryController@delete'); //添加、更新表单
        Route::post('/category/save', 'CategoryController@save'); //保存
        //分类管理 模块 end

        //地址库配置模块 start//
        Route::get('areaseting', 'AreasetingController@index'); //列表
        Route::post('areaseting/list','AreasetingController@table');//数据加载
        Route::get('areaseting/form', 'AreasetingController@form'); //添加,编辑
        Route::post('areaseting/save', 'AreasetingController@save'); //保存
        Route::post('areaseting/getAreasPid', 'AreasetingController@getAreasPid'); //返回按钮请求
        //地址库配置模块 end

        //运送方式 模块 start
        Route::get('/delivery/delivery', 'Delivery\DeliveryController@index');
        Route::post('/delivery/delivery/list', 'Delivery\DeliveryController@table'); //列表加载记录
        Route::get('/delivery/delivery/form', 'Delivery\DeliveryController@form'); //添加、更新表单
        Route::get('/delivery/delivery/del/{id}', 'Delivery\DeliveryController@delete'); //添加、更新表单
        Route::post('/delivery/delivery/save', 'Delivery\DeliveryController@save'); //保存
        //运送方式 模块 end

        //物流模板模块 start
        Route::get('/delivery/template', 'Delivery\TemplateController@index');
        Route::post('/delivery/template/list', 'Delivery\TemplateController@table'); //列表加载记录
        Route::get('/delivery/template/form', 'Delivery\TemplateController@form'); //添加、更新表单
        Route::get('/delivery/template/del/{id}', 'Delivery\TemplateController@delete'); //添加、更新表单
        Route::post('/delivery/template/save', 'Delivery\TemplateController@save'); //保存
        //物流模板模块 end


        //商品列表模块 start
        Route::get('/products', 'Goods\ProductsController@index');
        Route::post('/goods/products/list', 'Goods\ProductsController@table'); //列表加载记录
        Route::get('/goods/products/form', 'Goods\ProductsController@form'); //添加、更新表单
        Route::get('/goods/products/del/{id}', 'Goods\ProductsController@delete'); //添加、更新表单
        Route::post('/goods/products/save', 'Goods\ProductsController@save'); //保存
        Route::post('/goods/products/search_category', 'Goods\ProductsController@categorySearch'); //分类查询
        Route::post('/goods/products/get_attribute', 'Goods\ProductsController@getAttribute'); //获取sku列表
        Route::post('/goods/products/get_cate_size', 'Goods\ProductsController@getCateSize'); //获取规格列表
        Route::post('/goods/products/get_sku_table', 'Goods\ProductsController@getSkuTable'); //获取sku组合table
        Route::post('/goods/products/sales_price-form', 'Goods\ProductsController@salesPriceForm'); //渠道定价视图
        Route::post('/goods/products/supplier_price_form', 'Goods\ProductsController@supplierPriceForm'); //渠道定价视图
        Route::post('/goods/products/onsale', 'Goods\ProductsController@onSale'); //渠道定价视图
        Route::get('/goods/products/edit/{id}', 'Goods\ProductsController@edit'); //商品编辑
        Route::post('/goods/products/update', 'Goods\ProductsController@update'); //商品更新
        Route::get('/goods/custom_products_size/form',  'Goods\ProductsController@CustomProductSizeView'); //自定义规格
        Route::post('/goods/custom_products_size/save',  'Goods\ProductsController@CustomProductSizeSave'); //自定义规格
        Route::post('/goods/products/change_sort',  'Goods\ProductsController@changeSort'); //修改排序
        //商品列表模块 end


        //商品属性模块 start
        Route::get('/goods/products_attribute', 'Goods\ProductsAttributeController@index');
        Route::post('/goods/products_attribute/list', 'Goods\ProductsAttributeController@table'); //列表加载记录
        Route::get('/goods/products_attribute/form', 'Goods\ProductsAttributeController@form'); //添加、更新表单
        Route::get('/goods/products_attribute/del/{id}', 'Goods\ProductsAttributeController@delete'); //添加、更新表单
        Route::post('/goods/products_attribute/save', 'Goods\ProductsAttributeController@save'); //保存
        Route::post('/goods/products_attribute/del_attr_value', 'Goods\ProductsAttributeController@deleteAttrValue'); //删除属性值
        //商品属性模块 end

        //商品规格模块 start
        Route::get('/goods/products_size', 'Goods\ProductsSizeController@index');
        Route::post('/goods/products_size/list', 'Goods\ProductsSizeController@table'); //列表加载记录
        Route::get('/goods/products_size/form', 'Goods\ProductsSizeController@form'); //添加、更新表单
        Route::get('/goods/products_size/del/{id}', 'Goods\ProductsSizeController@delete'); //添加、更新表单
        Route::post('/goods/products_size/save', 'Goods\ProductsSizeController@save'); //保存
        //商品规格模块 end



        //管理员模块 start
        Route::get('/auth/cmsadmin', 'Auth\CmsAdminController@index'); //列表
        Route::post('/auth/cmsadmin/list', 'Auth\CmsAdminController@table'); //列表数据加载
        Route::get('/auth/cmsadmin/form', 'Auth\CmsAdminController@form'); //添加、更新表单
        Route::get('/auth/cmsadmin/del/{id}', 'Auth\CmsAdminController@delete'); //删除
        Route::post('/auth/cmsadmin/save', 'Auth\CmsAdminController@save'); //保存
        //管理员模块 end

        //角色管理 start
        Route::get('auth/cmsauthgroup','Auth\CmsAuthGroupController@index');//列表展示
        Route::post('auth/cmsauthgroup/list', 'Auth\CmsAuthGroupController@table'); //记录数据
        Route::get('auth/cmsauthgroup/form', 'Auth\CmsAuthGroupController@form'); //添加、更新表单
        Route::post('auth/cmsauthgroup/save', 'Auth\CmsAuthGroupController@save'); //保存
        //Route::post('auth/cmsauthgroup/tree', 'Auth\CmsAuthGroupController@tree'); //获取菜单规则
        Route::get('/auth/cmsauthgroup/del/{id}', 'Auth\CmsAuthGroupController@delete'); //删除
        //角色管理 end

        //菜单模块 start
        Route::get('/auth/rule', 'Auth\RuleController@index'); //列表
        Route::post('/auth/rule/list', 'Auth\RuleController@table'); //列表数据加载
        Route::get('/auth/rule/form', 'Auth\RuleController@form'); //添加、更新表单
        Route::get('/auth/rule/del/{id}', 'Auth\RuleController@delete'); //删除
        Route::post('/auth/rule/save', 'Auth\RuleController@save'); //保存
        Route::post('/auth/rule/updateField', 'Auth\RuleController@updateField'); //改变是否
        //菜单模块 end

        //供应商管理 start
        Route::get('suppliers/suppliers','Suppliers\SuppliersController@index');//列表展示
        Route::post('suppliers/suppliers/list', 'Suppliers\SuppliersController@table'); //记录数据
        Route::get('suppliers/suppliers/form', 'Suppliers\SuppliersController@form'); //添加、更新表单
        Route::post('suppliers/suppliers/save', 'Suppliers\SuppliersController@save'); //保存
        Route::get('suppliers/suppliers/del/{id}', 'Suppliers\SuppliersController@delete'); //添加、更新表单
        Route::get('suppliers/suppliers/account', 'Suppliers\SuppliersController@account'); //账号添加、更新表单
        Route::post('suppliers/suppliers/account_save', 'Suppliers\SuppliersController@accountSave'); //账号保存
        Route::get('suppliers/suppliers/logistics_costs', 'Suppliers\SuppliersController@logisticsCosts'); //物流成本设置
        Route::post('suppliers/suppliers/costSave', 'Suppliers\SuppliersController@costSave'); //物流成本设置保存
        //供应商管理 end

        //布局管理版式 start
        Route::get('/templatelayout/type', 'TemplateLayout\TypeController@index'); //列表
        Route::post('/templatelayout/type/list', 'TemplateLayout\TypeController@table'); //列表数据加载
        Route::get('/templatelayout/type/form', 'TemplateLayout\TypeController@form'); //添加、更新表单
        Route::get('/templatelayout/type/del/{id}', 'TemplateLayout\TypeController@delete'); //删除
        Route::post('/templatelayout/type/save', 'TemplateLayout\TypeController@save'); //保存
        //布局管理版式模块 end

        //布局列表管理模块 start
        Route::get('/templatelayout/main', 'TemplateLayout\MainController@index'); //列表
        Route::post('/templatelayout/main/list', 'TemplateLayout\MainController@table'); //列表数据加载
        Route::get('/templatelayout/main/form', 'TemplateLayout\MainController@form'); //添加、更新表单
        Route::get('/templatelayout/main/del/{id}', 'TemplateLayout\MainController@delete'); //删除
        Route::post('/templatelayout/main/save', 'TemplateLayout\MainController@save'); //保存
        Route::post('/templatelayout/main/getSpecStyle', 'TemplateLayout\MainController@getSpecStyle'); //获取规格标签数据
        Route::post('/templatelayout/main/getGoodsSpecLink', 'TemplateLayout\MainController@getGoodsSpecLink'); //规格标签获取对应规格数据
        Route::any('/templatelayout/main/getSpecdetail', 'TemplateLayout\MainController@getSpecdetail'); //规格参数
        Route::post('/templatelayout/main/checkstatus', 'TemplateLayout\MainController@checkstatus'); //改变审核状态
        Route::post('/templatelayout/main/copy', 'TemplateLayout\MainController@copy'); //克隆
        //布局列表管理模块 end

        //订单推送管理 start
        Route::get('suppliers/orderpush','Suppliers\OrderPushController@index');//列表展示
        Route::post('suppliers/orderpush/list', 'Suppliers\OrderPushController@table'); //记录数据
//        Route::get('suppliers/orderpush/form', 'Suppliers\OrderPushController@form'); //添加、更新表单
//        Route::post('suppliers/orderpush/save', 'Suppliers\OrderPushController@save'); //保存
//        Route::get('suppliers/orderpush/del/{id}', 'Suppliers\OrderPushController@delete'); //添加、更新表单
        //订单推送管理 end

        //素材和背景管理模块 start
        Route::get('template/material', 'Template\MaterialController@index'); //列表
        Route::post('template/material/list', 'Template\MaterialController@table'); //记录数据
        Route::get('template/material/form', 'Template\MaterialController@form'); //添加、更新表单
        Route::post('template/material/save', 'Template\MaterialController@save'); //保存
        Route::get('template/material/del/{id}', 'Template\MaterialController@delete'); //删除
        Route::post('template/material/getMaterialCate', 'Template\MaterialController@getMaterialCate'); //下拉获取二级数据
        Route::post('template/material/getMaterCateFlag', 'Template\MaterialController@getMaterCateFlag'); //下拉获取分类标识
        //素材管理模块 end

        //模板中心模块 start
        Route::get('/templatecenter/main', 'Templatecenter\MainController@index'); //列表
        Route::post('/templatecenter/main/list', 'Templatecenter\MainController@table'); //列表数据加载
        Route::get('/templatecenter/main/form', 'Templatecenter\MainController@form'); //添加、更新表单
        Route::get('/templatecenter/main/del/{id}', 'Templatecenter\MainController@delete'); //删除
        Route::post('/templatecenter/main/save', 'Templatecenter\MainController@save'); //保存
        Route::post('/templatecenter/main/getGoodsSpecLink', 'Templatecenter\MainController@getGoodsSpecLink'); //获取规格
        Route::any('/templatecenter/main/specdetail', 'Templatecenter\MainController@specdetail'); //规格参数
        Route::post('/templatecenter/main/updateField', 'Templatecenter\MainController@updateField'); //改变是否
        Route::post('/templatecenter/main/checkstatus', 'Templatecenter\MainController@checkstatus'); //改变审核状态
        Route::get('/templatecenter/main/setting', 'Templatecenter\MainController@setting'); //获取封面，内页id
        Route::post('/templatecenter/main/tempdata', 'Templatecenter\MainController@tempdata'); //配置获取封面内页数据
        Route::post('/templatecenter/main/copy', 'Templatecenter\MainController@copy'); //克隆
        Route::post('/templatecenter/main/getBackPx', 'Templatecenter\MainController@getBackPx'); //获取背景尺寸
        //模板中心模块 end

        //模板中心子页模块 start
        Route::any('/templatecenter/mainchild/childindex', 'Templatecenter\MainChildController@childindex'); //子页列表
        Route::post('/templatecenter/mainchild/list', 'Templatecenter\MainChildController@table'); //列表数据加载
        Route::get('/templatecenter/mainchild/del/{id}', 'Templatecenter\MainChildController@delete'); //删除
        Route::get('/templatecenter/mainchild/form', 'Templatecenter\MainChildController@form'); //添加编辑
        Route::post('/templatecenter/mainchild/save', 'Templatecenter\MainChildController@save'); //保存
        Route::get('/templatecenter/mainchild/copy', 'Templatecenter\MainChildController@copy'); //克隆from
        Route::post('/templatecenter/mainchild/docopy', 'Templatecenter\MainChildController@docopy'); //克隆
        //模板中心子页模块 end

        //封面模板模块 start
        Route::get('/templatecenter/face', 'Templatecenter\FaceController@index'); //列表
        Route::post('/templatecenter/face/list', 'Templatecenter\FaceController@table'); //列表数据加载
        Route::get('/templatecenter/face/del/{id}', 'Templatecenter\FaceController@delete'); //删除
        Route::get('/templatecenter/face/form', 'Templatecenter\FaceController@form'); //添加编辑
        Route::post('/templatecenter/face/save', 'Templatecenter\FaceController@save'); //保存
        Route::any('/templatecenter/face/specdetail', 'Templatecenter\FaceController@specdetail'); //规格参数
        Route::post('/templatecenter/face/checkstatus', 'Templatecenter\FaceController@checkstatus'); //改变审核状态
        Route::post('/templatecenter/face/getBackPx', 'Templatecenter\FaceController@getBackPx'); //获取背景尺寸
        //封面模板模块 end

        //内页模板模块 start
        Route::get('/templatecenter/inner', 'Templatecenter\InnerController@index'); //列表
        Route::post('/templatecenter/inner/list', 'Templatecenter\InnerController@table'); //列表数据加载
        Route::get('/templatecenter/inner/del/{id}', 'Templatecenter\InnerController@delete'); //删除
        Route::get('/templatecenter/inner/form', 'Templatecenter\InnerController@form'); //添加编辑
        Route::post('/templatecenter/inner/save', 'Templatecenter\InnerController@save'); //保存
        Route::post('/templatecenter/inner/checkstatus', 'Templatecenter\InnerController@checkstatus'); //改变审核状态
        //内页模板模块 end

        //内页模板子页模块 start
        Route::any('/templatecenter/innerchild/childindex', 'Templatecenter\InnerChildController@childindex'); //子页列表
        Route::post('/templatecenter/innerchild/list', 'Templatecenter\InnerChildController@table'); //列表数据加载
        Route::get('/templatecenter/innerchild/del/{id}', 'Templatecenter\InnerChildController@delete'); //删除
        Route::get('/templatecenter/innerchild/form', 'Templatecenter\InnerChildController@form'); //添加编辑
        Route::post('/templatecenter/innerchild/save', 'Templatecenter\InnerChildController@save'); //保存
        Route::get('/templatecenter/innerchild/copy', 'Templatecenter\InnerChildController@copy'); //克隆from
        Route::post('/templatecenter/innerchild/docopy', 'Templatecenter\InnerChildController@docopy'); //克隆
        //内页模板子页模块 end

        //模板标签模块 start
        Route::any('/templatecenter/tags', 'Templatecenter\TagsController@index'); //列表
        Route::post('/templatecenter/tags/list', 'Templatecenter\TagsController@table'); //列表数据加载
        Route::get('/templatecenter/tags/del/{id}', 'Templatecenter\TagsController@delete'); //删除
        Route::get('/templatecenter/tags/form', 'Templatecenter\TagsController@form'); //添加编辑
        Route::post('/templatecenter/tags/save', 'Templatecenter\TagsController@save'); //保存
        //模板标签模块 end

        //广告管理列表模块 start
        Route::any('/advertisement/adlist', 'Advertisement\AdlistController@index'); //列表
        Route::post('/advertisement/adlist/list', 'Advertisement\AdlistController@table'); //列表数据加载
        Route::get('/advertisement/adlist/del/{id}', 'Advertisement\AdlistController@delete'); //删除
        Route::get('/advertisement/adlist/form', 'Advertisement\AdlistController@form'); //添加编辑
        Route::post('/advertisement/adlist/save', 'Advertisement\AdlistController@save'); //保存
        Route::post('/advertisement/adlist/getAdPosList', 'Advertisement\AdlistController@getAdPosList'); //获取广告位置
        Route::post('/advertisement/adlist/getPositionInfo', 'Advertisement\AdlistController@getPositionInfo'); //获取广告位置详情
        Route::get('/advertisement/adlist/posthumb', 'Advertisement\AdlistController@posthumb'); //查看示意图
        //广告管理列表模块 end

        //广告位置管理模块 start
        Route::any('/advertisement/adposition', 'Advertisement\AdPositionController@index'); //列表
        Route::post('/advertisement/adposition/list', 'Advertisement\AdPositionController@table'); //列表数据加载
        Route::get('/advertisement/adposition/del/{id}', 'Advertisement\AdPositionController@delete'); //删除
        Route::get('/advertisement/adposition/form', 'Advertisement\AdPositionController@form'); //添加编辑
        Route::post('/advertisement/adposition/save', 'Advertisement\AdPositionController@save'); //保存
        //广告位置管理模块 end

        //文章分类管理模块 start
        Route::get('/article/type', 'Article\TypeController@index'); //列表
        Route::post('/article/type/list', 'Article\TypeController@table'); //列表数据加载
        Route::get('/article/type/del/{id}', 'Article\TypeController@delete'); //删除
        Route::get('/article/type/form', 'Article\TypeController@form'); //添加编辑
        Route::post('/article/type/save', 'Article\TypeController@save'); //保存
        //文章分类管理模块 end

        //文章列表管理模块 start
        Route::get('/article/list', 'Article\ListController@index'); //列表
        Route::post('/article/list/list', 'Article\ListController@table'); //列表数据加载
        Route::get('/article/list/del/{id}', 'Article\ListController@delete'); //删除
        Route::get('/article/list/form', 'Article\ListController@form'); //添加编辑
        Route::post('/article/list/save', 'Article\ListController@save'); //保存
        Route::post('/article/list/getArticleType', 'Article\ListController@getArticleType'); //获取分类
        //文章列表管理模块 end

        //商业印刷模板模块 start
        Route::any('/templatecenter/commercialtemp', 'Templatecenter\CommercialTempController@index'); //列表
        Route::post('/templatecenter/commercialtemp/list', 'Templatecenter\CommercialTempController@table'); //列表数据加载
        Route::get('/templatecenter/commercialtemp/form', 'Templatecenter\CommercialTempController@form'); //添加编辑
        Route::post('/templatecenter/commercialtemp/save', 'Templatecenter\CommercialTempController@save'); //保存
        //商业印刷模板模块 end

        //订单管理模块 start
        Route::get('order/list', 'Order\ListController@index');//订单列表
        Route::post('order/list/list', 'Order\ListController@table'); //列表加载记录
        Route::get('order/cancel/{id}', 'Order\ListController@cancelOrder'); //取消订单
        Route::get('order/detail/{id}', 'Order\ListController@detail');//订单详情
        Route::any('order/reciver/{id}', 'Order\ListController@changeInfo'); //修改收货人信息
        Route::any('order/change_delivery/{id}', 'Order\ListController@changeDelivery'); //修改物流
        Route::any('order/production/{id}', 'Order\ListController@production'); //提交生产
        Route::any('order/delivery/{id}', 'Order\ListController@delivery'); //订单发货
        Route::any('order/list/tag/{id}', 'Order\ListController@orderTag'); //订单标签
        Route::any('order/list/check/{number}', 'Order\ListController@checkFile'); //审核文件
        Route::post('order/list/reload', 'Order\ListController@reloadImg'); //重新出图
        Route::any('order/list/download_check', 'Order\ListController@downloadCheck'); //下载前检查文件
        Route::any('order/list/download', 'Order\ListController@downloadFile'); //下载文件
        Route::any('order/list/manuscript', 'Order\ListController@insideCover'); //封面内页下载
        Route::any('order/list/export/{data}', 'Order\ListController@orderExport');//订单导出
        Route::any('order/list/logistics/{id}', 'Order\ListController@logistics');//物流信息
        Route::post('order/list/count', 'Order\ListController@getCount');//订单数量统计


        Route::get('order/tag', 'Order\TagController@index');//订单标签
        Route::post('order/tag/list', 'Order\TagController@table'); //列表加载记录
        Route::get('order/tag/form', 'Order\TagController@form'); //添加、更新表单
        Route::get('order/tag/del/{id}', 'Order\TagController@delete'); //删除
        Route::post('order/tag/save', 'Order\TagController@save'); //保存
        //订单管理模块 end

        //薪酬管理模块 start
        Route::get('/salary/worker', 'Salary\WorkerController@index');//职工列表
        Route::post('/salary/worker/list', 'Salary\WorkerController@table'); //列表加载记录
        Route::get('/salary/worker/form', 'Salary\WorkerController@form'); //添加、更新表单
        Route::get('/salary/worker/del/{id}', 'Salary\WorkerController@delete'); //删除
        Route::post('/salary/worker/save', 'Salary\WorkerController@save'); //保存

        Route::get('/salary/detail', 'Salary\DetailController@index');//职工列表
        Route::post('/salary/detail/list', 'Salary\DetailController@table'); //列表加载记录
        Route::post('/salary/detail/import', 'Salary\DetailController@import'); //导入表格
        Route::get('/salary/detail/export', 'Salary\DetailController@export'); //导出数据
        Route::get('/salary/detail/del/{id}', 'Salary\DetailController@delete'); //删除
        //薪酬管理模块 end

        //队列管理模块
        //同步队列管理模块 start
        Route::get('/queue/ordersyncqueue', 'Queue\OrderSyncQueueController@index'); //列表
        Route::post('/queue/ordersyncqueue/list', 'Queue\OrderSyncQueueController@table'); //列表数据加载
        Route::post('/queue/ordersyncqueue/changQueueStatus', 'Queue\OrderSyncQueueController@changQueueStatus'); //改变队列状态
        //同步队列管理模块 end

        //合成队列管理模块 start
        Route::get('/queue/compoundqueue', 'Queue\CompoundQueueController@index'); //列表
        Route::post('/queue/compoundqueue/list', 'Queue\CompoundQueueController@table'); //列表数据加载
        Route::post('/queue/compoundqueue/changQueueStatus', 'Queue\CompoundQueueController@changQueueStatus');//改变队列状态
        Route::get('/queue/compoundqueue/form', 'Queue\CompoundQueueController@form'); //添加、更新表单
        Route::post('/queue/compoundqueue/save', 'Queue\CompoundQueueController@save'); //保存
        //合成队列管理模块 end

        //生成队列管理模块 start
        Route::get('/queue/opqueue', 'Queue\OrderProduceQueueController@index'); //列表
        Route::post('/queue/opqueue/list', 'Queue\OrderProduceQueueController@table'); //列表数据加载
        Route::post('/queue/opqueue/changQueueStatus', 'Queue\OrderProduceQueueController@changQueueStatus');//改变队列状态
        Route::get('/queue/opqueue/form', 'Queue\OrderProduceQueueController@form'); //添加、更新表单
        Route::post('/queue/opqueue/save', 'Queue\OrderProduceQueueController@save'); //保存
        //生成队列管理模块 end

        //推送队列管理模块 start
        Route::get('/queue/erppushqueue', 'Queue\ErpPushQueueController@index'); //列表
        Route::post('/queue/erppushqueue/list', 'Queue\ErpPushQueueController@table'); //列表数据加载
        Route::post('/queue/erppushqueue/changQueueStatus', 'Queue\ErpPushQueueController@changQueueStatus');//改变队列状态
        Route::get('/queue/erppushqueue/form', 'Queue\ErpPushQueueController@form'); //添加、更新表单
        Route::post('/queue/erppushqueue/save', 'Queue\ErpPushQueueController@save'); //保存
        //推送队列管理模块 end

        //物流反写队列管理模块 start
        Route::get('/queue/deliveryqueue', 'Queue\DeliveryQueueController@index'); //列表
        Route::post('/queue/deliveryqueue/list', 'Queue\DeliveryQueueController@table'); //列表数据加载
        Route::post('/queue/deliveryqueue/changQueueStatus', 'Queue\DeliveryQueueController@changQueueStatus');//改变队列状态
        Route::get('/queue/deliveryqueue/form', 'Queue\DeliveryQueueController@form'); //添加、更新表单
        Route::post('/queue/deliveryqueue/save', 'Queue\DeliveryQueueController@save'); //保存
        //物流反写队列管理模块 end
        //队列管理模块

        //运维管理 模块 start
        Route::get('/maintenance/exceptionlog', 'Maintenance\ExceptionLogController@index');
        Route::post('/maintenance/exceptionlog/list', 'Maintenance\ExceptionLogController@table'); //列表加载记录
        Route::get('/maintenance/exceptionlog/form', 'Maintenance\ExceptionLogController@form'); //添加、更新表单
        Route::get('/maintenance/exceptionlog/{id}', 'Maintenance\ExceptionLogController@delete'); //添加、更新表单
        Route::post('/maintenance/exceptionlog/save', 'Maintenance\ExceptionLogController@save'); //保存
        Route::post('/maintenance/exception/updateField', 'Maintenance\ExceptionLogController@updateField'); //是否处理
        //运维管理 模块 end

        //数据管理 start
        //商品统计 start
        Route::get('statistics/goods','Statistics\GoodsController@index');//列表展示
        Route::post('statistics/goods/list', 'Statistics\GoodsController@table'); //记录数据
        Route::get('statistics/goods/export', 'Statistics\GoodsController@export'); //导出数据

        //订单发货统计 start
        Route::get('statistics/orders','Statistics\OrdersController@index');//列表展示
        Route::post('statistics/orders/list', 'Statistics\OrdersController@table'); //记录数据
        Route::get('statistics/orders/export', 'Statistics\OrdersController@export'); //导出数据
        //订单发货统计 end

        //物流对账 start
        Route::get('statistics/express','Statistics\ExpressController@index');//列表展示
        Route::post('statistics/express/list', 'Statistics\ExpressController@table'); //记录数据
        Route::get('statistics/express/export', 'Statistics\ExpressController@export'); //导出数据
        Route::post('statistics/express/import', 'Statistics\ExpressController@import'); //导入物流成本
        //物流对账 end

        //利润对账 start
        Route::get('statistics/profit','Statistics\ProfitController@index');//列表展示
        Route::post('statistics/profit/list', 'Statistics\ProfitController@table'); //记录数据
        Route::get('statistics/profit/export', 'Statistics\ProfitController@export'); //导出数据
        //利润对账 end

        //销售成本 start
        Route::get('statistics/costs','Statistics\CostsController@index');//列表展示
        Route::post('statistics/costs/list', 'Statistics\CostsController@table'); //记录数据
        Route::get('statistics/costs/export', 'Statistics\CostsController@export'); //导出数据
        //销售成本 end

        //交货率 start
        Route::get('statistics/consignment','Statistics\ConsignmentController@index');//列表展示
        Route::post('statistics/consignment/list', 'Statistics\ConsignmentController@table'); //记录数据
        Route::get('statistics/consignment/export', 'Statistics\ConsignmentController@export'); //导出数据
        //交货率 end

        //模板统计 start
        Route::get('statistics/temp','Statistics\TempController@index');//列表展示
        Route::post('statistics/temp/list', 'Statistics\TempController@table'); //记录数据
        Route::get('statistics/temp/export/{data}', 'Statistics\TempController@export'); //导出数据
        //模板统计 end
        //数据管理 end





        });

    });
});
