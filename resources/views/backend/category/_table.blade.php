<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
    <tr>
		<td style="text-align: left;">{!! $v['cate_name'] !!}</td>

		<td>{{$v['cate_parent_value']}}</td>
		<td>{{$v['cate_unit']}}</td>

		<td>
            @if($v['cate_status']==1)<span style="color:green">启用</span>
            @elseif($v['cate_status']==0)<span style="color:red">禁用</span>
            @endif</td>

		<td>{{date("Y-m-d H:i:s",$v['created_at'])}}</td>
		<td> @if(!$v['updated_at']) - @else{{date("Y-m-d H:i:s",$v['updated_at'])}}@endif</td>

		<td style="vertical-align: bottom;">
		<p >
            <span class="oa_operate op_tbl btn-dialog" data-area="['65%', '70%']" data-url="{{URL::asset('/category/form')}}?id={{$v['cate_id']}}" data-title = "编辑">编辑</span>
            @if($cate_type!="goods")
            <span class="btn-del oa_operate op_tbl" data-url="{{URL::asset('/category/del')}}?id={{$v['cate_id']}}" data-title="删除记录" data-text="您确认要删除此记录吗?" style="">删除</span>
            @endif
        </p>
		</td>
    </tr>
    @empty
    <tr>
        <td colspan=12>暂无记录</td>
    </tr>
@endforelse
