<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
	@inject('CommonPresenter','App\Presenters\CommonPresenter');
    <tr>
		<td>{{$v['outer_order_no']}}</td>
		<td>@if(isset($goodsType[$v['goods_type']])) {!! $goodsType[$v['goods_type']] !!} @else 无 @endif </td>
		<td>{{$v['order_no']}}</td>
		<td>
			<select name="sync_status" class="sync_status" data-id="{{$v['sync_queue_id']}}">
				@foreach($queueStatusList as $ck=>$cv)
					<option value={{$ck}} @if($ck == $v['sync_status']) selected @endif >{{$cv}}</option>
				@endforeach
			</select>
		</td>
		<td>{{$v['error_msg']}}</td>
		<td>{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>
		<td>@if(isset($agentList[$v['agent_id']])){!! $agentList[$v['agent_id']] !!} @else 无 @endif</td>
    </tr>
    @empty
    <tr>
        <td colspan=8>暂无记录</td>
    </tr>
@endforelse
