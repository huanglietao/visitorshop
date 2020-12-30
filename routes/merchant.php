<?php
/**
 * 商户后台相关路由
 *
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2019/7/30
 */

Route::domain(config('app.merchant_url'))->group(function(){
    Route::namespace('Merchant')->group(function () {

        Route::get('login/index', 'LoginController@index');
        Route::get('/start/{type}/{t}','LoginController@start'); //初始化加载极验证码
        Route::post('login/savelogin', 'LoginController@savelogin'); //请求登录
        Route::post('login/login', 'LoginController@login'); //请求登录
        //登录验证
        Route::middleware('mch.auth')->group(function() {

            Route::get('', 'IndexController@index');
            Route::get('index', 'IndexController@index');
            Route::post('change_flag', 'IndexController@changeRuleFLag');
            Route::get('dashboard/logout', 'LoginController@logOut');
            Route::post('/getRuleRemarkAndBcrumb', 'BaseController@getRuleRemarkAndBcrumb'); //面包屑，提示信息




            //demo 模块 start
            Route::get('demo', 'DemoController@index'); //列表
            Route::post('demo/list', 'DemoController@table'); //记录
            Route::post('demo/add', 'DemoController@add'); //添加
            Route::get('demo/form', 'DemoController@form'); //添加
            Route::post('demo/save', 'DemoController@save'); //添加

            //demo 模块 end

            //dashboard 模块 start
            Route::get('dashboard', 'DashboardController@index');
            Route::post('dashboard/get_console_data', 'DashboardController@getConsoleData');
            //dashboard 模块 end

            //登录界面 start
            Route::get('login', 'LoginController@index');
            //登录界面 end

            //基础设置 start
            Route::get('system/basics', 'System\BasicsController@index'); //列表
            Route::post('system/basics/save', 'System\BasicsController@save'); //列表
            //基础设置 end


            //支付配置 模块 start
            Route::get('system/payment', 'System\PaymentController@index');
            Route::post('/system/payment/list', 'System\PaymentController@table'); //列表加载记录
            Route::get('/system/payment/form', 'System\PaymentController@form'); //添加、更新表单
            Route::post('/system/payment/save', 'System\PaymentController@save'); //保存
            Route::get('/system/payment/del/{id}', 'System\PaymentController@delete'); //删除
            //支付配置 模块 end

            //权限管理 start
            Route::get('auth/admin', 'Auth\AdminController@index');//管理员列表
            Route::post('auth/admin/list', 'Auth\AdminController@table'); //列表加载记录
            Route::get('auth/admin/form', 'Auth\AdminController@form'); //添加、更新表单
            Route::post('auth/admin/save', 'Auth\AdminController@save'); //保存
            Route::get('auth/admin/del/{id}', 'Auth\AdminController@delete'); //删除

            Route::get('auth/group', 'Auth\GroupController@index');//角色管理
            Route::post('auth/group/list', 'Auth\GroupController@table'); //列表加载记录
            Route::get('auth/group/form', 'Auth\GroupController@form'); //添加、更新表单
            Route::post('auth/group/save', 'Auth\GroupController@save'); //保存
            Route::get('auth/group/del/{id}', 'Auth\GroupController@delete'); //删除
            //权限管理 end

            //供应商管理 start
            Route::get('suppliers/suppliers','Suppliers\SuppliersController@index');//列表展示
            Route::post('suppliers/suppliers/list', 'Suppliers\SuppliersController@table'); //记录数据
            Route::get('suppliers/suppliers/form', 'Suppliers\SuppliersController@form'); //添加、更新表单
            Route::post('suppliers/suppliers/save', 'Suppliers\SuppliersController@save'); //保存
            Route::get('suppliers/suppliers/del/{id}', 'Suppliers\SuppliersController@delete'); //添加、更新表单
            Route::get('suppliers/suppliers/account', 'Suppliers\SuppliersController@account'); //账号添加、更新表单
            Route::post('suppliers/suppliers/account_save', 'Suppliers\SuppliersController@accountSave'); //账号保存
            //供应商管理 end

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
            Route::post('/goods/products/supplier_price_form', 'Goods\ProductsController@supplierPriceForm'); //供货商定价视图
            Route::post('/goods/products/onsale', 'Goods\ProductsController@onSale'); //渠道定价视图
            Route::get('/goods/products/edit/{id}', 'Goods\ProductsController@edit'); //商品编辑
            Route::post('/goods/products/update', 'Goods\ProductsController@update'); //商品更新
            Route::get('/goods/products/add_standard_products', 'Goods\ProductsController@addStandardProducts'); //添加标准化商品
            Route::post('/goods/products/get_standard_product_list', 'Goods\ProductsController@getStandardProductList'); //获取标准商品列表
            Route::post('/goods/products/add_standard_new_product', 'Goods\ProductsController@addStandardNewProductList'); //获取标准商品列表
            Route::get('/goods/custom_products_size/form',  'Goods\ProductsController@CustomProductSizeView'); //自定义规格
            Route::post('/goods/custom_products_size/save',  'Goods\ProductsController@CustomProductSizeSave'); //自定义规格
            Route::post('/goods/products/change_sort',  'Goods\ProductsController@changeSort'); //修改排序
            //商品列表模块 end
            //商品自定义分类 start
            Route::get('/goods/customcategory',  'Goods\CustomCategoryController@index'); //商家自定义分类
            Route::post('/customcategory/list',  'Goods\CustomCategoryController@table'); //商家自定义分类列表
            Route::get('/customcategory/form',  'Goods\CustomCategoryController@form'); //添加自定义分类
            Route::post('/customcategory/save',  'Goods\CustomCategoryController@save'); //保存
            Route::get('/customcategory/del/{id}',  'Goods\CustomCategoryController@delete'); //删除
            //商品自定义分类 start

           /* //商品属性模块 start
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
            //商品规格模块 end*/





            //订单管理 start
            Route::get('order/list', 'Order\ListController@index');//订单列表
            Route::post('order/list/list', 'Order\ListController@table'); //列表加载记录
            Route::get('order/cancel/{id}', 'Order\ListController@cancelOrder'); //取消订单
            Route::get('order/detail/{id}', 'Order\ListController@detail');//订单详情
            Route::any('order/reciver/{id}', 'Order\ListController@changeInfo'); //修改收货人信息
            Route::any('order/change_delivery/{id}', 'Order\ListController@changeDelivery'); //修改物流
            Route::any('order/distribution/{id}', 'Order\ListController@distribution'); //订单配货
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

            Route::get('order/service', 'Order\ServiceController@index');//订单售后
            Route::post('order/service/list', 'Order\ServiceController@table'); //列表加载记录
            Route::get('order/service/form', 'Order\ServiceController@form');//添加售后
            Route::post('order/service/save', 'Order\ServiceController@save'); //保存
            Route::post('order/service/get_amount', 'Order\ServiceController@getAmount'); //获取订单金额
            Route::get('order/service/detail/{id}', 'Order\ServiceController@detail');//售后详情
            Route::any('order/service/handle/{id}', 'Order\ServiceController@handle');//售后处理
            Route::get('order/service/del/{id}', 'Order\ServiceController@delete'); //售后删除
            Route::post('order/service/review', 'Order\ServiceController@review');//审核归档
            Route::any('order/service/exchange/{id}', 'Order\ServiceController@exchange');//添加换货单
            Route::any('order/service/export/{data}', 'Order\ServiceController@jobExport');//售后工单导出


            Route::get('order/exchange', 'Order\ExchangeController@index'); //换货单列表
            Route::post('order/exchange/list', 'Order\ExchangeController@table'); //列表加载记录
            Route::any('order/exchange/form', 'Order\ExchangeController@form');//详情

            Route::get('order/tag', 'Order\TagController@index');//订单标签
            Route::post('order/tag/list', 'Order\TagController@table'); //列表加载记录
            Route::get('order/tag/form', 'Order\TagController@form'); //添加、更新表单
            Route::get('order/tag/del/{id}', 'Order\TagController@delete'); //删除
            Route::post('order/tag/save', 'Order\TagController@save'); //保存

            Route::get('order/reason', 'Order\ReasonController@index');//售后原因文案
            Route::post('order/reason/list', 'Order\ReasonController@table'); //列表加载记录
            Route::get('order/reason/form', 'Order\ReasonController@form'); //添加、更新表单
            Route::get('order/reason/del/{id}', 'Order\ReasonController@delete'); //删除
            Route::post('order/reason/save', 'Order\ReasonController@save'); //保存



            //订单管理 end

            //订单推送管理 start
            Route::get('suppliers/orderpush','Suppliers\OrderPushController@index');//列表展示
            Route::post('suppliers/orderpush/list', 'Suppliers\OrderPushController@table'); //记录数据
//        Route::get('suppliers/orderpush/form', 'Suppliers\OrderPushController@form'); //添加、更新表单
//        Route::post('suppliers/orderpush/save', 'Suppliers\OrderPushController@save'); //保存
//        Route::get('suppliers/orderpush/del/{id}', 'Suppliers\OrderPushController@delete'); //添加、更新表单
            //订单推送管理 end

            //优惠券列表 start
            Route::get('marketing/coupon','Marketing\CouponController@index');//列表展示
            Route::post('marketing/coupon/list', 'Marketing\CouponController@table'); //记录数据
            Route::get('marketing/coupon/form', 'Marketing\CouponController@form'); //添加、更新表单
            Route::post('marketing/coupon/save', 'Marketing\CouponController@save'); //保存
            Route::get('marketing/coupon/del/{id}', 'Marketing\CouponController@delete'); //添加、更新表单
            //优惠码页面
            Route::get('marketing/couponNumber','Marketing\CouponNumberController@index');//优惠码列表展示
            Route::post('marketing/couponNumber/list', 'Marketing\CouponNumberController@table'); //记录数据
            //优惠券列表 end

            //分销管理--商家组别 start
            Route::get('agent/grade','Agent\GradeController@index');//列表展示
            Route::post('agent/grade/list', 'Agent\GradeController@table'); //记录数据
            Route::get('agent/grade/form', 'Agent\GradeController@form'); //添加、更新表单
            Route::post('agent/grade/save', 'Agent\GradeController@save'); //保存
            Route::get('agent/grade/del/{id}', 'Agent\GradeController@delete'); //添加、更新表单
            //分销管理--商家组别 end

            //分销管理--商家申请表 start
            Route::get('agent/apply','Agent\ApplyController@index');//列表展示
            Route::post('agent/apply/list', 'Agent\ApplyController@table'); //记录数据
            Route::get('agent/apply/form', 'Agent\ApplyController@form'); //添加、更新表单
            Route::post('agent/apply/save', 'Agent\ApplyController@save'); //保存
            Route::get('agent/apply/del/{id}', 'Agent\ApplyController@delete'); //添加、更新表单
            //分销管理--商家申请表 end

            //分销管理--商家列表 start
            Route::get('agent/info','Agent\InfoController@index');//列表展示
            Route::post('agent/info/list', 'Agent\InfoController@table'); //记录数据
            Route::get('agent/info/form', 'Agent\InfoController@form'); //添加、更新表单
            Route::post('agent/info/save', 'Agent\InfoController@save'); //保存
            Route::get('agent/info/capital', 'Agent\InfoController@capital'); //保存
            Route::post('agent/info/capital_save', 'Agent\InfoController@capital_save'); //保存
            Route::get('agent/info/del/{id}', 'Agent\InfoController@delete'); //添加、更新表单
            //分销管理--商家列表 end

            //分销管理--商家账号 start
            Route::get('agent/account','Agent\AccountController@index');//列表展示
            Route::post('agent/account/list', 'Agent\AccountController@table'); //记录数据
            Route::get('agent/account/form', 'Agent\AccountController@form'); //添加、更新表单
            Route::post('agent/account/save', 'Agent\AccountController@save'); //保存
            Route::get('agent/account/del/{id}', 'Agent\AccountController@delete'); //添加、更新表单
            //分销管理--商家账号 end

            //分销管理--站点配置 start
            Route::get('agent/deploy','Agent\DeployController@index');//列表展示
            Route::post('agent/deploy/save', 'Agent\DeployController@save'); //保存
            //分销管理--站点配置 end


            //会员管理--会员组别 start
            Route::get('user/grade','User\GradeController@index');//列表展示
            Route::post('user/grade/list', 'User\GradeController@table'); //记录数据
            Route::get('user/grade/form', 'User\GradeController@form'); //添加、更新表单
            Route::post('user/grade/save', 'User\GradeController@save'); //保存
            Route::get('user/grade/del/{id}', 'User\GradeController@delete'); //添加、更新表单
            //会员管理--会员组别 end

            //会员管理--会员列表 start
            Route::get('user/user','User\UserController@index');//列表展示
            Route::post('user/user/list', 'User\UserController@table'); //记录数据
            Route::get('user/user/form', 'User\UserController@form'); //添加、更新表单
            Route::post('user/user/save', 'User\UserController@save'); //保存
            Route::get('user/user/del/{id}', 'User\UserController@delete'); //添加、更新表单
            //会员管理--会员列表 end

            //会员管理--积分规则 start
            Route::get('user/score','User\ScoreController@index');//列表展示
            Route::post('user/score/list', 'User\ScoreController@table'); //记录数据
            Route::get('user/score/form', 'User\ScoreController@form'); //添加、更新表单
            Route::post('user/score/save', 'User\ScoreController@save'); //保存
            Route::get('user/score/del/{id}', 'User\ScoreController@delete'); //添加、更新表单
            //会员管理--积分规则 end

            //会员管理--资金变动 start
            Route::get('user/money','User\MoneyController@index');//列表展示
            Route::post('user/money/list', 'User\MoneyController@table'); //记录数据
            Route::get('user/money/form', 'User\MoneyController@form'); //添加、更新表单
            Route::post('user/money/save', 'User\MoneyController@save'); //保存
            Route::get('user/money/del/{id}', 'User\MoneyController@delete'); //添加、更新表单
            //会员管理--资金变动 end

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

            //文章列表管理模块 start
            Route::get('/article/list', 'Article\ListController@index'); //列表
            Route::post('/article/list/list', 'Article\ListController@table'); //列表数据加载
            Route::get('/article/list/del/{id}', 'Article\ListController@delete'); //删除
            Route::get('/article/list/form', 'Article\ListController@form'); //添加编辑
            Route::post('/article/list/save', 'Article\ListController@save'); //保存
            Route::post('/article/list/getArticleType', 'Article\ListController@getArticleType'); //获取分类
            //文章列表管理模块 end

            //消息管理模块 start
            Route::get('/news', 'News\ListsController@index'); //列表
            Route::post('/news/list', 'News\ListsController@table'); //列表数据加载
            Route::get('/news/detail', 'News\ListsController@detail'); //详情
            //消息管理模块 end

            //素材和背景管理模块 start
            Route::get('templatecenter/material', 'Templatecenter\MaterialController@index'); //列表
            Route::post('templatecenter/material/list', 'Templatecenter\MaterialController@table'); //记录数据
            Route::get('templatecenter/material/form', 'Templatecenter\MaterialController@form'); //添加、更新表单
            Route::post('templatecenter/material/save', 'Templatecenter\MaterialController@save'); //保存
            Route::get('templatecenter/material/del/{id}', 'Templatecenter\MaterialController@delete'); //删除
            Route::post('templatecenter/material/getMaterialCate', 'Templatecenter\MaterialController@getMaterialCate'); //下拉获取二级数据
            //素材管理模块 end

            //物流模板模块 start
            Route::get('/delivery/template', 'Delivery\TemplateController@index');
            Route::post('/delivery/template/list', 'Delivery\TemplateController@table'); //列表加载记录
            Route::get('/delivery/template/form', 'Delivery\TemplateController@form'); //添加、更新表单
            Route::get('/delivery/template/del/{id}', 'Delivery\TemplateController@delete'); //添加、更新表单
            Route::post('/delivery/template/save', 'Delivery\TemplateController@save'); //保存
            //物流模板模块 end

            //分销管理 资金明细 start
            Route::get('agent/fund', 'Agent\FundController@index');//资金明细
            Route::post('agent/fund/list', 'Agent\FundController@table');//资金列表
            Route::get('agent/fund/form', 'Agent\FundController@form');//详情
//            Route::any('agent/fund/export/{data}', 'Agent\FundController@fundExport');//资金明细导出
            //分销管理 资金明细 end

            //分销管理 运营策略 start
            Route::get('agent/strategy', 'Agent\Strategy\RechargeRuleController@index');//充值规则
            Route::post('agent/strategy/rechargerule/list', 'Agent\Strategy\RechargeRuleController@table');//充值规则列表
            Route::get('agent/strategy/rechargerule/form', 'Agent\Strategy\RechargeRuleController@form');//充值规则表单
            Route::post('/agent/strategy/rechargerule/save', 'Agent\Strategy\RechargeRuleController@save');//充值规则表单保存
            Route::get('/agent/strategy/rechargerule/del/{id}', 'Agent\Strategy\RechargeRuleController@delete');//删除
            //分销管理 运营策略 end

            //作品模块 start
            Route::get('/works/workslist', 'Works\WorksListController@index');
            Route::post('/works/workslist/list', 'Works\WorksListController@table'); //列表加载记录
            Route::get('/works/workslist/form', 'Works\WorksListController@form'); //添加、更新表单
            Route::get('/works/workslist/del/{id}', 'Works\WorksListController@delete'); //添加、更新表单
            Route::post('/works/workslist/save', 'Works\WorksListController@save'); //保存
            Route::post('/works/workslist/statusCount', 'Works\WorksListController@statusCount');//统计作品状态
            Route::post('/works/workslist/clone', 'Works\WorksListController@worksClone');//克隆作品
            Route::get('/works/workslist/regain', 'Works\WorksListController@regain');//恢复作品
            Route::get('/works/workslist/edit', 'Works\WorksListController@edit');//修改作品
            Route::get('/works/workslist/log', 'Works\WorksListController@log');//作品操作日志
            Route::get('/works/error', 'Works\WorksListController@projectsError');//查看异常
            //作品模块 end

            //分销菜单模块 start
            Route::get('/agent/rule', 'Agent\RuleController@index'); //列表
            Route::post('/agent/rule/list', 'Agent\RuleController@table'); //列表加载记录
            Route::get('/agent/rule/form', 'Agent\RuleController@form'); //添加、更新表单
            Route::get('/agent/rule/del/{id}', 'Agent\RuleController@delete'); //删除
            Route::post('/agent/rule/save', 'Agent\RuleController@save'); //保存
            Route::post('/agent/rule/updateField', 'Agent\RuleController@updateField'); //改变是否
            //分销菜单模块 end


            //分销管理--商家账号 start
            Route::get('agent/inviter','Agent\InviterController@index');//列表展示
            Route::post('agent/inviter/list', 'Agent\InviterController@table'); //记录数据
            Route::get('agent/inviter/form', 'Agent\InviterController@form'); //添加、更新表单
            Route::post('agent/inviter/save', 'Agent\InviterController@save'); //保存
            Route::get('agent/inviter/del/{id}', 'Agent\InviterController@delete'); //添加、更新表单
            Route::get('agent/inviter/export', 'Agent\InviterController@export'); //导出数据
            //分销管理--商家账号 end

       });

    });
});
