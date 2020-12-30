<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
	@inject('CommonPresenter','App\Presenters\CommonPresenter');
    <tr>
		<td>{{$v['project'][0]['prj_sn']}}</td>
		<td>{{$v['order_no']}}</td>
		<td>{{$v['project_sn']}}</td>
		<td>
			<select name="comp_queue_status" class="comp_queue_status" data-id="{{$v['comp_queue_id']}}">
				@foreach($queueStatusList as $ck=>$cv)
					<option value={{$ck}} @if($ck == $v['comp_queue_status']) selected @endif >{{$cv}}</option>
				@endforeach
			</select>
		</td>
		<td>@if($v['error_msg']=='null') 无@else{{$v['error_msg']}} @endif</td>
		<td>{{$CommonPresenter->exchangeTime($v['start_time'])}}</td>
		<td>{{$CommonPresenter->exchangeTime($v['end_time'])}}</td>
		<td>
			<p >
				<span class="oa_operate op_tbl btn-dialog" data-area="['60%', '60%']" data-url="{{URL::asset('/queue/compoundqueue/form')}}?id={{$v['comp_queue_id']}}" data-title = "编辑">编辑</span>
			</p>
		</td>
    </tr>
    @empty
    <tr>
        <td colspan=8>暂无记录</td>
    </tr>
@endforelse
