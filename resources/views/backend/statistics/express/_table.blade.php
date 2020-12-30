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
        <td>{{$v['shop_info']}}</td>
        <td>{{$v['product_nums']}}</td>
        <td>{{$v['product_weight']}}克</td>
        <td>{{$CommonPresenter->exchangeTime($v['shipping_time'])}}</td>
        <td>{{$CommonPresenter->exchangeTime($v['order_create_time'])}}</td>
        <td>{{$v['sp_info']}}</td>
        <td>{{$v['express_name']}}</td>
        <td>{{$v['delivery_code']}}</td>
        <td>{{$v['express_cost']}}</td>
        <td>{{$v['area_name']}}</td>
        <td>{{$v['collect_weight']}}</td>
    </tr>
@empty
    <tr>
        <td colspan=12>暂无记录</td>
    </tr>
@endforelse
