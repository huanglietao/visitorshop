
@extends('layout.factory_iframe')

@section("main-content")
    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => ' 对账报表 / 生产对账' ])
    @endcomponent
    <!-- 面包屑组件end  -->
<div id="main">
        <!--  提示组件 start -->
        @component('component/tips')
            {{--<p class="accounts-two">订单发货统计是统计订单中状态为已发货的订单</p>--}}
    @endcomponent
    <!--  查询 start -->
    <div class="" style="margin-top:20px">
        @include('factory.reportform.produce._search')
    </div>
    <!--  查询 end -->

    <!--  操作按钮 start -->
    <div id="act-btn" style="margin:20px 0 10px 0;display: flex;justify-content: space-between;">
        <button class="btn btn-write btn-refresh"><i class="fa fa-refresh"></i> 刷新</button>
        <button id="prod_export" class="btn btn-write btn-undo" style="margin-right: 0"><i class="fa fa-download"></i> 导出</button>
    </div>
    <!--  操作按钮 end -->

    <!-- 列表start  -->
    <div id="table">
        <table class="no-border-table" data-url="/reportform/produce/list">
            <thead>
            <tr class="table-head">
                <td style="width: 4%">商品</td>
                <td style="width: 6%">产品工厂码</td>
                <td style="width: 7%">客户简称</td>
                <td style="width: 7%">单双面</td>
                {{--<td style="width: 7%">金额</td>--}}
                <td style="width: 5%">订购数量</td>
                <td style="width: 7%">订单编号</td>
                <td style="width: 7%">订单流水号</td>
                <td style="width: 7%">订单状态</td>
                <td style="width: 5%">交易时间</td>
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
    <script src="{{ URL::asset('js/factory/report.js')}}"></script>
@endsection
