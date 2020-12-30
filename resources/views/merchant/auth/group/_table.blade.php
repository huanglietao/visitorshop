<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
    @inject("CommonPresenter",'App\Presenters\CommonPresenter');
    <tr>
        <td>{{$v['oms_group_name']}}</td>
        <td>{{$v['created_at']}}</td>
        <td> <span @if($v['oms_group_status']==1) style="color:green" @else style="color:red"@endif >
            {{$CommonPresenter->getEnabledOrDisabled($v['oms_group_status'])}}</span>
        </td>
		<td>
		<p >
            @if($v['oms_group_id']!=1)
            <span class="oa_operate op_tbl btn-dialog" data-area="['70%', '70%']" data-url="{{URL::asset('/auth/group/form')}}?id={{$v['oms_group_id']}}" data-title = "编辑">编辑</span>
            <span class="btn-del oa_operate op_tbl" data-url="{{URL::asset('/auth/group/del/'.$v['oms_group_id'])}}" data-title="删除记录" data-text="您确认要删除此记录吗?" style="">删除</span>
            @endif
        </p>
		</td>
    </tr>
    @empty
    <tr>
        <td colspan=4>暂无记录</td>
    </tr>
@endforelse
