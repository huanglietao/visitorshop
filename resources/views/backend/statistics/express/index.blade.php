<!DOCTYPE html>
@extends('layout.iframe')

@section("main-content")
    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 数据管理 / 物流对账' ])
    @endcomponent
    <!-- 面包屑组件end  -->
<div id="main">
        <!--  提示组件 start -->
        @component('component/tips')
            {{--<p class="accounts-two">物流对账</p>--}}
    @endcomponent
    <!--  查询 start -->
    <div class="" style="margin-top:20px">
        @include('backend.statistics.express._search',['InfoList'=>$InfoList])
    </div>
    <!--  查询 end -->

    <!--  操作按钮 start -->
    <div id="act-btn" style="margin:20px 0 10px 0;display: flex;justify-content: space-between;">
        <button class="btn btn-write btn-refresh"><i class="fa fa-refresh"></i> 刷新</button>
        <button id="express_import" class="btn btn-write" style="position: absolute;right: 85px;"><i class="fa fa-upload"></i> 导入物流成本</button>
        <input id="excel_upload" hidden style='width:140px;display: inline-block;padding-left: 0' type='file' accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel" class="col-sm-5" onchange="upload(this)">
        <button id="express_export" class="btn btn-write btn-undo" style="margin-right: 0"><i class="fa fa-download"></i> 导出</button>
    </div>
    <!--  操作按钮 end -->

    <!-- 列表start  -->
    @csrf
    <div id="table">
        <table class="no-border-table" data-url="/statistics/express/list">
            <thead>
            <tr class="table-head">
                {{--<td class="finance_order_checkbox_first_td">--}}
                    {{--@component('component/checkbox',['checkbox'=>['全选'],'name'=>['checkall'],'custom_class'=>"checkall checkall1 checkbox",'right_distance'=>10])--}}
                    {{--@endcomponent--}}
                {{--</td>--}}
                <td style="width: 13%">订单号</td>
                <td style="width: 8%">店铺来源</td>
                <td style="width: 5%">商品数量</td>
                <td style="width: 5%">商品总重量</td>
                <td style="width: 10%">发货日期</td>
                <td style="width: 10%">下单日期</td>
                <td style="width: 12%">供应商</td>
                <td style="width: 8%">配送方式</td>
                <td style="width: 10%">物流单号</td>
                <td style="width: 5%">物流成本</td>
                <td style="width: 8%">配送区域</td>
                <td style="width: 8%">揽件重量</td>
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
