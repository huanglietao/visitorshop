<!doctype html>
@extends('layout.mch_iframe')

@section("main-content")
    <link rel="stylesheet" href="{{URL::asset('css/merchant/order/list.css')}}">
    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 订单管理  /  订单列表' ])
    @endcomponent
    <!-- 面包屑组件end  -->

    <div id="main">

        <!--  提示组件 start -->
        @component('component/tips')
            {{--<p style="margin:5px 0 ;padding:0">订单列表中包括各种状态下的交易记录信息，可针对不同状态下订单进行处理。</p>--}}
        @endcomponent
        <!--  提示组件 end -->

        <!-- 搜索功能 start  -->
        <div class="" style="margin-top:20px">
            @include('merchant.order.list._search')
        </div>
        <!--  搜索功能 end -->

        <!-- tab状态按钮 start  -->
        <div class="nav_tab">
            @component('component.navOperateTab',['navlist'=>['ALL'=>'全部(0)','ORDER_STATUS_WAIT_CONFIRM'=>'待确认(0)','ORDER_STATUS_WAIT_PAY'=>'待支付(0)','ORDER_STATUS_WAIT_PRODUCE'=>'待生产(0)','ORDER_STATUS_WAIT_DELIVERY'=>'待发货(0)','ORDER_STATUS_FINISH'=>'已完成(0)','ORDER_STATUS_CANCEL'=>'取消(0)','ORDER_STATUS_AFTERSALE'=>'退换货(0)'],'extendClass'=>'works_tab'])@endcomponent
        </div>
        <!-- tab状态按钮 end  -->

        <!-- 操作按钮 start  -->
        <div id="act-btn" style="padding-bottom: 20px;">
            <input type="checkbox" class="checkall checkall1 checkbox all-checkall" id="all" data-id="1"><label for="all" style="margin-right: 10px"></label><span class="o_checkall-text">全选/反选</span>
{{--            <button class="btn btn-write btn-dialog"  data-url="{{URL::asset('/order/test')}}" data-title="批量发货">批量发货</button>--}}
            <button class="btn btn-write btn-batch-sign" data-title="批量标记">批量标记</button>
            <button class="btn btn-write btn-sign btn-dialog" style="display: none" data-title="批量标记"></button>
            <button class="btn btn-write btn-order-export" data-title="导出"><i class="fa fa-download"></i>导出</button>

            <input type="hidden" class="tab_val" value="ALL">
        </div>
        <!-- 操作按钮 end  -->

        <!-- 列表start  -->
        <div id="table">
            <table class="no-border-table" data-url="/order/list/list?{{$_SERVER['QUERY_STRING']}}">
                <thead>
                <tr class="table-head">
                    <td>商品</td>
                    <td>单价</td>
                    <td>数量</td>
                    <td>生产状态</td>
                    <td>订单状态</td>
                    <td>实收款</td>
                    <td>标签</td>
                    <td>操作</td>
                </tr>
                </thead>
                <tbody class="tbl-content">

                </tbody>
            </table>

            <!-- 操作按钮 start  -->
            <div id="act-btn" style="padding-top:20px;padding-bottom: 60px;">
                <input type="checkbox" class="checkall checkall1 checkbox all-checkall" id="all" data-id="1"><label for="all" style="margin-right: 10px"></label><span class="o_checkall-text">全选/反选</span>
{{--                <button class="btn btn-write btn-dialog"  data-url="{{URL::asset('/order/test')}}" data-title = "批量发货">批量发货</button>--}}
                <button class="btn btn-write btn-batch-sign" data-title="批量标记">批量标记</button>

            </div>
            <!-- 操作按钮 end  -->

            @component('component/paginate',['limit' => \Config::get('pageLimit')])
            @endcomponent

        </div>
        <!--  列表 end -->

    </div>



@endsection
<!--  加载js -->
@section("js-file")
    <script src="{{ URL::asset('js/merchant/order/list.js')}}"></script>
@endsection

@section("pages-js")
    //订单数量统计
    $.ajax({
        url : "/order/list/count",
        type : 'POST',
        dataType : 'JSON',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        success : function (data) {
            if(data['success'] == 'true'){
                $('#works-status-box').find('.workss_status_btn').each(function (e) {
                var str = '';
                switch (e){
                    case 0:
                        str = '全部('
                    break;
                    case 1:
                        str = '待确认('
                    break;
                    case 2:
                        str = '待支付('
                    break;
                    case 3:
                        str = '待生产('
                    break;
                    case 4:
                        str = '待发货('
                    break;
                    case 5:
                        str = '已完成('
                    break;
                    case 6:
                        str = '取消('
                    break;
                    case 7:
                        str = '退换货('
                    break;
                }
                $(this).html(str+data['data'][e]+')')
                })
            }
        }
    });
@endsection







