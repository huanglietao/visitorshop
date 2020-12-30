<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
	@inject('CommonPresenter','App\Presenters\CommonPresenter');
    <tr>
		<td>{{$v['score_rule_name']}}</td>
		<td>{{$scoreRule[$v['score_rule_way']]}}</td>
		<td>{{$v['score_rule_score']}}</td>
		<td>{{$CommonPresenter->getEnabledOrDisabled($v['score_rule_status'])}}</td>
		<td>{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>
		<td>
		<p >
            <span class="oa_operate op_tbl btn-dialog" data-area="['70%', '70%']" data-url="{{URL::asset('/user/score/form')}}?id={{$v['score_rule_id']}}" data-title = "编辑">编辑</span>
            <span class="btn-del oa_operate op_tbl" data-url="{{URL::asset('/user/score/del/'.$v['score_rule_id'])}}" data-title="删除记录" data-text="您确认要删除此记录吗?" style="">删除</span>
        </p>
		</td>
    </tr>
    @empty
    <tr>
        <td colspan=6>暂无记录</td>
    </tr>
@endforelse
