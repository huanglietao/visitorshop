<?php
/**
 * Created by sass.
 * Author: LJH
 * Date: 2019/12/25
 * Time: 11:46
 */
?>

@extends('layout.erp_iframe')

@section("main-content")

    <!-- 面包屑 start  -->
    @component('component/crumb')
        <i class="fa fa-dashboard"></i> 控制台 / 订单管理 / 订单列表
    @endcomponent
    <!-- 面包屑 end  -->

    <div id="main" >
        <!-- 操作提示 start -->
        @component('component/tips')
            <p style="margin:5px 0 ;padding:0">客户对账单订单查询日期最长为一个月时间。</p>
            <p style="margin:5px 0;padding:0">默认只查询昨天的订单，时间为昨天早上8点至当前时间。</p>
            <p style="margin:5px 0;padding:0">导出报表内容格式为Excel，对帐记录与页面设置的查询条件结果一致。</p>
    @endcomponent
    <!-- 操作提示 end -->

        <div id="download" style="float:right;border: 1px solid #BBBBBB;padding: 2px 10px;cursor: pointer;margin: 15px 0;" >
            <span class="back" style="border-radius: 0">
                <img src="/images/download.png" style="width:15px;position:relative;top:-2px">
                <span style="color: black;margin-left: 6px">导出报表</span>
            </span>
        </div>


        <!-- 搜索功能 start  -->
        <div id="searchID" style="margin-top:20px;clear: both">
            @include('erp.order._search')
        </div>
        <!--  搜索功能 end -->

        <div class="statistics_loading"></div>
        <div id="table" style="margin-top: 20px">
            <!-- table start -->
            <table class="no-border-table">
                <thead>
                <tr class="table-head">
                    <td>订单日期</td>
                    <td>出货单号</td>
                    <td>订单内容</td>
                    <td>品名及规格</td>
                    <td>数量</td>
                    <td>单位</td>
                    <td>单双面</td>
                    <td>原价</td>
                    <td>其它</td>
                    <td>加工</td>
                    <td>折让</td>
                    <td>合计</td>
                    <td>备注</td>
                </tr>
                </thead>
                <tbody class="tbl-content">

                </tbody>
            </table>
        @include('erp.order._paginate',['limit' => $pageLimit])
        <!-- table end -->
        </div>
    </div>

@endsection

<!---  引入当前页需使用的js  -->
@section("js-file")
    <script src="{{ URL::asset('js/erp/index.js')}}"></script>
    <script src="{{ URL::asset('assets/daterangepicker/moment.min.js')}}"></script>
    <script src="{{ URL::asset('assets/daterangepicker/daterangepicker.js')}}"></script>
@endsection
@section("pages-js")

@endsection