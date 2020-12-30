<link rel="stylesheet" href="{{ URL::asset('css/agent/finance/finance.css') }}">
@extends('layout.iframe')

@section("main-content")
    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard'])
    @endcomponent
    <!-- 面包屑组件end  -->
    <div id="main">
        <!--  提示组件 start -->
        @component('component/tips')
    @endcomponent
    <!--  查询 start -->
        <div class="" style="margin-top:20px">
            @include('backend.queue.ordersyncqueue._search')
        </div>
        <!--  查询 end -->

        <!--  数据统计 start -->
        <div class="order_statistics_data">
            <div class="order_statistics_data_child">
                <p class="osdc_title">已处理</p>
                <p class="osdc_content">
                    <span class="osdc_content_num osdc_content_blue">{{$queueStatus['finish']}}</span>
                </p>
            </div>
            <div class="order_statistics_data_child">
                <p class="osdc_title">处理中</p>
                <p class="osdc_content">
                    <span class="osdc_content_num osdc_content_blue">{{$queueStatus['progress']}}</span>
                </p>
            </div>
            <div class="order_statistics_data_child">
                <p class="osdc_title">处理失败</p>
                <p class="osdc_content">
                    <span class="osdc_content_num osdc_content_blue">{{$queueStatus['error']}}</span>
                </p>
            </div>

        </div>
        <!--  数据统计 end -->

        <!--  操作按钮 start -->
        <div id="act-btn" style="margin:20px 0 10px 0;display: flex;justify-content: space-between;">
            <button class="btn btn-write btn-refresh"><i class="fa fa-refresh"></i> 刷新</button>
        </div>
        <!--  操作按钮 end -->

        <!-- 列表start  -->
        <div id="table">
            <table class="no-border-table" data-url="/queue/ordersyncqueue/list">
                <thead>
                <tr class="table-head">
                    <td>外部订单号</td>
                    <td>商品类型</td>
                    <td>系统订单号</td>
                    <td style="width:12%;">状态</td>
                    <td>错误信息</td>
                    <td>创建时间</td>
                    <td>所属分销</td>
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
    <!-- 列表end    -->
@endsection
<!---  引入当前页需使用的js  -->
@section("pages-js")

@endsection
@section("js-file")
    <script src="{{ URL::asset('js/backend/queue.js')}}"></script>
@endsection
