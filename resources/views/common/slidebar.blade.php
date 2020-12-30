<aside class="main-sidebar sidebar-dark-primary elevation-4 menu-color" style="">
    <!-- Brand Logo -->
    <a class="brand-link logo-color" style="text-align:center;">
        <!--<img src="{{URL::asset('images/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">-->
        <span class="brand-text font-weight-light">
            @if($systemName == 'dms')
                @if(isset($deployInfo['deploy_sitename'])){{$deployInfo['deploy_sitename']}}@elseif(isset($systemInfo['dms_name'])){{$systemInfo['dms_name']}}@else{{ $system }}管理平台@endif
            @elseif($systemName == 'cms')
                @if(isset($systemInfo['cms_name'])){{$systemInfo['cms_name']}}@else{{ $system }}管理平台@endif
            @elseif($systemName == 'oms')
                @if(isset($omsSystemInfo['oms_name'])){{$omsSystemInfo['oms_name']}}@elseif(isset($systemInfo['oms_name'])){{$systemInfo['oms_name']}}@else{{$system }}管理平台@endif
            @else
                {{ $system }}管理平台
            @endif
        </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar" style="overflow-y: hidden">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 mb-3 d-flex" style="border-bottom: none;padding-bottom:0px">
            <div class="pub_version" style="">
                v1.0 <span class="beta-txt">beta</span>
            </div>
        </div>


        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                @if ($systemName == 'dms')
                    @if(isset($menuList))
                        {!! $menuList !!}
                        {{--<li class="nav-item has-treeview menu-open">--}}
                            {{--<a href="javascript:void(0);" data-id="@id" data-url="/dashboard" class="nav-link menu-link active menu-default">--}}
                                {{--<i class="nav-icon fa fa-dashboard"></i>--}}
                                {{--<p>控制台</p>--}}
                            {{--</a>--}}
                        {{--</li>--}}
                        {{--<li class="nav-item has-treeview">--}}
                            {{--<a href="javascript:void(0);" data-id="@id" data-url="/test" class="nav-link menu-link menu-default">--}}
                                {{--<i class="nav-icon fa fa-user-circle"></i>--}}
                                {{--<p>测试</p>--}}
                            {{--</a>--}}
                        {{--</li>--}}
                        {{--<li class="nav-item has-treeview">--}}
                            {{--<a href="javascript:void(0);" data-id="@id" data-url=" " class="nav-link">--}}
                                {{--<i class="nav-icon fa fa-users"></i>--}}
                                {{--<p> 权限管理<i class="fa fa-angle-left right fa-lg"></i></p>--}}
                            {{--</a>--}}
                            {{--<ul class="nav nav-treeview menu-lv-1">--}}
                                {{--<li class="nav-item has-treeview">--}}
                                    {{--<a href="javascript:void(0);" data-id="@id" data-url="/auth/admin" class="nav-link menu-link">--}}
                                        {{--<i class="nav-icon "></i> <p>管理员列表</p>--}}
                                    {{--</a>--}}
                                {{--</li>--}}
                                {{--<li class="nav-item has-treeview">--}}
                                    {{--<a href="javascript:void(0);" data-id="@id" data-url="/auth/rule" class="nav-link menu-link">--}}
                                        {{--<i class="nav-icon "></i> <p>角色管理</p>--}}
                                    {{--</a>--}}
                                {{--</li>--}}
                            {{--</ul>--}}
                        {{--</li>--}}
                    @else
                        <li class="nav-item has-treeview menu-open">
                            <a href="javascript:void(0);" class="nav-link menu-link active menu-default" data-url="/dashboard" data-id="1">
                                <i class="nav-icon fa fa-dashboard"></i>
                                <p>
                                    控制台
                                </p>
                            </a>

                        </li>
                        <li class="nav-item has-treeview">
                            <a href="javascript:void(0);" class="nav-link menu-link menu-default" data-url="/demo" data-id="1">
                                <i class="nav-icon fa fa-star"></i>
                                <p>
                                    示例参考
                                </p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa fa-lock"></i>
                                <p>
                                    权限管理
                                    <i class="fa fa-angle-left right fa-lg"></i>
                                    <!--<span class="badge badge-info right">6</span>-->
                                </p>
                            </a>
                            <ul class="nav nav-treeview menu-lv-1">
                                <li class="nav-item ">
                                    <a href="javascript:void(0);" class="nav-link menu-link" data-url="/admin">
                                        <p>管理员列表</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="javascript:void(0);" class="nav-link menu-link" data-url="/daily">
                                        <p>管理员日志</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a  href="#" class="nav-link menu-link" data-url="/rule">
                                        <p>角色管理</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="javascript:void(0);" class="nav-link">
                                <i class="nav-icon fa fa-cog"></i>
                                <p>
                                    系统设置
                                    <i class="fa fa-angle-left right fa-lg"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview menu-lv-1">
                                <li class="nav-item ">
                                    <a href="javascript:void(0);" class="nav-link menu-link" data-url="/system">
                                        <p>系统设置</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa fa-shopping-bag"></i>
                                <p>
                                    商品管理
                                    <i class="fa fa-angle-left right fa-lg"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview menu-lv-1">
                                <li class="nav-item">
                                    <a href="javascript:void(0);" class="nav-link menu-link" data-url="/goods/list/index">
                                        <p>商品列表</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="javascript:void(0);" class="nav-link menu-link" data-url="/goods/category/index/666">
                                        <p>商品分类</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link menu-link" data-url="/goods/detail/index/666">
                                        <p>商品详情</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa fa-calendar-o"></i>
                                <p>
                                    作品管理
                                    <i class="fa fa-angle-left right fa-lg"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview menu-lv-1">
                                <li class="nav-item">
                                    <a href="javascript:void(0);" class="nav-link menu-link" data-url="/works">
                                        <p>作品列表</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa fa-list-ul"></i>
                                <p>
                                    订单管理
                                    <i class="fa fa-angle-left right fa-lg"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview menu-lv-1">
                                <li class="nav-item">
                                    <a href="javascript:void(0);" class="nav-link menu-link" data-url="/orders">
                                        <p>订单列表</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="javascript:void(0);" class="nav-link menu-link" data-url="/orders/cart">
                                        <p>购物车</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="javascript:void(0);" class="nav-link menu-link" data-url="/orders/create">
                                        <p>提交订单</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="javascript:void(0);" class="nav-link menu-link" data-url="/orders/detail/5">
                                        <p>订单详情</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="javascript:void(0);" class="nav-link menu-link" data-url="/orders/aftersales">
                                        <p>售后订单</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="javascript:void(0);" class="nav-link menu-link" data-url="/orders/manage_address/123">
                                        <p>管理收货地址</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa fa-area-chart"></i>
                                <p>
                                    财务统计
                                    <i class="fa fa-angle-left right fa-lg"></i>
                                </p>
                            </a>
                            <ul class="menu-lv-1 nav nav-treeview">
                                <li class="nav-item">
                                    <a href="javascript:void(0);" class="nav-link menu-link" data-url="/finance/accounts/index">
                                        <p>资金账务</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="javascript:void(0);" class="nav-link menu-link" data-url="/finance/recharge/index">
                                        <p>账户充值</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="javascript:void(0);" class="nav-link menu-link" data-url="/finance/accountrecharge/index">
                                        <p>充值记录</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="javascript:void(0);" class="nav-link menu-link" data-url="/finance/fund/index">
                                        <p>资金明细</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="javascript:void(0);" class="nav-link menu-link" data-url="/finance/sales_analysis/index">
                                        <p>销售分析</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="javascript:void(0);" class="nav-link menu-link menu-default" data-url="/news" data-id="1">
                                <i class="nav-icon fa fa-envelope"></i>
                                <p>
                                    消息管理
                                </p>
                            </a>
                        </li>
                    @endif
                @elseif($systemName == 'erp')
                    <li class="nav-item has-treeview menu-open">
                        <a href="javascript:void(0);" class="nav-link menu-link active menu-default" data-url="/dashboard" data-id="1">
                            <i class="nav-icon fa fa-dashboard"></i>
                            <p>
                                控制台
                            </p>
                        </a>
                    </li>
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fa fa-university"></i>
                            <p>
                                资金管理
                                <i class="fa fa-angle-left right fa-lg"></i>
                                <!--<span class="badge badge-info right">6</span>-->
                            </p>
                        </a>
                        <ul class="nav nav-treeview menu-lv-1">
                            <li class="nav-item ">
                                <a href="javascript:void(0);" class="nav-link menu-link" data-url="finance/record">
                                    <p>充值记录</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a  href="javascript:void(0);" class="nav-link menu-link" data-url="finance/recharge">
                                    <p>账户充值</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fa fa-area-chart"></i>
                            <p>
                                对账管理
                                <i class="fa fa-angle-left right fa-lg"></i>
                                <!--<span class="badge badge-info right">6</span>-->
                            </p>
                        </a>
                        <ul class="nav nav-treeview menu-lv-1">
                            <li class="nav-item ">
                                <a href="javascript:void(0);" class="nav-link menu-link" data-url="/reconciliation/bill">
                                    <p>客户对账单</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fa fa-list-ul"></i>
                            <p>
                                订单管理
                                <i class="fa fa-angle-left right fa-lg"></i>
                                <!--<span class="badge badge-info right">6</span>-->
                            </p>
                        </a>
                        <ul class="nav nav-treeview menu-lv-1">
                            <li class="nav-item ">
                                <a href="javascript:void(0);" class="nav-link menu-link" data-url="/orders">
                                    <p>订单列表</p>
                                </a>
                            </li>
                            @if($allow_import == 1)
                            <li class="nav-item ">
                                <a href="javascript:void(0);" class="nav-link menu-link" data-url="/import">
                                    <p>业务数据导入</p>
                                </a>
                            </li>
                            @endif
                        </ul>

                    </li>
                @elseif($systemName == 'cms')
                    {!! $menuList !!}
                @elseif($systemName == 'oms')
                    {!! $menuList !!}
                @else
                    @if(isset($menuList))
                        {!! $menuList !!}
                    @else
                        <li class="nav-item has-treeview menu-open">
                            <a href="javascript:void(0);" class="nav-link menu-link active menu-default" data-url="/dashboard" data-id="1">
                                <i class="nav-icon fa fa-dashboard"></i>
                                <p>
                                    控制台
                                </p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa fa-area-chart"></i>
                                <p>
                                    对账管理
                                    <i class="fa fa-angle-left right fa-lg"></i>
                                    <!--<span class="badge badge-info right">6</span>-->
                                </p>
                            </a>
                            <ul class="nav nav-treeview menu-lv-1">
                                <li class="nav-item ">
                                    <a href="javascript:void(0);" class="nav-link menu-link" data-url="reconciliation/bill">
                                        <p>客户对账单</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa fa-lock"></i>
                                <p>
                                    权限管理
                                    <i class="fa fa-angle-left right fa-lg"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview menu-lv-1">
                                <li class="nav-item ">
                                    <a href="javascript:void(0);" class="nav-link menu-link" data-url="auth/rule">
                                        <p>菜单管理</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a  href="javascript:void(0);" class="nav-link menu-link" data-url="auth/kfusers/index">
                                        <p>管理员列表</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a  href="#" class="nav-link menu-link" data-url="/auth/group">
                                        <p>角色管理</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                @endif

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
