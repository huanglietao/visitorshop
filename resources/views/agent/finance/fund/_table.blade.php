<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
    @inject('CommonPresenter','App\Presenters\CommonPresenter')
    <tr>
        <td>{{$v['cus_balance_business_no']}}</td>
        <td>{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>
        <td>{{$CommonPresenter->fundTypeExchange($v['cus_balance_type_detail'])}}</td>
        <td>@if(empty($v['cus_balance_trade_no']))-@else{{$v['cus_balance_trade_no']}}@endif</td>
        <td>@if($v['cus_balance_type'] == FINANCE_INCOME)+@else -@endif{{$v['cus_balance_change']}}</td>
        <td>{{$v['cus_balance']}}</td>
        <td>{{$v['operater_name']}}</td>
        <td>{{$v['remark']}}</td>
        <td>
            <span class="oa_operate op_tbl btn-dialog" data-area="['70%', '70%']" data-url="{{URL::asset('/finance/fund/form')}}?id={{$v['cus_balance_id']}}" data-title="详情">详情</span>

        </td>
    </tr>
@empty
    <tr>
        <td colspan=9>暂无记录</td>
    </tr>
@endforelse


