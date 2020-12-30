@extends('layout.mch_iframe')

@section("main-content")
    <link rel="stylesheet" href="{{URL::asset('css/merchant/order/list.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('assets/daterangepicker/daterangepicker.css') }}">


    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 订单管理 / 订单售后' ])
    @endcomponent
    <!-- 面包屑组件end  -->


    <div id="main" style="padding:15px">
        <!--  提示组件 start -->
        @component('component/tips')
            {{--<p style="margin:5px 0 ;padding:0">订单详情记录订单的基本信息、收货人信息、商品信息、费用信息等。订单分5个大流程分别是提交、支付、发货、收货及评价。</p>--}}
            {{--<p style="margin:5px 0;padding:0">特殊类目订单由于行业特性可能有其它支流程，且该类订单商品拥有特殊子项目信息，可点击展开键查看特殊信息。</p>--}}
            {{--<p style="margin:5px 0;padding:0">分销类商家提交成功的订单是无法修改的（系统会自动确认执行后续处理流程），只能联系商家进行修改。订单售后问题需要确认收货后才能申请售后。</p>--}}
        @endcomponent
        <!--  提示组件 end -->

        <!-- 搜索功能 start  -->
        <div class="" style="margin-top:20px">
            @include('merchant.order.service._search')
        </div>
        <!--  提示功能 end -->

        <!-- tab状态按钮 start  -->
        <div class="nav_tab">
            @component('component.navOperateTab',['navlist'=>['all'=>'全部售后',ORDER_AFTER_STATUS_UNPROCESSED=>'未处理',ORDER_AFTER_STATUS_PROCESSED=>'已处理',ORDER_AFTER_STATUS_FILE=>'审核归档',ORDER_AFTER_STATUS_WITHDRAW=>'已撤回'],'extendClass'=>'works_tab'])
            @endcomponent
            <input type="hidden" class="tab_val" value="all">
        </div>
        <!-- tab状态按钮 end  -->

        <!-- 操作按钮 start  -->
        <div id="act-btn" style="padding:20px 0">
            <button class="btn btn-write btn-refresh"><i class="fa fa-refresh"></i> 刷新</button>
            <button class="btn btn-write btn-job-export" data-title="导出"><i class="fa fa-download"></i>导出</button>
            <button data-area="['70%', '70%']" data-url="{{URL::asset('/order/service/form')}}" data-title = "添加" class="btn-dialog btn btn-primary btn-sm btn-3F51B5 btn-add"  style="padding:3px 8px;font-size: 12px" ><i class="fa fa-plus"></i> 添加售后单</button>
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
    <script src="{{ URL::asset('js/merchant/order/list.js')}}"></script>
    <script type="text/javascript" src="/assets/plupload/plupload.full.min.js"></script>
@endsection
@section("pages-js")

@endsection
