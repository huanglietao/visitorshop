<!-- table列表数据显示  -->
@if(!$list)
    <tr>
        <td colspan=18>暂无记录</td>
    </tr>
@endif
@foreach  ($list as $k=>$v)
    <tr>
        <td style="width: 8%">{{$v['order_date']}}</td>
        <td style="width: 5%">{{$v['order_name']}}</td>
        <td style="width: 9%">{{$v['order_print_name']}}</td>
        <td style="width: 5%">{{$v['order_product_name']}}</td>
        <td style="width: 4%">{{$v['order_single_num']}}</td>
        <td style="width: 4%">{{$v['order_uom_name']}}</td>
        <td style="width: 4%">{{$v['order_one_two']}}</td>
        <td style="width: 4%">{{$v['order_all_money']}}</td>
        <td style="width: 4%">{{$v['order_other_money']}}</td>
        <td style="width: 4%">{{$v['order_last_working_all_money']}}</td>
        <td style="width: 4%">{{$v['order_discount_money']}}</td>
        <td style="width: 4%">{{$v['order_total_money']}}</td>
        <td style="width: 5%">{{$v['order_state']}}</td>
        <td style="width: 5%">{{$v['express_type']}}</td>
        <td style="width: 5%">{{$v['express_num']}}</td>
        <td style="width: 5%">{{$v['express_date']}}</td>
        <td style="width: 5%">{{$v['tripartite_serial_number']}}</td>
        <td style="width: 20%">{{$v['order_note']}}</td>
    </tr>
@endforeach