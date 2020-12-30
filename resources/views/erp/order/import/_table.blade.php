<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
    <tr>
		<td>{{$v['partner_number']}}</td>
		<td>{{$v['partner_order_date']}}</td>
		<td>
			@if($v['is_collect']==1)是
			@elseif($v['is_collect']==2)否
			@endif
		</td>
		<td>{{$v['partner_real_name']}}</td>
		<td>{{$v['product_name']}}</td>
		<td>{{$v['single_num']}}</td>
		<td>
            @if($v['assign_express_type']=='yto')圆通
            @elseif($v['assign_express_type']=='sto')申通
            @elseif($v['assign_express_type']=='zto')中通
            @elseif($v['assign_express_type']=='yunda')韵达
            @elseif($v['assign_express_type']=='best')百世
            @elseif($v['assign_express_type']=='sfj')顺丰寄
			@elseif($v['assign_express_type']=='sfd')顺丰到
            @elseif($v['assign_express_type']=='ems')邮政
            @elseif($v['assign_express_type']=='since')自提
            @elseif($v['assign_express_type']=='other')其他
            @endif
        </td>
		<td>{{$v['recipient_person']}}</td>
		<td>{{$v['recipient_phone']}}</td>
		<td>{{$v['recipient_address']}}</td>
		<td>{{$v['sender_person']}}</td>
		<td>{{$v['sender_phone']}}</td>
		<td>{{$v['sender_address']}}</td>
		<td>
            @if($v['status']=='success')<span style="color:green">成功</span>
            @elseif($v['status']=="error")<span style="color:red"> {{$v['err_msg']}}</span>
            @endif
        </td>
		<td>{{$v['note']}}</td>
		<td>
			<p>
				<span class="oa_operate op_tbl btn-dialog" data-area="['65%', '70%']" data-url="{{URL::asset('/import/form')}}?id={{$v['id']}}" data-title = "编辑">编辑</span>
			</p>
		</td>
    </tr>
    @empty
    <tr>
        <td colspan=16>暂无记录</td>
    </tr>
@endforelse
