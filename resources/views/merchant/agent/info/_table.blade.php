<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
	@inject('CommonPresenter','App\Presenters\CommonPresenter')
    <tr>
		<td>{{$v['agent_name']}}</td>
		<td>{{$shop_type[$v['agent_type']]}}</td>
		<td>{{$v['cust_lv_name']}}</td>
		<td>{{$v['agent_linkman']}}</td>
		<td>{{$v['mobile']}}</td>
		<td>{{$CommonPresenter->getEnabledOrDisabled($v['agent_status'])}}</td>
		<td>{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>
		<td>{{($info[$v['inviter_id']])}}</td>
		<td>
			<p>
				<span class="oa_operate op_tbl btn-dialog" data-area="['70%', '70%']" data-url="{{URL::asset('/agent/info/form')}}?id={{$v['agent_info_id']}}" data-title = "编辑">编辑</span>
				<span class="oa_operate op_tbl btn-dialog" data-area="['70%', '70%']" data-url="{{URL::asset('/agent/info/capital')}}?id={{$v['agent_info_id']}}" data-title = "充值">充值</span>
				<span class="oa_operate op_tbl"><a  href="/agent/fund?user_id={{$v['agent_info_id']}}" style="color: rgba(63, 81, 181, 1)">资金明细</a> </span>
				<span class="btn-del oa_operate op_tbl" data-url="{{URL::asset('/agent/info/del/'.$v['agent_info_id'])}}" data-title="删除记录" data-text="您确认要删除此记录吗?" style="">删除</span>
			</p>
		</td>
    </tr>
    @empty
    <tr>
        <td colspan=7>暂无记录</td>
    </tr>
@endforelse
