<?php
/**
 * Created by sass.
 * Author: LJH
 * Date: 2019/1/6
 * Time: 11:46
 */
?>

@extends('layout.erpkf_iframe')

@section("main-content")

    <!-- 面包屑 start  -->
    @component('component/crumb')
        <i class="fa fa-dashboard"></i> 控制台 / 对账管理 / 客户对账单
    @endcomponent
    <!-- 面包屑 end  -->

    <div id="main" >
        <!-- 操作提示 start -->
        @component('component/tips')
            <p style="margin:5px 0 ;padding:0">客户对帐单查询日期最长为一个月时间。</p>
            <p style="margin:5px 0;padding:0">默认只查询昨天的对帐记录，时间为昨天早上8点至当前时间。</p>
            <p style="margin:5px 0;padding:0">导出报表内容格式为Excel，对帐记录与页面设置的查询条件结果一致。</p>
        @endcomponent
    <!-- 操作提示 end -->

        <div id="download" style="margin-top: 30px;float:right;border: 1px solid #BBBBBB;padding: 2px 10px;position: relative;cursor: pointer;" >
            <span class="back" style="border-radius: 0">
                <img src="/images/download.png" style="width:15px;position:relative;top:-2px">
                <span style="color: black;margin-left: 6px">导出报表</span>
            </span>
        </div>
        <div style="margin:20px 0">
            @component('component.navOperateTab',['navlist'=>['1'=>'全部记录'],'extendClass'=>"s_analy_tab"])

            @endcomponent
        </div>


        <!-- 搜索功能 start  -->
        <div id="searchID" style="margin-top:20px">
            @include('erpkf.reconciliation.bill._search',['partner_code'=>$partner_codeList])
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
                        <td>出货状态</td>
                        <td>物流方式</td>
                        <td>物流单号</td>
                        <td>物流时间</td>
                        <td>第三方订单流水号</td>
                        <td>备注</td>
                    </tr>
                </thead>
                <tbody class="tbl-content">

                </tbody>
            </table>
        @include('erpkf.reconciliation.bill._paginate',['limit' => $pageLimit])
        <!-- table end -->
        </div>
    </div>

@endsection

<!---  引入当前页需使用的js  -->
@section("js-file")
    <script src="{{ URL::asset('js/erpkf/index.js')}}"></script>
    <script src="{{ URL::asset('assets/daterangepicker/moment.min.js')}}"></script>
    <script src="{{ URL::asset('assets/daterangepicker/daterangepicker.js')}}"></script>
@endsection
@section("pages-js")

@endsection
<style>
    .nav_status_btn{
        border-right: 1px solid rgb(220, 223, 230);
    }
</style>