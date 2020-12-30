<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
    <tr>
		<td><img src="{{$url}}{{$v['avatar']}}" style="width: 45px"/></td>
		<td>{{$v['username']}}</td>
		<td>{{$v['nickname']}}</td>
		<td>{{$groupList[$v['group_id']]}}</td>
		<td>{{$v['email']}}</td>
		@inject("CommonPresenter",'App\Presenters\CommonPresenter');
		<td>{{$CommonPresenter->getNormalOrHidden($v['status'])}}</td>
		<td>{{$v['created_at']}}</td>
		<td>
		<p >
            <span class="oa_operate op_tbl btn-dialog" data-area="['65%', '70%']" data-url="{{URL::asset('/auth/kfusers/form')}}?id={{$v['id']}}" data-title = "编辑">编辑</span>
            <span class="btn-del oa_operate op_tbl" data-url="{{URL::asset('/auth/kfusers/del')}}?id={{$v['id']}}" data-title="删除记录" data-text="您确认要删除此记录吗?" style="">删除</span>
        </p>
		</td>
    </tr>
    @empty
    <tr>
        <td colspan=10>暂无记录</td>
    </tr>
@endforelse
