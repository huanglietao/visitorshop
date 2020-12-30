<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
    @inject('CommonPresenter','App\Presenters\CommonPresenter');
    <tr>
        <td><img src="@if(empty($v['cms_adm_avatar']))/images/defaultHead.png @else{{$v['cms_adm_avatar']}}@endif" style="width: 45px"/></td>
		<td>{{$v['cms_adm_username']}}</td>
		<td>{{$v['cms_adm_nickname']}}</td>
        <td>{{$authGroup[$v['cms_adm_group_id']]}}</td>
        <td>{{$v['cms_adm_email']}}</td>
		<td>
            <span @if($v['cms_adm_status']==ONE) style="color:green" @endif style="color:red" >
                {{$CommonPresenter->getEnabledOrDisabled($v['cms_adm_status'])}}</span>
        </td>
		<td>{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>
		<td>
		<p style="margin:4px 0;">
            <span class="oa_operate op_tbl btn-dialog" data-area="['65%', '70%']" data-url="{{URL::asset('/auth/cmsadmin/form')}}?id={{$v['cms_adm_id']}}" data-title = "编辑">编辑</span>
            @if($v['cms_adm_id']!=ONE)
            <span class="btn-del oa_operate op_tbl" data-url="{{URL::asset('/auth/cmsadmin/del/'.$v['cms_adm_id'])}}" data-title="删除记录" data-text="您确认要删除此记录吗?" style="">删除</span>
            @endif
        </p>
		</td>
    </tr>
    @empty
    <tr>
        <td colspan=7>暂无记录</td>
    </tr>
@endforelse
