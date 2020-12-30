<!-- table列表数据显示  -->
@inject('CommonPresenter','App\Presenters\CommonPresenter');

@forelse  ($list as $k=>$v)
	<tr>
		<td>{{$v['old_order_no']}}</td>
		<td>{{$v['exchange_order_no']}}</td>
		<td>{{$v['job_reason_text']}}</td>
		<td>{{$v['bart_explain']}}</td>
		<td>{{$v['operater']}}</td>
		<td>{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>

		<td><p style="margin-top: 17px">
				<span class="oa_operate op_tbl btn-dialog" data-area="['65%', '70%']" data-url="{{URL::asset('/order/exchange/form')}}?id={{$v['ord_bart_id']}}" data-title = "详情">详情</span>
			</p>
		</td>
	</tr>
@empty
	<tr>
		<td colspan=7>暂无记录</td>
	</tr>
@endforelse


