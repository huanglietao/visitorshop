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
        <td>{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>
        <td>{{$v['express_name']}}</td>
        <td class="logi_detail">{{$v['delivery_code']}}</td>
        <td>{{$CommonPresenter->exchangeTime($v['order_shipping_time'])}}</td>
        <td>{{$v['num']}}</td>
        <td>{{$v['nums']}}</td>
        <td>{{$v['area']}}</td>
        <td>{{$CommonPresenter->exchangeOrderStatus($v['order_status'])}}</td>
        <td>{{$v['order_exp_fee']}}</td>
        <td>{{$v['order_real_total']}}</td>
        <td class="gdp">{{$v['mix_exp_fee']}}%</td>
    </tr>
@empty
    <tr>
        <td colspan=12>暂无记录</td>
    </tr>
@endforelse


