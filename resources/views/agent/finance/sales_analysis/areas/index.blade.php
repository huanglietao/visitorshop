<!--  查询 start -->
<div class="" style="margin-top:20px">
    @include('agent.finance.sales_analysis.areas._search')
</div>
<!--  查询 end -->

<!--  数据统计 start -->
<div class="order_statistics_data">
    <div class="order_statistics_data_child">
        <p class="osdc_title">省直辖市数量</p>
        <p class="osdc_content">
            <span class="osdc_content_num osdc_content_blue">{{$navData['proNums']}}</span>
            <span class="osdc_content_unit osdc_content_blue">个</span>
        </p>
    </div>
    <div class="order_statistics_data_child">
        <p class="osdc_title">总订单数量</p>
        <p class="osdc_content">
            <span class="osdc_content_num osdc_content_blue">{{$navData['ordNums']}} </span>
            <span class="osdc_content_unit osdc_content_blue">笔</span>
        </p>
    </div>
    <div class="order_statistics_data_child">
        <p class="osdc_title">销售金额</p>
        <p class="osdc_content">
            <span class="osdc_content_num osdc_content_blue">{{$navData['totals']}}</span>
            <span class="osdc_content_unit osdc_content_blue">元</span>
        </p>
    </div>
    <div class="order_statistics_data_child">
        <p class="osdc_title">销售占比最高省份</p>
        <p class="osdc_content">
            <span class="osdc_content_num osdc_content_blue">{{$navData['mixList']['mix']}}%</span>
            <span class="osdc_content_unit osdc_content_blue">{{$navData['mixList']['proName']}}</span>
        </p>
    </div>
</div>
<!--  数据统计 end -->

<!--  操作按钮 start -->
<div id="act-btn" style="margin:20px 0 10px 0;display: flex;justify-content: space-between;">
    <button class="btn btn-write btn-refresh"><i class="fa fa-refresh"></i> 刷新</button>
    <button id="export" data-value="areas" class="btn btn-write btn-undo" style="margin-right: 0"><i class="fa fa-download"></i> 导出</button>
</div>
<!--  操作按钮 end -->

<!-- 列表start  -->
<div id="table">
    <table class="no-border-table" data-url="/finance/areas/table" >
        <thead>
        <tr class="table-head">
            {{--<td class="finance_order_checkbox_first_td">--}}
                {{--@component('component/checkbox',['checkbox'=>['全选'],'name'=>['checkall'],'custom_class'=>"checkall checkall1 checkbox",'right_distance'=>10])--}}
                {{--@endcomponent--}}
            {{--</td>--}}
            <td style="width: 20%">省/直辖市</td>
            <td style="width: 20%">订单数量</td>
            <td style="width: 19%">销售金额</td>
            <td style="width: 19%">客单价</td>
            <td style="width: 19%">占比</td>
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