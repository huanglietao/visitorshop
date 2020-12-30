<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
	@inject('CommonPresenter','App\Presenters\CommonPresenter');
	<tr>
		<td>{{$v['salary_worker_name']}}</td>
		<td>{{$v['position']['name']}}</td>
		<td>{{$v['position']['rate']}}</td>
		{{--<td>{{$v['position']['per_money']}}</td>--}}
		<td>{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>
		<td>
			<p style="margin:4px 0 ;padding:0">
				<span class="oa_operate op_tbl btn-dialog" data-area="['70%', '70%']" data-url="{{URL::asset('/salary/worker/form')}}?id={{$v['salary_worker_id']}}" data-title = "编辑">编辑</span>
				<span class="btn-del oa_operate op_tbl" data-url="{{URL::asset('/salary/worker/del/'.$v['salary_worker_id'])}}" data-title="删除记录" data-text="您确认要删除此记录吗?" style="">删除</span>
			</p>
		</td>
    </tr>
    @empty
    <tr>
        <td colspan=5>暂无记录</td>
    </tr>
@endforelse
