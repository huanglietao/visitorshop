<!DOCTYPE html>
@extends('layout.iframe')

@section("main-content")
    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 数据管理 / 利润统计' ])
    @endcomponent
    <!-- 面包屑组件end  -->
<div id="main">
        <!--  提示组件 start -->
        @component('component/tips')
            {{--<p class="accounts-two">利润统计是统计每一个已完成的订单中的利润</p>--}}
    @endcomponent
    <!--  查询 start -->
    <div class="" style="margin-top:20px">
        @include('backend.statistics.profit._search',['InfoList'=>$InfoList])
    </div>
    <!--  查询 end -->

    <!--  操作按钮 start -->
    <div id="act-btn" style="margin:20px 0 10px 0;display: flex;justify-content: space-between;">
        <button class="btn btn-write btn-refresh"><i class="fa fa-refresh"></i> 刷新</button>
        <button id="profit_export" class="btn btn-write btn-undo" style="margin-right: 0"><i class="fa fa-download"></i> 导出</button>
    </div>
    <!--  操作按钮 end -->

    <!-- 列表start  -->
    <div id="table">
        <table class="no-border-table" data-url="/statistics/profit/list">
            <thead>
            <tr class="table-head">
                {{--<td class="finance_order_checkbox_first_td">--}}
                    {{--@component('component/checkbox',['checkbox'=>['全选'],'name'=>['checkall'],'custom_class'=>"checkall checkall1 checkbox",'right_distance'=>10])--}}
                    {{--@endcomponent--}}
                {{--</td>--}}
                <td style="width: 10%">订单号</td>
                <td style="width: 10%">下单日期</td>
                <td style="width: 10%">发货日期</td>
                <td style="width: 5%">商品数量</td>
                <td style="width: 5%">商品总额</td>
                <td style="width: 5%">配送费用</td>
                <td style="width: 5%">优惠金额</td>
                <td style="width: 5%">已支付金额</td>
                <td style="width: 5%">商品成本</td>
                <td style="width: 5%">物流成本</td>
                <td style="width: 5%">成本小计</td>
                <td style="width: 5%">利润</td>
                <td style="width: 5%">毛利率</td>
                <td style="width: 8%">店铺来源</td>
                <td style="width: 8%">渠道来源</td>
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
    <script src="{{ URL::asset('js/backend/statistics/index.js')}}"></script>
@endsection