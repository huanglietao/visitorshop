<!-- table列表数据显示  -->
@forelse($list as $k=>$v)
	@inject('CommonPresenter','App\Presenters\CommonPresenter');
	<tr>
		<td>{{$v['dms_group_name']}}</td>
		<td>
			<span @if($v['dms_group_status'] == PUBLIC_ENABLE) style="color:green" @else style="color:red"@endif >
            {{$CommonPresenter->getEnabledOrDisabled($v['dms_group_status'])}}</span>
		</td>
		<td>{{$v['created_at']}}</td>
		<td>
			@if($v['dms_group_rule'] != '*')
				<p style="margin-top: 17px">
					<span style="cursor:pointer;margin-right: 10px;color: rgba(63, 81, 181, 1)" class="oa_operate btn-dialog" data-url="{{URL::asset('/auth/rule/form')}}?id={{$v['dms_group_id']}}" data-title = "编辑">编辑</span>
					<span class="btn-del oa_operate" data-url="{{URL::asset('/auth/rule/del/'.$v['dms_group_id'])}}" data-title="删除记录" data-text="您确定要删除该角色组吗?" style="cursor: pointer;margin-right: 10px;color: rgba(63, 81, 181, 1)">删除</span>
				</p>
			@endif
		</td>
	</tr>
@empty
	<tr>
		<td colspan=5>暂无记录</td>
	</tr>
@endforelse


