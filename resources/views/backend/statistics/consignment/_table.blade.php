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
        <td>{{$v['order_amount']}}</td>
        <td>{{$v['cha_info']}}</td>
        <td>{{$v['shop_info']}}</td>
        <td>{{$CommonPresenter->exchangeTime($v['submit_time'])}}</td>
        <td>{{$CommonPresenter->exchangeTime($v['shipping_time'])}}</td>
        <td>{{$v['consign']}}</td>
    </tr>
@empty
    <tr>
        <td colspan=7>暂无记录</td>
    </tr>
@endforelse
