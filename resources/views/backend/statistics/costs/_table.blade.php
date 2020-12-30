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
        <td>{{$v['order_item_no']}}</td>
        <td>{{$v['express_name']}}</td>
        <td>{{$v['delivery_code']}}</td>
        <td>{{$v['rcv_address']}}</td>
        <td>{{$v['product_name']}}</td>
        <td>{{$v['product_sku_sn']}}</td>
        <td>{{$v['product_process_code']}}</td>
        <td>{{$v['product_attr']}}</td>
        <td>{{$v['product_nums']}}</td>
        <td>{{$v['unit_cost']}}</td>
        <td>{{$v['product_page_num']}}</td>
{{--        <td>{{$v['express_fee']}}</td>--}}
        <td>{{$v['product_cost']}}</td>
        <td>{{$CommonPresenter->exchangeTime($v['shipping_time'])}}</td>
        <td>{{$CommonPresenter->exchangeTime($v['order_create_time'])}}</td>
        <td>{{$v['sp_info']}}</td>
        <td>{{$v['cha_info']}}</td>
        <td>{{$v['shop_info']}}</td>
    </tr>
@empty
    <tr>
        <td colspan=18>暂无记录</td>
    </tr>
@endforelse
