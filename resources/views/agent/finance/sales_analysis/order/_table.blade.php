<!-- table列表数据显示  -->
@forelse($list as $k=>$v)
    @inject('CommonPresenter','App\Presenters\CommonPresenter')
    <input hidden type="text" class="export"/>
    <tr>
        {{--<td class="finance_order_checkbox_first_td" >--}}
            {{--@component('component/checkbox',['checkbox'=>[''],'name'=>['checkbox'],'custom_class'=>"checkedres checkbox",'right_distance'=>10])--}}
            {{--@endcomponent--}}
        {{--</td>--}}
        <td>{{$v['order_no']}}</td>
        <td>{{$CommonPresenter->exchangeOrderStatus($v['order_status'])}}</td>
        <td>{{$v['prod_nums']}}</td>
        <td>{{$v['order_real_total']}}</td>
        <td>{{$v['order_exp_fee']}}</td>
        <td>{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>
        <td>
            <span class="oa_operate op_tbl btn-dialog" data-area="['70%', '70%']" data-url="{{URL::asset('/finance/order/form')}}?order_id={{$v['order_id']}}" data-title="详情">详情</span>
        </td>
    </tr>
@empty
    <tr>
        <td colspan=7>暂无记录</td>
    </tr>
@endforelse

