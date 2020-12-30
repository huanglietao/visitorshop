<!--  查询 start -->
<div class="" style="margin-top:20px">
    @include('agent.finance.sales_analysis.order._search')
</div>
<!--  查询 end -->

<!--  数据统计 start -->
<div class="order_statistics_data">
    <div class="order_statistics_data_child">
        <p class="osdc_title">销售总额</p>
        <p class="osdc_content">
            <span class="osdc_content_num osdc_content_blue">{{$orderStatus[0]}}</span>
            <span class="osdc_content_unit osdc_content_blue">元</span>
        </p>
    </div>
    <div class="order_statistics_data_child">
        <p class="osdc_title">异常单总额</p>
        <p class="osdc_content">
            <span class="osdc_content_num osdc_content_blue">{{$orderStatus[1]}}</span>
            <span class="osdc_content_unit osdc_content_blue">元</span>
        </p>
    </div>
    <div class="order_statistics_data_child">
        <p class="osdc_title">平均客单价</p>
        <p class="osdc_content">
            <span class="osdc_content_num osdc_content_blue">{{$orderStatus[2]}}</span>
            <span class="osdc_content_unit osdc_content_blue">元</span>
        </p>
    </div>
    <div class="order_statistics_data_child">
        <p class="osdc_title">总订单数</p>
        <p class="osdc_content">
            <span class="osdc_content_num osdc_content_gray">{{$orderStatus[3]}}</span>
            <span class="osdc_content_unit osdc_content_gray">笔</span>
        </p>
    </div>
    <div class="order_statistics_data_child">
        <p class="osdc_title">已确认数</p>
        <p class="osdc_content">
            <span class="osdc_content_num osdc_content_gray">{{$orderStatus[4]}}</span>
            <span class="osdc_content_unit osdc_content_gray">笔</span>
        </p>
    </div>
    <div class="order_statistics_data_child">
        <p class="osdc_title">已支付数</p>
        <p class="osdc_content">
            <span class="osdc_content_num osdc_content_gray">{{$orderStatus[5]}}</span>
            <span class="osdc_content_unit osdc_content_gray">笔</span>
        </p>
    </div>
    <div class="order_statistics_data_child">
        <p class="osdc_title">未发货数</p>
        <p class="osdc_content">
            <span class="osdc_content_num osdc_content_gray">{{$orderStatus[6]}}</span>
            <span class="osdc_content_unit osdc_content_gray">笔</span>
        </p>
    </div>
    <div class="order_statistics_data_child">
        <p class="osdc_title">已发货数</p>
        <p class="osdc_content">
            <span class="osdc_content_num osdc_content_gray">{{$orderStatus[7]}}</span>
            <span class="osdc_content_unit osdc_content_gray">笔</span>
        </p>
    </div>
    <div class="order_statistics_data_child">
        <p class="osdc_title">已完成数</p>
        <p class="osdc_content">
            <span class="osdc_content_num osdc_content_gray">{{$orderStatus[8]}}</span>
            <span class="osdc_content_unit osdc_content_gray">笔</span>
        </p>
    </div>
    <div class="order_statistics_data_child">
        <p class="osdc_title">异常单数</p>
        <p class="osdc_content">
            <span class="osdc_content_num osdc_content_gray">{{$orderStatus[9]}}</span>
            <span class="osdc_content_unit osdc_content_gray">笔</span>
        </p>
    </div>
</div>
<!--  数据统计 end -->

<!--  操作按钮 start -->
<div id="act-btn" style="margin:20px 0 10px 0;display: flex;justify-content: space-between;">
    <button class="btn btn-write btn-refresh"><i class="fa fa-refresh"></i> 刷新</button>
    <button id="export" data-value="orders" class="btn btn-write btn-undo" style="margin-right: 0"><i class="fa fa-download"></i> 导出</button>
</div>
<!--  操作按钮 end -->

<!-- 列表start  -->
<div id="table">
    <table class="no-border-table" data-url="/finance/order/table">
        <thead>
        <tr class="table-head">
            {{--<td class="finance_order_checkbox_first_td">--}}
                {{--@component('component/checkbox',['checkbox'=>['全选'],'name'=>['checkall'],'custom_class'=>"checkall checkall1 checkbox",'right_distance'=>10])--}}
                {{--@endcomponent--}}
            {{--</td>--}}
            <td style="width: 15%">订单号</td>
            <td style="width: 30%">状态</td>
            <td style="width: 5%">数量</td>
            <td style="width: 10%">金额</td>
            <td style="width: 5%">运费</td>
            <td style="width: 20%">下单时间</td>
            <td style="width: 10%">操作</td>
        </tr>
        </thead>
        <tbody class="tbl-content">

        </tbody>
    </table>
    @component('component/paginate',['limit' =>\Config::get('pageLimit') ])
    @endcomponent

</div>
<!-- 列表end    -->
<script src="{{ URL::asset('js/agent/finance/index.js')}}"></script>