<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
    @inject('CommonPresenter','App\Presenters\CommonPresenter')
    <input hidden type="text" class="export"/>
    <tr>
        {{--<td class="finance_order_checkbox_first_td">--}}
            {{--@component('component/checkbox',['checkbox'=>[''],'name'=>['checkbox'],'custom_class'=>"checkedres checkbox",'right_distance'=>10])--}}
            {{--@endcomponent--}}
        {{--</td>--}}
        <td style="padding-right: 1%">{{$v['order_no']}}</td>
        <td>{{$v['order_relation_no']}}</td>
        <td>{{$CommonPresenter->exchangeTime($v['order_create_time'])}}</td>
        <td>{{$v['rcv_user']}}</td>
        <td>{{$v['rcv_address']}}</td>
        <td>{{$v['rcv_mobile']}}</td>
        <td>{{$v['order_amount']}}</td>
        <td>{{$v['pay_name']}}</td>
        <td>已付款</td>
        <td>{{$v['express_name']}}</td>
        <td>{{$v['delivery_code']}}</td>
        <td>{{$CommonPresenter->exchangeTime($v['shipping_time'])}}</td>
        <td style="text-align: left">{{$v['product_info']}}</td>
        <td>{{$v['shop_info']}}</td>
        <td>{{$v['cha_info']}}</td>
        <td>{{$v['sp_info']}}</td>
    </tr>
@empty
    <tr>
        <td colspan=16>暂无记录</td>
    </tr>
@endforelse
