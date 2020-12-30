<!--  查询 start -->
<div class="" style="margin-top:20px">
    @include('agent.finance.sales_analysis.logisticsDetail._search')
</div>
<!--  查询 end -->

<!--  数据统计 start -->
<div class="order_statistics_data">
    <div class="order_statistics_data_child">
        <p class="osdc_title">物流公司数量</p>
        <p class="osdc_content">
            <span class="osdc_content_num osdc_content_blue">{{$navData['deliNums']}} </span>
            <span class="osdc_content_unit osdc_content_blue">家</span>
        </p>
    </div>
    <div class="order_statistics_data_child">
        <p class="osdc_title">总票数</p>
        <p class="osdc_content">
            <span class="osdc_content_num osdc_content_blue">{{$navData['ordNums']}}</span>
            <span class="osdc_content_unit osdc_content_blue">票</span>
        </p>
    </div>
    <div class="order_statistics_data_child">
        <p class="osdc_title">总运费</p>
        <p class="osdc_content">
            <span class="osdc_content_num osdc_content_blue">{{$navData['totals']}} </span>
            <span class="osdc_content_unit osdc_content_blue">元</span>
        </p>
    </div>
    <div class="order_statistics_data_child">
        <p class="osdc_title">占比最高物流</p>
        <p class="osdc_content">
            <span class="osdc_content_num osdc_content_blue">{{$navData['mixList']['mix']}}%</span>
            <span class="osdc_content_unit osdc_content_blue">{{$navData['mixList']['deliName']}}</span>
        </p>
    </div>
</div>
<!--  数据统计 end -->

<!--  操作按钮 start -->
<div id="act-btn" style="margin:20px 0 10px 0;display: flex;justify-content: space-between;">
    <div>
        <button class="btn btn-write btn-refresh"><i class="fa fa-refresh"></i> 刷新</button>
        <button class="btn btn-write btn-back"><i class="fa fa-arrow-left"></i> 返回</button>
    </div>
    <button id="export" data-value="logisticsDetail" class="btn btn-write btn-undo" style="margin-right: 0"><i class="fa fa-download"></i> 导出</button>
</div>
<!--  操作按钮 end -->

<!-- 列表start  -->
<div id="table" >
    <table class="no-border-table" data-url="/finance/logisticsDetail/table?order_delivery_id={{$str}}">
        <thead>
        <tr class="table-head">
            {{--<td class="finance_order_checkbox_first_td" style="width: 5%">--}}
                {{--@component('component/checkbox',['checkbox'=>['全选'],'name'=>['checkall'],'custom_class'=>"checkall checkall1 checkbox",'right_distance'=>10])--}}
                {{--@endcomponent--}}
            {{--</td>--}}
            <td style="width: 10%">订单号</td>
            <td style="width: 10%">下单时间</td>
            <td style="width: 10%">物流公司</td>
            <td style="width: 10%">物流单号</td>
            <td style="width: 10%">发货时间</td>
            <td style="width: 5%">数量</td>
            <td style="width: 5%">总数量</td>
            <td style="width: 10%">地区</td>
            <td style="width: 5%">状态</td>
            <td style="width: 5%">运费</td>
            <td style="width: 5%">订单金额</td>
            <td style="width: 5%">运费占比</td>
        </tr>
        </thead>
        <tbody class="tbl-content">

        </tbody>
    </table>
    @component('component/paginate',['limit' => \Config::get('pageLimit')])
    @endcomponent

</div>
<!-- 列表end    -->
<script src="{{ URL::asset('js/agent/finance/index.js')}}"></script>