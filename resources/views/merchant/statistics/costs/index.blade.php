<!DOCTYPE html>
<link rel="stylesheet" href="{{ URL::asset('css/agent/finance/finance.css') }}">
@extends('layout.mch_iframe')

@section("main-content")
    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 数据管理 / 销售成本统计' ])
    @endcomponent
    <!-- 面包屑组件end  -->
<div id="main">
        <!--  提示组件 start -->
        @component('component/tips')
            {{--<p class="accounts-two">订单发货统计是统计订单中状态为已发货的订单</p>--}}
    @endcomponent
    <!--  查询 start -->
    <div class="" style="margin-top:20px">
        @include('merchant.statistics.costs._search',['chaList'=>$chaList])
    </div>
    <!--  查询 end -->

    <!--  操作按钮 start -->
    <div id="act-btn" style="margin:20px 0 10px 0;display: flex;justify-content: space-between;">
        <button class="btn btn-write btn-refresh"><i class="fa fa-refresh"></i> 刷新</button>
        <button id="costs_export" class="btn btn-write btn-undo" style="margin-right: 0"><i class="fa fa-download"></i> 导出</button>
    </div>
    <!--  操作按钮 end -->

    <!-- 列表start  -->
    <div id="table">
        <table class="no-border-table" data-url="/statistics/costs/list">
            <thead>
            <tr class="table-head">
                {{--<td class="finance_order_checkbox_first_td">--}}
                    {{--@component('component/checkbox',['checkbox'=>['全选'],'name'=>['checkall'],'custom_class'=>"checkall checkall1 checkbox",'right_distance'=>10])--}}
                    {{--@endcomponent--}}
                {{--</td>--}}
                <td style="width: 7%">订单号</td>
                <td style="width: 7%">项目号</td>
                <td style="width: 5%">快递方式</td>
                <td style="width: 7%">物流单号</td>
                <td style="width: 5%">配送区域</td>
                <td style="width: 5%">商品名称</td>
                <td style="width: 4%">货号</td>
                <td style="width: 4%">工厂编号</td>
                <td style="width: 5%">属性</td>
                <td style="width: 3%">数量</td>
                <td style="width: 4%">单位成本</td>
                <td style="width: 3%">张数</td>
                {{--<td style="width: 3%">运费</td>--}}
                <td style="width: 5%">成本小计</td>
                <td style="width: 5%">发货时间</td>
                <td style="width: 5%">下单时间</td>
                <td style="width: 5%">供货商</td>
                <td style="width: 5%">渠道</td>
                <td style="width: 5%">店铺来源</td>
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
<!-- 列表end    -->
@endsection
<!---  引入当前页需使用的js  -->
@section("pages-js")

@endsection
@section("js-file")
    <script src="{{ URL::asset('js/merchant/statistics/index.js')}}"></script>
@endsection
