<!doctype html>
@extends('layout.iframe')

@section("main-content")
    <link rel="stylesheet" href="{{ URL::asset('css/agent/orders/orders.css') }}?r=1.0">
    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 订单管理 / 订单列表' ])
    @endcomponent
    <!-- 面包屑组件end  -->


    <div id="main" style="padding:25px">
        <!--  提示组件 start -->
        @component('component/tips')
            {{--<p style="margin:5px 0 ;padding:0">订单列表中包括各种状态下的交易记录信息，可针对不同状态下订单进行处理。</p>--}}
        @endcomponent
        <!--  提示组件 end -->

        <!-- 搜索功能 start  -->
        <div class="" style="margin-top:20px">
            @include('agent.orders.list._search')
        </div>
        <!--  提示功能 end -->
        <!-- 操作按钮 start  -->
        <div id="act-btn" style="padding:20px 0">
            <input type="checkbox" class="checkall checkall1 checkbox all-checkall" id="all" data-id="1"><label for="all" style="margin-right: 10px"></label><span class="o_checkall-text">全选/反选</span>
{{--            <button class="btn btn-write btn-dialog"  data-url="{{URL::asset('/order/test')}}" data-title="批量发货">批量发货</button>--}}
            <button class="btn btn-write btn-batch-sign" data-title="批量标记">批量标记</button>
            <button class="btn btn-write btn-sign btn-dialog" style="display: none" data-title="批量标记"></button>
        </div>
        <!-- 操作按钮 end  -->

        <!-- 列表start  -->
        <div id="table">
            <table class="no-border-table" data-url="/order/list/list">
                <thead>
                <tr class="table-head">
                    <td>商品</td>
                    <td>单价</td>
                    <td>数量</td>
                    <td>生产状态</td>
                    <td>订单状态</td>
                    <td>实收款</td>
                    <td>标签</td>
                    <td>操作</td>
                </tr>
                </thead>
                <tbody class="tbl-content">

                </tbody>
            </table>

            <div id="act-btn" style=" margin: 3px 0 0 3px;float: left;">
                <input type="checkbox" class="checkall checkall3 checkbox all-checkall" id="all2" data-id="3">
                <label for="all2" style="margin-right: 10px"></label>
                <span class="o_checkall-text">全选/反选</span>
{{--                <button class="btn btn-write btn-dialog"  data-url="{{URL::asset('/order/test')}}" data-title = "批量发货">批量发货</button>--}}
                <button class="btn btn-write btn-batch-sign" data-title="批量标记">批量标记</button>
            </div>

            @component('component/paginate',['limit' => \Config::get('pageLimit')])

            @endcomponent

        </div>
        <!-- 列表end    -->
    </div>
@endsection
@section("js-file")
    <!-- AdminLTE App -->
    <script src="{{ URL::asset('js/agent/orders/orders.js')}}"></script>



@endsection






