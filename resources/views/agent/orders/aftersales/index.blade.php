@extends('layout.iframe')

@section("main-content")
    <link rel="stylesheet" href="{{ URL::asset('css/agent/orders/orders.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('assets/daterangepicker/daterangepicker.css') }}">


    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 订单管理 / 订单列表' ])
    @endcomponent
    <!-- 面包屑组件end  -->


    <div id="main" style="padding:15px">
        <!--  提示组件 start -->
        @component('component/tips')
            {{--<p style="margin:5px 0 ;padding:0">订单列表中包括各种状态下的交易记录信息，可针对不同状态下订单进行处理。</p>--}}
        @endcomponent
        <!--  提示组件 end -->

        <!-- 搜索功能 start  -->
        <div class="" style="margin-top:20px">
            @include('agent.orders.aftersales._search')
        </div>
        <!--  提示功能 end -->

        <!-- 操作按钮 start  -->
        <div id="act-btn" style="padding:20px 0">
            <button class="btn btn-write btn-refresh"><i class="fa fa-refresh"></i> 刷新</button>
            <button data-area="['70%', '700px']" data-url="{{URL::asset('/order/service/form')}}" data-title = "添加" class="btn-dialog btn btn-primary btn-sm btn-3F51B5 btn-add"  style="padding:3px 8px;font-size: 12px" ><i class="fa fa-plus"></i> 添加售后单</button>
        </div>
        <!-- 操作按钮 end  -->

        <!-- 列表start  -->
        <div id="table">
            <table class="no-border-table" data-url="/order/service/list">
                <thead>
                <tr class="table-head">
                    <td>售后单号</td>
                    <td>订单号</td>
                    <td>订单金额</td>
                    <td>数量</td>
                    <td>类型</td>
                    <td>状态</td>
                    <td>申请人</td>
                    {{--<td>售后原因</td>--}}
                    <td>申请时间</td>
                    <td>处理时间</td>
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
    <script src="{{ URL::asset('assets/daterangepicker/moment.min.js')}}"></script>
    <script src="{{ URL::asset('assets/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{ URL::asset('js/agent/orders/orders.js')}}"></script>
    <script type="text/javascript" src="/assets/plupload/plupload.full.min.js"></script>
@endsection
