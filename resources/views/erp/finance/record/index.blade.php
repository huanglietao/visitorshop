@extends('layout.erp_iframe')
@section("main-content")

    <!-- 面包屑组件start  -->
    @component('component/goods_crumb',['icon' => 'fa-dashboard', 'title' => '资金管理 / 充值记录'])
    @endcomponent
    <!-- 面包屑组件end  -->

    <div id="main">
        <!-- 搜索功能 start  -->
        <div class="" style="margin-top:20px;margin-bottom: 20px;">
            @include('erp.finance.record._search')
        </div>
        <!--  搜索功能 end -->

        <div style="width: 100%;margin-bottom: 20px;">
            <a href="/finance/recharge"><button class="btn btn-3F51B5 btn-primary up" style="cursor:pointer;">立即充值</button></a>
        </div>

        <!-- 列表start  -->
        <div id="table">
            <table class="no-border-table" data-url="/finance/record/list">
                <thead>
                <tr class="table-head">
                    <td style="width: 10%">客户编号</td>
                    <td style="width: 10%">客户名称</td>
                    <td style="width: 10%">充值订单号</td>
                    <td style="width: 10%">充值方式</td>
                    <td style="width: 10%">关联支付流水号</td>
                    <td style="width: 8%">金额</td>
                    <td style="width: 5%">手续费</td>
                    <td style="width: 10%">充值时间</td>
                    <td style="width: 10%">支付时间</td>
                    <td style="width: 10%">状态</td>
                    <td style="width: 5%">操作</td>
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

<!---  引入当前页需使用的js  -->
@section("js-file")
    <script src="{{ URL::asset('assets/daterangepicker/moment.min.js')}}"></script>
    <script src="{{ URL::asset('assets/daterangepicker/daterangepicker.js')}}"></script>
@endsection

@section("pages-js")

@endsection