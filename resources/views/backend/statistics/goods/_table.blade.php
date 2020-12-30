<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
    <input hidden type="text" class="export"/>
    <tr>
        {{--<td class="finance_order_checkbox_first_td" >--}}
            {{--@component('component/checkbox',['checkbox'=>[''],'name'=>['checkbox'],'custom_class'=>"checkedres checkbox",'right_distance'=>10])--}}
            {{--@endcomponent</td>--}}
        <td>{{$v['prod_sku_sn']}}</td>
        <td>{{$v['prod_name']}}</td>
        <td>{{$v['prod_attr']}}</td>
        <td>{{$v['prod_num']}}</td>
        <td>{{$v['prod_sku_price']}}</td>
        <td>{{$v['prod_sale_price']}}</td>
        <td class="gdp">{{$v['percentage']}}%</td>
    </tr>
@empty
    <tr>
        <td colspan=7>暂无记录</td>
    </tr>
@endforelse

