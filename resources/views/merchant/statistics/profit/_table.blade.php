<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
    @inject('CommonPresenter','App\Presenters\CommonPresenter')
    <input hidden type="text" class="export"/>
    <tr>
        {{--<td class="finance_order_checkbox_first_td" >--}}
            {{--@component('component/checkbox',['checkbox'=>[''],'name'=>['checkbox'],'custom_class'=>"checkedres checkbox",'right_distance'=>10])--}}
            {{--@endcomponent--}}
        {{--</td>--}}
        <td>{{$v['order_no']}}</td>
        <td>{{$CommonPresenter->exchangeTime($v['order_create_time'])}}</td>
        <td>{{$CommonPresenter->exchangeTime($v['shipping_time'])}}</td>
        <td>{{$v['product_nums']}}</td>
        <td>{{$v['product_amount']}}</td>
        <td>{{$v['express_fee']}}</td>
        <td>{{$v['discount_fee']}}</td>
        <td>{{$v['pay_amount']}}</td>
        <td>{{$v['product_cost']}}</td>
        <td>{{$v['express_cost']}}</td>
        <td>{{$v['total_cost']}}</td>
        <td>{{$v['profit']}}</td>
        <td>{{$v['gross_margin']}}%</td>
        <td>{{$v['shop_info']}}</td>
        <td>{{$v['cha_info']}}</td>
    </tr>
@empty
    <tr>
        <td colspan=15>暂无记录</td>
    </tr>
@endforelse
