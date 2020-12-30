<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
	@inject('CommonPresenter','App\Presenters\CommonPresenter');
    <tr>
		<td>{{$v['morder'][0]['order_no']}}</td>
		<td>@if($v['produce_queue_type']==ONE)自动  @else 手动 @endif </td>
		<td>
			<select name="produce_queue_status" class="produce_queue_status" data-id="{{$v['produce_queue_id']}}">
				@foreach($queueStatusList as $ck=>$cv)
					<option value={{$ck}} @if($ck == $v['produce_queue_status']) selected @endif >{{$cv}}</option>
				@endforeach
			</select>
		</td>
		<td>{{$v['produce_queue_err_msg']}}</td>
		<td>{{$CommonPresenter->exchangeTime($v['start_time'])}}</td>
		<td>{{$CommonPresenter->exchangeTime($v['end_time'])}}</td>
		<td>
			<p >
				<span class="oa_operate op_tbl btn-dialog" data-area="['60%', '60%']" data-url="{{URL::asset('/queue/opqueue/form')}}?id={{$v['produce_queue_id']}}" data-title = "编辑">编辑</span>
			</p>
		</td>
    </tr>
    @empty
    <tr>
        <td colspan=8>暂无记录</td>
    </tr>
@endforelse
