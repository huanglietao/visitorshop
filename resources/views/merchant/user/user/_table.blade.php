<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
	@inject('CommonPresenter','App\Presenters\CommonPresenter');
    <tr>
		<td>
			<img src="{{$v['user_avatar']}}" style="width: 50px;height: 50px;margin-top: 5px;margin-bottom: 5px;">
		</td>
		<td>{{$v['user_name']}}</td>
		<td>{{$v['user_nickname']}}</td>
		<td>{{$gradeList[$v['cust_lv_id']]}}</td>
		<td>{{$CommonPresenter->getEnabledOrDisabled($v['status'])}}</td>
		<td>{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>
		<td>
			<p >
				<span class="oa_operate op_tbl btn-dialog" data-area="['70%', '70%']" data-url="{{URL::asset('/user/user/form')}}?id={{$v['user_id']}}" data-title = "编辑">编辑</span>
				<span class="oa_operate op_tbl"><a  href="/user/money?user_id={{$v['user_id']}}" style="color: rgba(63, 81, 181, 1)">资金变动</a> </span>
				<span class="btn-del oa_operate op_tbl" data-url="{{URL::asset('/user/user/del/'.$v['user_id'])}}" data-title="删除记录" data-text="您确认要删除此记录吗?" style="">删除</span>
			</p>
		</td>
    </tr>
    @empty
    <tr>
        <td colspan=7>暂无记录</td>
    </tr>
@endforelse
