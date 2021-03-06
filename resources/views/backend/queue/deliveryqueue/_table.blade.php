<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
	@inject('CommonPresenter','App\Presenters\CommonPresenter');
    <tr>
		<td>{{$v['morder'][0]['order_no']}}</td>
		<td>{{$v['delivery_code']}}</td>
		<td>{{$v['delivery_name']}}</td>
		<td>
			<select name="delivery_push_status" class="delivery_push_status" data-id="{{$v['delivery_push_id']}}">
				@foreach($queueStatusList as $ck=>$cv)
					<option value={{$ck}} @if($ck == $v['delivery_push_status']) selected @endif >{{$cv}}</option>
				@endforeach
			</select>
		</td>
		<td>{{$v['error_msg']}}</td>
		<td>{{$CommonPresenter->exchangeTime($v['start_time'])}}</td>
		<td>{{$CommonPresenter->exchangeTime($v['end_time'])}}</td>
		<td>
			<p >
				<span class="oa_operate op_tbl btn-dialog" data-area="['60%', '60%']" data-url="{{URL::asset('/queue/deliveryqueue/form')}}?id={{$v['delivery_push_id']}}" data-title = "编辑">编辑</span>
			</p>
		</td>
    </tr>
    @empty
    <tr>
        <td colspan=9>暂无记录</td>
    </tr>
@endforelse
