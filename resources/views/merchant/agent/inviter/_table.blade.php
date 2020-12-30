<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
	@inject('CommonPresenter','App\Presenters\CommonPresenter')
    <tr>
		<td>{{$v['order_no']}}</td>
		<td>{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>
		<td>{{$v['nums']}} 件</td>
		<td>{{$CommonPresenter->exchangeOrderStatus($v['order_status'])}}</td>
		<td>￥{{$v['order_real_total']}}</td>
		<td>{{$v['agent_name']}}【{{$v['cha_name']}}】</td>
		<td>{{$inviters[$v['user_id']]}}</td>
    </tr>
    @empty
    <tr>
        <td colspan=7>暂无记录</td>
    </tr>
@endforelse
