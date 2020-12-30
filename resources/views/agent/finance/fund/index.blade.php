<!doctype html>
@extends('layout.iframe')

@section("main-content")
    <link rel="stylesheet" href="{{ URL::asset('css/agent/finance/finance.css') }}">
    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 财务统计 / 资金明细' ])
    @endcomponent
    <!-- 面包屑组件end  -->


    <div id="main" style="padding:25px">
        <!--  提示组件 start -->
        @component('component/tips')
        {{--<p style="margin:5px 0 ;padding:0">资金账户充值分为，线下入账和即时到账二种方式</p>--}}
        {{--<p style="margin:5px 0;padding:0">线下入账是提交的线下充值入账申请，需要财务人员后台审核才能入账；即时到账是通过第三方支付渠道进行在线付款，能实时到账。</p>--}}
    @endcomponent
    <!--  提示组件 end -->

        <!-- 搜索功能 start  -->
        <div class="" style="margin-top:20px">
            @include('agent.finance.fund._search')
        </div>
        <!--  搜索功能 end -->

        <!--  数据统计 start -->
        <div class="order_statistics_data">
            <div class="order_statistics_data_child" style="width: 50%;margin: 10px 3%">
                <p class="osdc_title">收入</p>
                <p class="osdc_content">
                    <span class="osdc_content_num osdc_content_blue">+{{$statisticsInfo['income']['money']}} </span>
                    <span class="osdc_content_unit osdc_content_blue">元</span>
                </p>
                <p class="osdc_title" style="color: #797777">{{$statisticsInfo['income']['count']}}笔</p>
            </div>
            <div class="order_statistics_data_child" style="width: 50%;margin: 10px 3%">
                <p class="osdc_title">支出</p>
                <p class="osdc_content">
                    <span class="osdc_content_num osdc_content_blue" style="color: #dc3545">-{{$statisticsInfo['expenditure']['money']}} </span>
                    <span class="osdc_content_unit osdc_content_blue" style="color: #dc3545">元</span>
                </p>
                <p class="osdc_title" style="color: #797777">{{$statisticsInfo['expenditure']['count']}}笔</p>
            </div>

        </div>
        <!--  数据统计 end -->


        <!-- 操作按钮 start  -->
         <div class="statistics_tabs" style="margin-top: 20px;position: relative;">
             @component('component.navOperateTab',['navlist'=>['ALL'=>'全部','FINANCE_INCOME'=>'收入','FINANCE_EXPEND'=>'支出'],'extendClass'=>"fund_tab"])

             @endcomponent
             <!--  操作按钮 start -->
                 <div style="position: absolute;right: 0;top:0;bottom:0;margin-top: auto;margin-bottom: auto;" class="btn-fund-export">
                     <button class="btn btn-write btn-undo" style="margin-right: 0"><i class="fa fa-download"></i> 导出</button>
                 </div>
                 <!--  操作按钮 end -->
                 <input type="hidden" class="tab_val" value="ALL">
         </div>

        <!-- 操作按钮 end  -->

        <!-- 列表start  -->
        <div id="table">
            <table class="no-border-table" data-url="/finance/fund/list">
                <thead>
                <tr class="table-head">
                    <td>业务单号</td>
                    <td>发生时间</td>
                    <td>类型</td>
                    <td>关联支付流水号</td>
                    <td>金额</td>
                    <td>余额</td>
                    <td>操作人</td>
                    <td style="width: 20%">描述</td>
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

    <script type="text/javascript" src="{{ URL::asset('js/agent/finance/fund.js') }}"></script>

@endsection






