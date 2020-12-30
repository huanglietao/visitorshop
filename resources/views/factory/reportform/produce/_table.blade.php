<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
    @inject('CommonPresenter','App\Presenters\CommonPresenter')

    <tr>
        <td>@if(isset($v['product'][0]['prod_name'])){{$v['product'][0]['prod_name']}} @else - @endif</td>
        <td>@if(isset($v['prosku'][0]['prod_supplier_sn'])){{$v['prosku'][0]['prod_supplier_sn']}} @else - @endif</td>
        <td>{{$v['agent_name']}}</td>
        <td>{{$v['is_trun']}}</td>
        {{--<td>{{$v['new_sp_order_amount']}}</td>--}}
        <td>{{$v['sp_num']}}</td>
        <td>{{$v['erp_order_no']}}</td>
        <td>{{$v['ord_prj_no']}}</td>
        <td>@if(isset($orders_status[$v['sp_order_status']])){{$orders_status[$v['sp_order_status']]}} @else - @endif</td>
        <td>{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>
    </tr>
@empty
    <tr>
        <td colspan=8>暂无记录</td>
    </tr>
@endforelse
