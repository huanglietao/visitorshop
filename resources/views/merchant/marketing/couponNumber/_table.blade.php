<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
    <tr>
		<td>{{$v['cou_name']}}</td>
		<td>{{$v['cou_num_code']}}</td>
		<td>{{$v['cou_num_money']}}</td>
		<td>
            @if($v['cou_num_is_used']==1)未使用
            @elseif($v['cou_num_is_used']==2)已使用
            @endif
        </td>
        <td>{{$v['user_id']}}</td>
        <td>{{$v['order_num']}}</td>
    </tr>
    @empty
    <tr>
        <td colspan=6>暂无记录</td>
    </tr>
@endforelse
