<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
    <tr>
		<td>{{$v['mid']}}</td>
		<td>{{$v['username']}}</td>
		<td>{{$v['nickname']}}</td>
		<td>{{$v['password']}}</td>
		<td>{{$v['salt']}}</td>
		<td>{{$v['is_main']}}</td>
		<td>{{$v['token']}}</td>
		<td>{{$v['avatar']}}</td>
		<td>{{$v['created_at']}}</td>
		<td>{{$v['updated_at']}}</td>

		<td>
		<p >
            <span class="oa_operate op_tbl btn-dialog" data-area="['65%', '70%']" data-url="{{URL::asset('/test/form')}}?id={{$v['id']}}" data-title = "编辑">编辑</span>
            <span class="btn-del oa_operate op_tbl" data-url="{{URL::asset('/test/del')}}?id={{$v['id']}}" data-title="删除记录" data-text="您确认要删除此记录吗?" style="">删除</span>
        </p>
		</td>
    </tr>
    @empty
    <tr>
        <td colspan=11>暂无记录</td>
    </tr>
@endforelse
