<!doctype html>
@extends('layout.mch_iframe')

@section("main-content")
    <link rel="stylesheet" href="{{ URL::asset('css/agent/finance/finance.css') }}">
    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 分销管理 / 资金明细' ])
    @endcomponent
    <!-- 面包屑组件end  -->


    <div id="main" style="padding:25px">
        <!--  提示组件 start -->
        @component('component/tips')
        {{--<p style="margin:5px 0 ;padding:0">资金账户充值分为，线下入账和即时到账二种方式</p>--}}
        {{--<p style="margin:5px 0;padding:0">线下入账是提交的线下充值入账申请，需要财务人员后台审核才能入账；即时到账是通过第三方支付渠道进行在线付款，能实时到账。</p>--}}
        {{--<p style="margin:5px 0;padding:0"></p>--}}
    @endcomponent
    <!--  提示组件 end -->

        <!-- 搜索功能 start  -->
        <div class="" style="margin-top:20px">
            @include('merchant.agent.fund._search')
        </div>
        <!--  搜索功能 end -->


        <!-- 操作按钮 start  -->
        <div id="act-btn" style="padding:20px 0">
            <button class="btn btn-white btn-refresh"><i class="fa fa-refresh"></i> &nbsp;刷新</button>
        </div>
        <!-- 操作按钮 end  -->

        <!-- 列表start  -->
        <div id="table">
            <table class="no-border-table" data-url="/agent/fund/list{{$str}}">
                <thead>
                <tr class="table-head">
                    <td>关联分销商</td>
                    <td>业务单号</td>
                    <td>发生时间</td>
                    <td>类型</td>
                    <td>支付方式</td>
                    <td>关联支付流水号</td>
                    <td>金额</td>
                    <td>余额</td>
                    <td>操作人</td>
                    <td style="width: 20%;">描述</td>
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






