<!doctype html>
@extends('layout.factory_iframe')

@section("main-content")
    <link rel="stylesheet" href="{{URL::asset('css/factory/order/list.css')}}">
    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 订单管理  /  订单列表' ])
    @endcomponent
    <!-- 面包屑组件end  -->

    <div id="main">

        <!--  提示组件 start -->
        @component('component/tips')
            <p style="margin:5px 0 ;padding:0">订单列表中包括各种状态下的交易记录信息，可针对不同状态下订单进行处理。</p>
        @endcomponent
        <!--  提示组件 end -->

        <!-- 搜索功能 start  -->
        <div class="" style="margin-top:20px">
            @include('factory.order.list._search')
        </div>
        <!--  搜索功能 end -->

        <!-- tab状态按钮 start  -->
        <div class="nav_tab">
            @component('component.navOperateTab',['navlist'=>['ALL'=>'全部('.intval($statusCount[0]).')','ORDER_NO_PRODUCE'=>'未生产('.intval($statusCount[1]).')','ORDER_PRODUCING'=>'生产中('.intval($statusCount[2]).')','ORDER_SHIPPED'=>'已发货('.intval($statusCount[3]).')'],'extendClass'=>'works_tab'])
            @endcomponent
            <input type="hidden" class="tab_val" value="ALL">
        </div>
        <!-- tab状态按钮 end  -->


        <!-- 列表start  -->
        <div id="table">
            <table class="no-border-table" data-url="/order/list/list">
                <thead>
                <tr class="table-head">
                    <td>商品</td>
                    <td>单价</td>
                    <td>数量</td>
                    <td>实收款</td>
                    <td>收件人信息</td>
                    <td>生产状态</td>
                    <td>订单状态</td>
                    <td>下载状态</td>
                    <td>操作</td>
                </tr>
                </thead>
                <tbody class="tbl-content">

                </tbody>
            </table>

            @component('component/paginate',['limit' => \Config::get('pageLimit')])

            @endcomponent

        </div>
        <!--  列表 end -->

    </div>



@endsection
<!--  加载js -->
@section("js-file")
    <script src="{{ URL::asset('js/factory/order/list.js')}}"></script>
@endsection







