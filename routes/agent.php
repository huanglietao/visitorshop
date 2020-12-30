<?php
/**
 * 分销后台路由
 *
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2019/7/30
 */

Route::domain(config('app.agent_url'))->group(function(){
    Route::namespace('Agent')->group(function () {

        Route::get('/demo/sync-temp', 'DemoController@syncTemp');
        Route::get('/demo/sync-material', 'DemoController@syncMaterial');
        Route::get('/demo/sync-layout', 'DemoController@syncLayout');
         Route::get('/demo/sync-prono', 'DemoController@syncSkuProNo');
        //官网首页 start
        Route::get('/index/home', 'IndexController@home');
        //官网首页 end

        //注册 start
        Route::get('/index/register', 'IndexController@register');
        Route::post('/index/register/save', 'IndexController@save');
        Route::post('/register/checkMobile', 'IndexController@checkMobile');
        Route::get('/index/clause',function (){
            return view('agent.index.clause');
        });
        //注册 end

        Route::get('/index/forget', 'IndexController@forget'); //忘记密码
        Route::post('/index/check', 'IndexController@checkName'); //检查账号
        Route::post('/index/code', 'IndexController@getCode'); //获取验证码
        Route::post('/index/verification', 'IndexController@verification'); //校验并修改

        Route::get('/index/subsuc', 'IndexController@workSuccess'); //作品提交成功页

        Route::get('/tool/index', 'ToolController@index'); //客服小工具
        Route::post('/tool/revoke', 'ToolController@revokeProduct'); //撤销生产状态
        Route::post('/tool/synchronization', 'ToolController@synchronization'); //新增二次同步标识


        //后台首页 start
        Route::get('', 'IndexController@index');
        Route::get('/mch_id/{mch_id}', 'IndexController@index');
        Route::get('index', 'IndexController@index');
        //后台首页 end

        // Controllers Within The "App\Http\Controllers\Agent" Namespace
        Route::get('/login', 'LoginController@index');
        Route::get('/start/{type}/{t}','LoginController@start'); //初始化加载极验证码
        Route::post('login/savelogin', 'LoginController@savelogin'); //请求登录
        Route::post('login/login', 'LoginController@login'); //请求登录
        //登录验证

        //账户充值
        Route::any('/finance/recharge/alipaynotify','Finance\RechargeController@alipaynotify');//支付宝充值回调
        Route::any('/finance/recharge/alipayreturn','Finance\RechargeController@alipayreturn');//支付宝充值成功返回页面

        Route::any('/finance/recharge/wxpaynotify','Finance\RechargeController@wxpaynotify');//微信充值回调
        Route::any('/finance/recharge/ajax_check_recharge','Finance\RechargeController@ajax_check_recharge');//微信充值成功返回页面
        //账户充值

        //订购作品
        Route::any('/works/alipaynotify','Works\WorksController@alipaynotify');//支付宝充值回调
        Route::any('/works/alipayreturn','Works\WorksController@alipayreturn');//支付宝充值成功返回页面

        Route::any('/works/wxpaynotify','Works\WorksController@wxpaynotify');//微信充值回调
        Route::any('/works/ajax_check_recharge','Works\WorksController@ajax_check_recharge');//微信充值成功返回页面
        //订购作品

        //文章 start
        Route::get('/articles', 'ArticlesController@index');
        Route::get('/articles/detail', 'ArticlesController@detail');
        //文章 end

        //diy在线制作助手 start
        Route::get('/diy_assistant', 'DiyAssistantController@index');
        Route::post('/diy_assistant/table', 'DiyAssistantController@table');
        Route::post('/diy_assistant/works_operate', 'DiyAssistantController@workOperate');
        Route::get('/works/diy_delete/{id}', 'DiyAssistantController@workDelete');//删除作品
        Route::post('/diy_assistant/change_works_num', 'DiyAssistantController@changeWorksNum');//修改作品数量
        //diy在线制作助手 end


        //模板市场 start
        Route::get('/goods/detail/template', 'Goods\DetailController@template');//模板市场页面
        Route::post('/goods/get_template', 'Goods\DetailController@get_template');//模板搜索
        Route::post('/goods/get_m_template', 'Goods\DetailController@get_m_template');//手机模板搜索
        Route::get('/t/{sku_id}/{agent_id}', 'Goods\DetailController@template');//模板市场页面

        //模板市场 end

        //商业模板市场 start
        Route::get('/goods/detail/comltemplate', 'Goods\DetailController@comltemplate');//模板市场页面
        Route::post('/goods/detail/getComlTemplate', 'Goods\DetailController@getComlTemplate');//模板搜索
        Route::post('/goods/get_m_template', 'Goods\DetailController@get_m_template');//手机模板搜索
        Route::get('/ct/{sku_id}/{agent_id}', 'Goods\DetailController@comltemplate');//商业模板市场页面
        //商业模板市场 end

        //商业印刷作品成功页
        Route::get('/index/comlSubsuc', 'IndexController@comlWorkSuccess');

        //產品市场 start
        Route::get('/index/products', 'IndexController@products'); //產品
        //產品市场 end


        //稿件上传 start
        Route::get('/goods/detail/work_upload', 'Goods\DetailController@work_upload');//稿件上传页面
        Route::post('/goods/fileUpload', 'Goods\DetailController@fileUpload');//稿件上传
        Route::post('/goods/filesave', 'Goods\DetailController@filesave');//稿件上传
        Route::post('/goods/delete_pdf', 'Goods\DetailController@delete_pdf');//pdf删除
        Route::post('/goods/checkPage', 'Goods\DetailController@checkPage');//检验稿件是否符合数量要求
        //稿件上传 end

        Route::middleware('agent.auth')->group(function() {
            //退出登录 start
            Route::get('dashboard/logout', 'LoginController@logOut');
            //退出登录 end

            //dashboard 模块 start
            Route::get('dashboard', 'DashboardController@index');
            Route::post('dashboard/chart', 'DashboardController@getChartData');//控制台图表数据

            //弹出层组件测试 start
            Route::get('/hlt', 'TestController@hlt');
            Route::get('/tips', 'TestController@tips');
            Route::get('/step', 'TestController@step');
            //弹出层组件测试 end

            //dashboard 模块 end

            //公共面包屑提示操作信息
            Route::post('/getRuleRemarkAndBcrumb', 'BaseController@getRuleRemarkAndBcrumb'); //面包屑，提示信息

            //demo 模块 start
            Route::get('demo', 'DemoController@index'); //列表
            Route::post('demo/list', 'DemoController@table'); //记录
            Route::post('demo/add', 'DemoController@add'); //添加
            Route::get('demo/form', 'DemoController@form'); //添加
            Route::post('demo/save', 'DemoController@save'); //添加

            Route::get('test', 'TestController@index'); //列表
            Route::post('test/list', 'TestController@table'); //记录
            Route::get('test/form', 'TestController@form'); //添加
            Route::post('test/save', 'TestController@save'); //添加
            Route::get('test/del', 'TestController@delete'); //添加

            Route::get('test/media', 'test\MediaController@index'); //列表
            Route::post('test/media/list', 'test\MediaController@table'); //记录
            Route::get('test/media/form', 'test\MediaController@form'); //添加
            Route::post('test/media/save', 'test\MediaController@save'); //添加
            Route::get('test/media/del', 'test\MediaController@delete'); //添加

            //demo 模块 end

            //商品模块 start
            Route::get('/goods/list/index', 'Goods\ListController@index');//商品列表
            Route::post('/goods/collect', 'Goods\ListController@collect');//收藏商品
            Route::get('/goods/category/index/{category_id}', 'Goods\CategoryController@table');//商品分类
            Route::get('/goods/detail/index/{product_id}', 'Goods\DetailController@table');//商品详情
            Route::post('/goods/detail/getPrice', 'Goods\DetailController@getPrice');//商品详情
            Route::post('/goods/detail/tips', 'Goods\DetailController@tips');//制作链接
            Route::post('/goods/category/searchgoods', 'Goods\CategoryController@searchGoods');//搜索商品

            Route::post('/goods/shoppingCart', 'Goods\DetailController@shoppingCart');//稿件上传加入购物车
            Route::post('/goods/orderCreate', 'Goods\DetailController@orderCreate');//稿件上传立即订购


            Route::post('/goods/detail/add_cart', 'Goods\DetailController@addCart');

            Route::get('/goods/collect/index', 'Goods\CollectController@index');//我的收藏
            //商品模块 end

            //消息管理模块 start
            Route::get('/news', 'News\ListsController@index'); //列表
            Route::post('/news/list', 'News\ListsController@table'); //列表数据加载
            Route::get('/news/detail', 'News\ListsController@detail'); //详情
            //消息管理模块 end

            //orders 模块 start
            Route::get('order/list', 'Orders\ListsController@index'); //订单列表
            Route::get('order/cancel/{id}', 'Orders\ListsController@cancelOrder'); //取消订单
            Route::post('order/list/list', 'Orders\ListsController@table'); //记录
            Route::any('order/list/tag/{id}', 'Orders\ListsController@orderTag'); //订单标签
            Route::get('order/detail/{id}','Orders\ListsController@detail');//订单详情
            Route::post('order/receiver','Orders\ListsController@confirmReceiver');//确认收货
            Route::any('order/list/check/{number}', 'Orders\ListsController@checkFile'); //审核文件
            Route::post('order/list/reload', 'Orders\ListsController@reloadImg'); //重新出图
            Route::any('order/list/download_check', 'Orders\ListsController@downloadCheck'); //下载前检查文件
            Route::any('order/list/download', 'Orders\ListsController@downloadFile'); //下载文件
            Route::any('create/get_create_delivery_price','Orders\ListsController@getCreateDeliveryPrice');//创建订单获取物流方式与运费
            Route::any('order/list/logistics/{id}', 'Orders\ListsController@logistics');//物流信息


            Route::get('order/service', 'Orders\AfterSalesController@index');//售后订单
            Route::post('order/service/list', 'Orders\AfterSalesController@table');//售后table
            Route::get('order/service/form', 'Orders\AfterSalesController@form');//添加
            Route::post('order/service/save', 'Orders\AfterSalesController@save'); //保存
            Route::post('order/service/get_amount', 'Orders\AfterSalesController@getAmount'); //获取订单金额
            Route::get('order/service/del/{id}', 'Orders\AfterSalesController@delete');//删除
            Route::get('order/service/withdraw/{id}', 'Orders\AfterSalesController@withdraw');//售后撤回
            Route::post('order/order_goods', 'Orders\AfterSalesController@orderGoods');

            Route::any('/orders/add_cart_goods','Orders\CartsController@addCartGoods');//添加购物车
            Route::get('/orders/cart','Orders\CartsController@index');//购物车
            Route::post('/orders/shopping_cart','Orders\CartsController@table');//购物车列表
            Route::post('/orders/shopping_cart_num','Orders\CartsController@changeCartGoodsNum');//更改购物车商品数量
            Route::any('/orders/del_cart_goods','Orders\CartsController@delCartGoods');//删除购物车商品
            Route::post('/orders/collect_cart_goods','Orders\CartsController@collectCartGoods');//收藏购物车商品
            Route::any('/orders/batch_del_cart_goods','Orders\CartsController@batchDelCartGoods');//批量删除购物车商品



            Route::get('orders/create', 'Orders\ListsController@create');//提交订单
            Route::post('create/carete_order','Orders\ListsController@orderCreate');//创建订单

            Route::get('/order/test', 'orders\ListsController@test');//弹窗默认页面
            Route::get('/order/del/{order_id}', 'orders\ListsController@del');//删除测试方法
            Route::get('/orders/manage_address/{address_id}','orders\AddressController@index');//管理收货地址
            Route::post('/orders/manage_address/table','orders\AddressController@table');//购物车列表
            //orders 模块 end


            //管理员列表模块 start
            Route::get('auth/admin','Auth\AdminController@index');//管理员列表
            Route::post('auth/admin/list', 'Auth\AdminController@table'); //表格
            Route::get('auth/admin/form', 'Auth\AdminController@form');//管理员添加
            Route::get('auth/admin/del/{id}', 'Auth\AdminController@delete'); //删除
            Route::post('auth/admin/save', 'Auth\AdminController@save'); //保存、更新
            //管理员列表模块 end


            //管理员日志模块 start
            Route::get('daily','Auth\DailyController@index');//日志列表
            Route::post('daily/list','Auth\DailyController@table');//操作记录
            Route::get('daily/detail','Auth\DailyController@detail');//操作详情
            //管理员日志模块 end

            //角色组模块 start
            Route::get('auth/rule','Auth\RuleController@index');//角色列表
            Route::post('auth/rule/list','Auth\RuleController@table');//记录
            Route::get('auth/rule/form', 'Auth\RuleController@form');//角色添加
            Route::get('auth/rule/del/{id}', 'Auth\RuleController@delete'); //删除
            Route::post('auth/rule/save', 'Auth\RuleController@save'); //保存、更新
            //角色组模块 end

            //系统设置 密码管理 start
            Route::get('system/index','SystemController@index');//密码管理列表
            Route::get('/system/basic_info','SystemController@basicInfo');//基本信息
            Route::post('/system/baseSave','SystemController@baseSave');//基本信息修改
            Route::get('/system/pwd_management','SystemController@pwdManagement');//密码管理
            Route::post('/system/pwdSave','SystemController@pwdSave');//密码保存
            Route::post('/system/getCode','SystemController@getCode');//验证码获取
            //系统设置 密码管理 end


            //works 模块 start
            Route::get('works', 'Works\WorksController@index');//作品管理
            Route::post('/works/list', 'Works\WorksController@table');//作品列表记录
            Route::get('/works/remarks', 'Works\WorksController@remarks');//标签作品
            Route::post('/works/labelSave', 'Works\WorksController@labelSave'); //保存、更新
            Route::get('/works/log', 'Works\WorksController@log');//作品操作日志
            Route::get('/works/edit', 'Works\WorksController@edit');//修改作品
            Route::post('/works/save', 'Works\WorksController@save'); //保存、更新
            Route::get('/works/clone_works', 'Works\WorksController@cloneWorks');//克隆作品页面
            Route::post('/works/clone', 'Works\WorksController@worksClone');//克隆作品
            Route::get('/works/delete/{id}', 'Works\WorksController@delete');//删除作品
            Route::get('/works/delIds', 'Works\WorksController@deleteIds');//批量删除作品
            Route::get('/works/regain', 'Works\WorksController@regain');//恢复作品
            Route::get('/works/review', 'Works\WorksController@review');//恢复作品
            Route::get('/works/order', 'Works\WorksController@order');//订购作品
            Route::get('/works/orderSave', 'Works\WorksController@orderSave');//订购作品
            Route::post('/works/getPrice', 'Works\WorksController@getPrice');//订购作品
            Route::post('/works/statusCount', 'Works\WorksController@statusCount');//统计作品状态
            Route::get('/works/ajaxtongbu', 'Works\WorksController@ajaxtongbu');//同步订单
            Route::post('/works/outerNo', 'Works\WorksController@outerNo');//同步订单
            Route::get('/works/makeUrl', 'Works\WorksController@makeUrl');//制作链接
            Route::get('/works/error', 'Works\WorksController@projectsError');//查看异常
            Route::post('/works/checkPayword', 'Works\WorksController@checkPayword');//支付密码验证
            //works 模块 end

            //财务统计 start
            Route::get('/finance/accounts/index', 'Finance\AccountsController@index');//资金账务
            Route::post('/finance/accounts/remind_status', 'Finance\AccountsController@remindStatus');//资金账务
            //账户充值 start
            Route::get('/finance/recharge/index', 'Finance\RechargeController@index');//账户充值
            Route::get('/finance/recharge/pay','Finance\RechargeController@pay');//账户充值（即时到账）
            Route::post('/finance/recharge/offline_pay','Finance\RechargeController@offlinePay');//账户充值（即时到账）
            //账户充值 end
            Route::any('/finance/accountrecharge/index', 'Finance\AccountRechargeController@index');//账户充值(表格)
            Route::any('/finance/accountrecharge/table', 'Finance\AccountRechargeController@table');//账户充值(表格数据)
            Route::get('/finance/accountrecharge/info', 'Finance\AccountRechargeController@form');//账户充值(详情页)
            Route::get('/finance/accountrecharge/cancel', 'Finance\AccountRechargeController@cancel');//账户充值(取消操作)
            Route::get('/finance/sales_analysis/index', 'Finance\SalesAnalysisController@index');//销售分析
            Route::get('/finance/sales_analysis/sales', 'Finance\SalesAnalysisController@salesAnalysis');//销售分析
            Route::get('/finance/order/index', 'Finance\SalesAnalysisController@orderStatistics');//订单统计
            Route::post('/finance/order/table', 'Finance\SalesAnalysisController@orderTable');//订单统计
            Route::get('/finance/order/form', 'Finance\SalesAnalysisController@orderForm');//订单统计
            Route::get('/finance/orders/ordersExport', 'Finance\FileExportController@ordersExport');//订单统计导出
            Route::get('/finance/goods/index', 'Finance\SalesAnalysisController@goodsStatistics');//商品统计列表
            Route::post('/finance/goods/table', 'Finance\SalesAnalysisController@goodsTable');//商品统计数据
            Route::get('/finance/goods/goodsExport', 'Finance\FileExportController@goodsExport');//商品统计导出
            Route::get('/finance/areas/index', 'Finance\SalesAnalysisController@areasStatistics');//地区统计列表
            Route::post('/finance/areas/table', 'Finance\SalesAnalysisController@areasTable');//地区统计数据
            Route::get('/finance/areas/areasExport', 'Finance\FileExportController@areasExport');//地区统计导出
            Route::get('/finance/logistics/index', 'Finance\SalesAnalysisController@logisticsStatistics');//物流统计列表
            Route::post('/finance/logistics/table', 'Finance\SalesAnalysisController@logisticsTable');//物流统计数据
            Route::get('/finance/logistics/logisticsExport', 'Finance\FileExportController@logisticsExport');//物流统计导出
            Route::get('/finance/logisticsDetail/index', 'Finance\SalesAnalysisController@logisticsDetailStatistics');//物流明细统计列表
            Route::post('/finance/logisticsDetail/table', 'Finance\SalesAnalysisController@logisticsDetailTable');//物流明细统计数据
            Route::get('/finance/logisticsDetail/logisticsDetailExport', 'Finance\FileExportController@logisticsDetailExport');//物流明细统计导出

            Route::get('finance/fund', 'Finance\FundController@index');//资金明细
            Route::post('finance/fund/list', 'Finance\FundController@table');//资金列表
            Route::get('finance/fund/form', 'Finance\FundController@form');//详情
            Route::any('finance/fund/export/{data}', 'Finance\FundController@fundExport');//资金明细导出

            //财务统计 end

            //推广管理模块 start
            Route::get('extension/pomoters','Extension\PomotersController@index');//推广列表
            Route::post('extension/pomoters/list','Extension\PomotersController@table');//表格
            Route::get('extension/pomoters/form', 'Extension\PomotersController@form');//添加
            Route::get('extension/pomoters/del/{id}', 'Extension\PomotersController@delete'); //删除
            Route::post('extension/pomoters/save', 'Extension\PomotersController@save'); //保存、更新
            //推广管理模块 end

        });


    });
});
