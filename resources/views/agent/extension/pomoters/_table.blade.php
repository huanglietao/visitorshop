<!-- table列表数据显示  -->
@forelse($list as $k=>$v)
	@inject('CommonPresenter','App\Presenters\CommonPresenter');
	<tr>
		<td>{{$v['agent_name']}}</td>
		<td>{{$v['inviter_code']}}</td>
		<td>{{$v['order']}}</td>
		<td>{{$v['amount']}}</td>
		<td>{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>
	</tr>
@empty
	<tr>
		<td colspan=5>暂无记录</td>
	</tr>
@endforelse


