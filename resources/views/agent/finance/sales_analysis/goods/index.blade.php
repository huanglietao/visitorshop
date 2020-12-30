<!--  查询 start -->
<div class="" style="margin-top:20px">
    @include('agent.finance.sales_analysis.goods._search')
</div>
<!--  查询 end -->

<!--  数据统计 start -->
<div class="order_statistics_data">
    <div class="order_statistics_data_child">
        <p class="osdc_title">SKU数量</p>
        <p class="osdc_content">
            <span class="osdc_content_num osdc_content_blue">{{$totalNum['sku_num']}}</span>
            <span class="osdc_content_unit osdc_content_blue">款</span>
        </p>
    </div>
    <div class="order_statistics_data_child">
        <p class="osdc_title">总销量</p>
        <p class="osdc_content">
            <span class="osdc_content_num osdc_content_blue">{{$totalNum['prod_num']}}</span>
            <span class="osdc_content_unit osdc_content_blue">件</span>
        </p>
    </div>
    <div class="order_statistics_data_child">
        <p class="osdc_title">销售金额</p>
        <p class="osdc_content">
            <span class="osdc_content_num osdc_content_blue">{{$totalNum['prices']}} </span>
            <span class="osdc_content_unit osdc_content_blue">元</span>
        </p>
    </div>

</div>
<!--  数据统计 end -->

<!--  操作按钮 start -->
<div id="act-btn" style="margin:20px 0 10px 0;display: flex;justify-content: space-between;">
    <button class="btn btn-write btn-refresh"><i class="fa fa-refresh"></i> 刷新</button>
    <button id="export" data-value="goods" class="btn btn-write btn-undo" style="margin-right: 0"><i class="fa fa-download"></i> 导出</button>
</div>
<!--  操作按钮 end -->

<!-- 列表start  -->
<div id="table">
    <table class="no-border-table" data-url="/finance/goods/table">
        <thead>
        <tr class="table-head">
            {{--<td class="finance_order_checkbox_first_td">--}}
                {{--@component('component/checkbox',['checkbox'=>['全选'],'name'=>['checkall'],'custom_class'=>"checkall checkall1 checkbox",'right_distance'=>10])--}}
                {{--@endcomponent--}}
            {{--</td>--}}
            <td style="width: 11%">货号</td>
            <td style="width: 30%">名称</td>
            <td style="width: 30%">属性</td>
            <td style="width: 6%">数量</td>
            <td style="width: 6%">价格</td>
            <td style="width: 6%">金额</td>
            <td style="width: 11%">占比</td>
        </tr>
        </thead>
        <tbody class="tbl-content">
        {{--将变量以h5形式输出--}}
            {{--{!! $table_view !!}--}}
        </tbody>
    </table>
    @component('component/paginate',['limit' => \Config::get('pageLimit')])
    @endcomponent

</div>
<!-- 列表end    -->
<script src="{{ URL::asset('js/agent/finance/index.js')}}"></script>