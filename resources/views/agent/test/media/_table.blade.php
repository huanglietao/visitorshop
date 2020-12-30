<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
    <tr>
		<td>{{$v['prod_id']}}</td>
		<td>{{$v['prod_md_path']}}</td>
		<td>{{$v['prod_md_ismain']}}</td>
		<td>{{$v['prod_md_type']}}</td>
		<td>{{$v['sort']}}</td>
		<td>{{$v['created_at']}}</td>
		<td>{{$v['updated_at']}}</td>

		<td>
		<p >
            <span class="oa_operate op_tbl btn-dialog" data-area="['65%', '70%']" data-url="{{URL::asset('/test/media/form')}}?id={{$v['prod_md_id']}}" data-title = "编辑">编辑</span>
            <span class="btn-del oa_operate op_tbl" data-url="{{URL::asset('/test/media/del')}}?id={{$v['prod_md_id']}}" data-title="删除记录" data-text="您确认要删除此记录吗?" style="">删除</span>
        </p>
		</td>
    </tr>
    @empty
    <tr>
        <td colspan=8>暂无记录</td>
    </tr>
@endforelse
