@extends('layout.mch_iframe')

@section("main-content")
    <link rel="stylesheet" href="{{URL::asset('css/merchant/order/list.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('assets/daterangepicker/daterangepicker.css') }}">


    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 订单管理 / 换货单列表' ])
    @endcomponent
    <!-- 面包屑组件end  -->


    <div id="main" style="padding:15px">
        <!--  提示组件 start -->
        @component('component/tips')
            {{--<p style="margin:5px 0 ;padding:0">订单详情记录订单的基本信息、收货人信息、商品信息、费用信息等。订单分5个大流程分别是提交、支付、发货、收货及评价。</p>--}}
            {{--<p style="margin:5px 0;padding:0">特殊类目订单由于行业特性可能有其它支流程，且该类订单商品拥有特殊子项目信息，可点击展开键查看特殊信息。</p>--}}
            {{--<p style="margin:5px 0;padding:0">分销类商家提交成功的订单是无法修改的（系统会自动确认执行后续处理流程），只能联系商家进行修改。订单售后问题需要确认收货后才能申请售后。</p>--}}
        @endcomponent
        <!--  提示组件 end -->

        <!-- 搜索功能 start  -->
        <div class="" style="margin-top:20px">
            @include('merchant.order.exchange._search')
        </div>
        <!--  提示功能 end -->

        <!-- 操作按钮 start  -->
        <div id="act-btn" style="padding:20px 0">
            <button class="btn btn-write btn-refresh"><i class="fa fa-refresh"></i> 刷新</button>
        </div>
        <!-- 操作按钮 end  -->

        <!-- 列表start  -->
        <div id="table">
            <table class="no-border-table" data-url="/order/exchange/list">
                <thead>
                <tr class="table-head">
                    <td>原订单号</td>
                    <td>换货单号</td>
                    <td>换货原因</td>
                    <td>换货说明</td>
                    <td>操作人</td>
                    <td>创建时间</td>
                    <td>操作</td>
                </tr>
                </thead>
                <tbody class="tbl-content">

                </tbody>
            </table>
            @component('component/paginate',['limit' => \Config::get('pageLimit')])
            @endcomponent

        </div>
        <!-- 列表end    -->
    </div>


@endsection
@section("js-file")
    <!-- AdminLTE App -->
    <script src="{{ URL::asset('assets/daterangepicker/moment.min.js')}}"></script>
    <script src="{{ URL::asset('assets/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{ URL::asset('js/merchant/order/list.js')}}"></script>
@endsection
