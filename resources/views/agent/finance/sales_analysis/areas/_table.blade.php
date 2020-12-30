<!-- table列表数据显示  -->
@forelse($list as $k=>$v)
    @inject('CommonPresenter','App\Presenters\CommonPresenter')
    <input hidden type="text" class="export"/>
    <tr>
        {{--<td class="finance_order_checkbox_first_td" >--}}
            {{--@component('component/checkbox',['checkbox'=>[''],'name'=>['checkbox'],'custom_class'=>"checkedres checkbox",'right_distance'=>10])--}}
            {{--@endcomponent--}}
        {{--</td>--}}
        <td>{{$v['area_name']}}</td>
        <td>{{$v['ordersNum']}}</td>
        <td>{{$v['totals']}}</td>
        <td>{{$v['per']}}</td>
        <td class="gdp">{{$v['mix']}}%</td>
    </tr>
@empty
    <tr>
        <td colspan=5>暂无记录</td>
    </tr>
@endforelse

