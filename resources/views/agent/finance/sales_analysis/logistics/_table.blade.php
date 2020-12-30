<!-- table列表数据显示  -->
@forelse($list as $k=>$v)
    @inject('CommonPresenter','App\Presenters\CommonPresenter')
    <input hidden type="text" class="export"/>
    <tr>
        {{--<td class="finance_order_checkbox_first_td" >--}}
            {{--@component('component/checkbox',['checkbox'=>[''],'name'=>['checkbox'],'custom_class'=>"checkedres checkbox",'right_distance'=>10])--}}
            {{--@endcomponent--}}
        {{--</td>--}}
        <td>{{$v['express_name']}}</td>
        <td>{{$v['ordersNum']}}</td>
        <td>{{$v['exp_fee']}}</td>
        <td class="gdp">{{$v['mix']}}%</td>
        <td class="logi_detail">
            <span class="oa_operate op_tbl" id="logistics_detail" data-value="{{$v['express_id']}}">物流明细</span>
        </td>
    </tr>
@empty
    <tr>
        <td colspan=5>暂无记录</td>
    </tr>
@endforelse




