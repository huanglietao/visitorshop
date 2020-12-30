<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
	@inject('CommonPresenter','App\Presenters\CommonPresenter');
    <tr>
		<td>{{$username}}</td>
		<td>{{$v['amount']}}</td>
		<td>{{$v['balance']}}</td>
		<td>
			@if($v['money_type']==1)消费
			@elseif($v['money_type']==2)充值
			@endif
		</td>
		<td>{{$v['recharge_no']}}</td>
		<td>{{$v['trade_no']}}</td>
		<td>{{$v['operator']}}</td>
		<td>{{$v['note']}}</td>
		<td>{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>
    </tr>
    @empty
    <tr>
        <td colspan=9>暂无记录</td>
    </tr>
@endforelse
