<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
    @inject('CommonPresenter','App\Presenters\CommonPresenter');
    <tr>
		<td>{{$v['rec_rule_name']}}</td>
		<td>{{$v['recharge_fee']}}</td>
		<td>{{$v['present_fee']}}</td>
        <td>{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>
		<td>
		<p >
            <span class="oa_operate op_tbl btn-dialog" data-area="['70%', '70%']" data-url="{{URL::asset('/agent/strategy/rechargerule/form')}}?id={{$v['rec_rule_id']}}" data-title = "编辑">编辑</span>
            <span class="btn-del oa_operate op_tbl" data-url="{{URL::asset('/agent/strategy/rechargerule/del/'.$v['rec_rule_id'])}}" data-title="删除记录" data-text="您确认要删除此记录吗?" style="">删除</span>
        </p>
		</td>
    </tr>
    @empty
    <tr>
        <td colspan=5>暂无记录</td>
    </tr>
@endforelse
