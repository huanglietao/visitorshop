<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
    @inject("CommonPresenter",'App\Presenters\CommonPresenter');
    <tr>
        <td>{{$v['cms_group_id']}}</td>
        <td>{{$v['cms_group_name']}}</td>
        <td>{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>
        <td> <span @if($v['cms_group_status']==1) style="color:green" @else style="color:red"@endif >
            {{$CommonPresenter->getEnabledOrDisabled($v['cms_group_status'])}}</span>
        </td>
		<td>
		<p style="margin:4px 0;">
            @if($v['cms_group_id']!=1)
            <span class="oa_operate op_tbl btn-dialog" data-area="['65%', '70%']" data-url="{{URL::asset('/auth/cmsauthgroup/form')}}?id={{$v['cms_group_id']}}" data-title = "编辑">编辑</span>
            <span class="btn-del oa_operate op_tbl" data-url="{{URL::asset('/auth/cmsauthgroup/del/'.$v['cms_group_id'])}}" data-title="删除记录" data-text="您确认要删除此记录吗?" style="">删除</span>
            @endif
        </p>
		</td>
    </tr>
    @empty
    <tr>
        <td colspan=7>暂无记录</td>
    </tr>
@endforelse
