<!-- table列表数据显示  -->
@forelse($list as $k=>$v)
	@inject('CommonPresenter','App\Presenters\CommonPresenter');
	<tr>
		<td>{{$v['dms_adm_username']}}</td>
		<td>{{$v['dms_real_name']}}</td>
		<td>{!! $groupList[$v['dms_adm_group_id']] !!}</td>
		<td>{{$v['dms_adm_email']}}</td>
		<td>
			<span @if($v['dms_adm_status'] == PUBLIC_ENABLE) style="color:green" @else style="color:red"@endif >
            {{$CommonPresenter->getEnabledOrDisabled($v['dms_adm_status'])}}</span>
		</td>
		<td>{{$v['created_at']}}</td>
		<td>{{$CommonPresenter->exchangeTime($v['dms_adm_logintime'])}}</td>
		<td><p style="margin-top: 17px">
				{{--<span style="cursor:pointer;margin-right: 10px;color: rgba(63, 81, 181, 1)"  data-area="['58%', '600px']" data-url="{{URL::asset('/daily/detail')}}" data-title = "详情" class="btn-dialog oa_operate">日志</span>--}}
				{{--<span style="cursor:pointer;margin-right: 10px;color: rgba(63, 81, 181, 1)" class="oa_operate">权限</span>--}}
				@if($v['is_main'] != 1)
					<span style="cursor:pointer;margin-right: 10px;color: rgba(63, 81, 181, 1)" data-url="{{URL::asset('/auth/admin/form')}}?id={{$v['dms_adm_id']}}" data-title = "编辑" class="oa_operate btn-dialog">编辑</span>
					<span class="btn-del oa_operate" data-url="{{URL::asset('/auth/admin/del/'.$v['dms_adm_id'])}}" data-title="删除记录" data-text="您确定要删除 {{$v['dms_adm_username']}} 管理员账号吗?" style="cursor: pointer;margin-right: 10px;color: rgba(63, 81, 181, 1)">删除</span>
				@else
					<span style="cursor:pointer;margin-right: 10px;color: rgba(63, 81, 181, 1)" data-url="{{URL::asset('/auth/admin/form')}}?id={{$v['dms_adm_id']}}" data-title = "编辑" class="oa_operate btn-dialog">编辑</span>
				@endif
				{{--<span style="cursor:pointer;margin-right: 10px;color: rgba(63, 81, 181, 1)" class="oa_operate">密码</span>--}}
			</p>
		</td>
	</tr>
@empty
	<tr>
		<td colspan=9>暂无记录</td>
	</tr>
@endforelse

