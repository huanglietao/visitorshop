<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
    <tr>
		<td>{{$v['cust_lv_name']}}</td>
		<td>{{$v['cust_lv_desc']}}</td>
		<td>{{$v['cust_lv_discount']}}</td>
		<td>{{$v['cust_lv_score']}}</td>
		<td>
			<p>
                <span class="oa_operate op_tbl btn-dialog" data-area="['70%', '70%']" data-url="{{URL::asset('/user/grade/form')}}?id={{$v['cust_lv_id']}}" data-title = "编辑">编辑</span>
                <span class="btn-del oa_operate op_tbl" data-url="{{URL::asset('/user/grade/del/'.$v['cust_lv_id'])}}" data-title="删除记录" data-text="您确认要删除此记录吗?" style="">删除</span>
			</p>
		</td>
    </tr>
    @empty
    <tr>
        <td colspan=5>暂无记录</td>
    </tr>
@endforelse
