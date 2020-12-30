<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
	@inject('CommonPresenter','App\Presenters\CommonPresenter')
    <tr>
		<td>{{$v['cou_name']}}</td>
		<td>
			@if($v['cou_type']=="1")卡券
			@elseif($v['cou_type']=="2")优惠码
			@endif
		</td>
		<td>{{$v['cou_denomination']}}</td>
		<td>{{$v['cou_min_consumption']}}</td>
		<td>
			{{$v['sales_chanel_name']}}
		</td>
		<td>{{$v['cou_use_times']}}</td>
		<td>{{$CommonPresenter->exchangeTime($v['cou_start_time'])}}</td>
		<td>{{$CommonPresenter->exchangeTime($v['cou_end_time'])}}</td>
		<td>
			<p>
				<span class="oa_operate op_tbl btn-edit" data-area="['70%', '70%']" data-url="{{URL::asset('/marketing/coupon/form')}}?id={{$v['cou_id']}}" data-title = "编辑">编辑</span>
				@if($v['cou_type']=="2")
					<span class="oa_operate op_tbl"><a  href="/marketing/couponNumber?id={{$v['cou_id']}}" style="color: rgba(63, 81, 181, 1)">详情</a> </span>
				@endif
				<span class="btn-del oa_operate op_tbl" data-url="{{URL::asset('/marketing/coupon/del/'.$v['cou_id'])}}" data-title="删除记录" data-text="您确认要删除此记录吗?" style="">删除</span>
			</p>
		</td>
    </tr>
    @empty
    <tr>
        <td colspan=9>暂无记录</td>
    </tr>
@endforelse
