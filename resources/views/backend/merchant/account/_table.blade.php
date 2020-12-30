<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
	@inject('CommonPresenter','App\Presenters\CommonPresenter');
	<tr>
		<td><img src="@if(!empty($v['oms_adm_avatar'])){{$v['oms_adm_avatar']}} @else /images/defaultHead.png @endif" onerror="this.src='/images/defaultHead.png'" style="width: 50px;height: 50px;margin-top: 5px;margin-bottom: 5px;"></td>
		<td>{{$v['oms_adm_username']}}</td>
		<td>{{$v['oms_adm_nickname']}}</td>
		<td>{!! $groupList[$v['oms_adm_group_id']] !!}</td>
		<td>
			<span @if($v['oms_adm_status']==1) style="color:green" @else style="color:red"@endif >
            {{$CommonPresenter->getEnabledOrDisabled($v['oms_adm_status'])}}</span>
		</td>
		<td>{{$CommonPresenter->exchangeTime($v['oms_adm_logintime'])}}</td>
		<td>{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>
		<td>
			<p style="margin:4px 0 ;padding:0">
				<span class="oa_operate op_tbl btn-dialog" data-area="['70%', '70%']" data-url="{{URL::asset('/merchant/account/form')}}?id={{$v['oms_adm_id']}}" data-title = "编辑">编辑</span>
				<span class="btn-del oa_operate op_tbl" data-url="{{URL::asset('/merchant/account/del/'.$v['oms_adm_id'])}}" data-title="删除记录" data-text="您确认要删除此记录吗?" style="">删除</span>
			</p>
		</td>
    </tr>
    @empty
    <tr>
        <td colspan=7>暂无记录</td>
    </tr>
@endforelse
