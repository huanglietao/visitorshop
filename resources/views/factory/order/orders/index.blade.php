<!doctype html>
@extends('layout.factory_iframe')

@section("main-content")
    <link rel="stylesheet" href="{{URL::asset('css/factory/order/orders.css')}}">
    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 订单管理  /  订单列表' ])
    @endcomponent
    <!-- 面包屑组件end  -->

    <div id="main">

        <!--  提示组件 start -->
        @component('component/tips')
            <p style="margin:5px 0 ;padding:0">订单列表中包括各种状态下的交易记录信息，可针对不同状态下订单进行处理。</p>
        @endcomponent
        <!--  提示组件 end -->

        <!-- 搜索功能 start  -->
        <div class="" style="margin-top:20px">
            @include('factory.order.orders._search')
        </div>
        <!--  搜索功能 end -->

        <!-- tab状态按钮 start  -->
        <div class="nav_tab">
            @component('component.navOperateTab',['navlist'=>['ALL'=>'全部(0)','SP_ORDER_STATUS_PRODUCE'=>'待生产(0)','SP_ORDER_STATUS_PRODUCING'=>'生产中(0)','SP_ORDER_STATUS_DELIVERY'=>'已送货(0)','SP_ORDER_STATUS_SEND'=>'已发货(0)'],'extendClass'=>'works_tab'])@endcomponent
            <input type="hidden" class="tab_val" value="ALL">
        </div>
        <!-- tab状态按钮 end  -->


        <!-- 列表start  -->
        <div id="table">
            <table class="no-border-table" data-url="/order/orders/list">
                <thead>
                <tr class="table-head">
                    <td>商品</td>
                    <td>单价</td>
                    <td>数量</td>
                    <td>实收款</td>
                    <td>订单状态</td>
                    <td>二维码</td>
                    <td>下载状态</td>
                </tr>
                </thead>
                <tbody class="tbl-content">

                </tbody>
            </table>

            @component('component/paginate',['limit' => \Config::get('pageLimit')])

            @endcomponent

        </div>
        <!--  列表 end -->

    </div>



@endsection
<!--  加载js -->
@section("js-file")
    <script src="{{ URL::asset('js/factory/order/orders.js')}}"></script>
    <script src="{{ URL::asset('assets/jeromeetienne-jquery/jquery.qrcode.min.js')}}"></script>
@endsection

@section("pages-js")
    //订单数量统计
    $.ajax({
        url : "/order/orders/count",
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
                            str = '待生产('
                        break;
                        case 2:
                            str = '生产中('
                        break;
                        case 3:
                            str = '已送货('
                        break;
                        case 4:
                            str = '已发货('
                        break;
                    }
                    $(this).html(str+data['data'][e]+')')
                })
            }
        }
    });
@endsection






