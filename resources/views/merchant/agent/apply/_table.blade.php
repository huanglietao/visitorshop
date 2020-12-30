<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
	@inject('CommonPresenter','App\Presenters\CommonPresenter')
    <tr>
		<td>{{$v['agent_name']}}</td>
		<td>{{$shop_type[$v['agent_type']]}}</td>
		<td>{{$v['cust_lv_name']}}</td>
		<td>{{$v['agent_linkman']}}</td>
		<td>{{$v['mobile']}}</td>
		<td>
			@if($v['review_status']=="1")等待审核
			@elseif($v['review_status']=="2")审核通过
			@elseif($v['review_status']=="3")审核不通过
			@endif
		</td>
		<td>{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>
		<td>
				@if($v['review_status']==1||$v['review_status']==3)
					<span class="oa_operate op_tbl btn-dialog" style="margin-right: 0" data-area="['70%', '70%']" data-url="{{URL::asset('/agent/apply/form')}}?id={{$v['agent_apply_id']}}" data-title = "审核">审核</span>
				@endif
				@if($v['is_create_adm']==1 && $v['review_status']==2)
					<span class="oa_operate op_tbl btn-dialog" style="margin-right: 0" data-area="['70%', '70%']" data-url="{{URL::asset('/agent/account/form')}}?agent_info_id={{$v['agent_info_id']}}" data-title = "创建账号">创建账号</span>
				@elseif($v['is_create_adm']==2)
					<span class="btn-del oa_operate op_tbl" style="margin-right: 0" data-url="{{URL::asset('/agent/apply/del/'.$v['agent_apply_id'])}}" data-title="删除记录" data-text="您确认要删除此记录吗?" style="">删除</span>
				@endif

		</td>
    </tr>
    @empty
    <tr>
        <td colspan=7>暂无记录</td>
    </tr>
@endforelse
