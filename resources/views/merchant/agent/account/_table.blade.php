<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
    @inject('CommonPresenter','App\Presenters\CommonPresenter');
    <tr>
		<td>
            <img src="{{$v['dms_adm_avatar']}}" style="width: 50px;height: 50px;margin-top: 5px;margin-bottom: 5px;">
        </td>
		<td>{{$v['dms_adm_username']}}</td>
		<td>{{$oms_adm_username}}</td>
		<td>{{$v['dms_adm_nickname']}}</td>
        <td>{!! $groupList[$v['dms_adm_group_id']] !!}</td>
		<td>
            <span @if($v['dms_adm_status']==PUBLIC_ENABLE) style="color:green" @else style="color:red"@endif >
                {{$CommonPresenter->getEnabledOrDisabled($v['dms_adm_status'])}}
            </span>
        </td>
        <td>{{$CommonPresenter->exchangeTime($v['dms_adm_logintime'])}}</td>
        <td>{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>
		<td>
            <p>
                <span class="oa_operate op_tbl btn-dialog" data-area="['70%', '70%']" data-url="{{URL::asset('/agent/account/form')}}?id={{$v['dms_adm_id']}}" data-title = "编辑">编辑</span>
                <span class="btn-del oa_operate op_tbl" data-url="{{URL::asset('/agent/account/del/'.$v['dms_adm_id'])}}" data-title="删除记录" data-text="您确认要删除此记录吗?" style="">删除</span>
            </p>
		</td>
    </tr>
    @empty
    <tr>
        <td colspan=8>暂无记录</td>
    </tr>
@endforelse
