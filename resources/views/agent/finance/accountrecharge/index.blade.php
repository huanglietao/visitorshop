<!doctype html>
@extends('layout.iframe')

@section("main-content")
    <link rel="stylesheet" href="{{ URL::asset('css/agent/finance/finance.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/daterangepicker/daterangepicker.css') }}">
    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 财务统计 / 账户充值' ])
    @endcomponent
    <!-- 面包屑组件end  -->


    <div id="main" class="accounts-one">
        <!--  提示组件 start -->
        @component('component/tips')
            {{--<p>资金账户充值分为，线下入账和即时到账二种方式</p>--}}
            {{--<p>线下入账是提交的线下充值入账申请，需要财务人员后台审核才能入账；即时到账是通过第三方支付渠道进行在线付款，能实时到账。</p>--}}
        @endcomponent
        <!--  提示组件 end -->

        <!-- 搜索功能 start  -->
        <div class="" style="margin-top:20px;margin-bottom: 20px;">
            @include('agent.finance.accountrecharge._search')
        </div>
        <!--  提示功能 end -->

        <!-- 列表start  -->
        <div id="table">
            <table class="no-border-table" data-url="/finance/accountrecharge/table">
                <thead>
                <tr class="table-head">
                    <td style="width: 10%">充值订单号</td>
                    <td style="width: 10%">金额</td>
                    <td style="width: 10%">状态</td>
                    <td style="width: 10%">支付类型</td>
                    <td style="width: 10%">关联支付流水号</td>
                    <td style="width: 10%">创建时间</td>
                    <td style="width: 10%">到账时间</td>
                    <td style="width: 5%">操作</td>
                </tr>
                </thead>
                <tbody class="tbl-content">

                </tbody>
            </table>
            @component('component/paginate',['limit' =>\Config::get('pageLimit')])
            @endcomponent

        </div>
        <!-- 列表end    -->
    </div>
@endsection

<!---  引入当前页需使用的js  -->
@section("js-file")
    <script type="text/javascript" src="{{ URL::asset('js/agent/finance/recharge.js') }}"></script>
    <script src="{{ URL::asset('assets/daterangepicker/moment.min.js')}}"></script>
    <script src="{{ URL::asset('assets/daterangepicker/daterangepicker.js')}}"></script>
@endsection

@section("pages-js")

@endsection





