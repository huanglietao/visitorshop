<!-- table列表数据显示  -->
@forelse  ($workList as $k=>$v)
	@inject('CommonPresenter','App\Presenters\CommonPresenter')
	<tr class="cart_tr">
		<td class="o_attr_goods">

			<div class="o_works_info">

				<div class="o_works_img sc_works_img">
					<img src="{{$v['prod_image']}}"  onerror="this.src='/images/home/moren.jpg'">
				</div>
				<div class="s_works_detail ">
					<p class="s_works_name" style="width:100%; margin-bottom: 8px!important;word-break: break-all;">{{$v['prod_name']}}</p>
					<p class="o_works_spec" style="width:100%;margin-bottom: 8px!important;word-break: break-all;">{{$v['prod_attr']}}</p>
				</div>

			</div>

		</td>
	</tr>

	@if($v['prod_cate_flag'] == GOODS_MAIN_CATEGORY_PRINTER)
		@if($v['waiting_make']>0)
			<tr class="cart_tr">
			<td colspan=20 style="text-align: center;">共<span style="color: red;font-size: 16px;">{{$v['prod_num']}}</span>本,还有 <span style="color: red;font-size: 16px;">{{$v['waiting_make']}}</span> 本此定制产品待设计 </td>
			</tr>
		@elseif($v['already_submit']==$v['prod_num'])
			<tr class="cart_tr">
				<td colspan=20 style="text-align: center;"> <span style="color: red;font-size: 16px;">{{$v['already_submit']}}</span>/{{$v['prod_num']}}本 </td>
			</tr>
		@endif
		@endif
		@php $unique = uniqid(); @endphp
		@foreach($v['work_info'] as $kk => $vv)

			<tr class="cart_tr">
				<td class="s_works_spec">
					<input type="hidden" class="order_no" value="{{$order_no}}">
					<input type="hidden" class="project_all_num" value="{{$v['prod_num']}}">
					<input type="hidden" class="agent_id" value="{{$agent_id}}">
					<input type="hidden" class="prj_info_id" value="{{$vv['prj_info_id']}}">
					<input type="hidden" class="unique" value="{{$unique}}">
					<div style="text-align: right;padding-right: 10px;margin-bottom: 5px">
						<span style="color: #9e9e9e;">
							@if(!empty($vv['updated_at']))
								编辑时间:{{date('m/d H:i:s',$vv['updated_at'])}}
							@endif
						</span>
					</div>
					<div style="display: flex;line-height: inherit;position: relative;">
						@if(empty($is_order))
						<div style="display: flex;width: 60%"> <input class="s_num_mobile s_num_input works_num_{{$vv['prj_info_id']}} works_num_{{$unique}}" data-defalut-value="{{$vv['ord_quantity']}}" style="color: red;width: 25px;text-align: center;margin-top: -2px" value="{{$vv['ord_quantity']}}">&nbsp;</input> 本/&nbsp;作品编号:{{$vv['prj_sn']}}</div>
						@else
							<span style="display:inline-block;width: 60%"> <span style="color: red;">{{$vv['ord_quantity']}}&nbsp;</span> 本/&nbsp;作品编号:{{$vv['prj_sn']}}</span>
						@endif
						@if($vv['prj_status'] == WORKS_DIY_STATUS_MAKING)
							<span>制作中</span>
						@elseif($vv['prj_status'] == WORKS_DIY_STATUS_WAIT_CONFIRM)
							<span>已提交</span>
						@elseif($vv['prj_status'] == WORKS_DIY_STATUS_ORDER)
							<span>已下单</span>
						@endif
							<div class="mobile-work-operate">
						@if($vv['prj_status'] == WORKS_DIY_STATUS_MAKING)
								<a class="diy-work-operate diy-work-edit" target="_blank" href="http://{{$agent_url}}/ds_m/index.html?w={{$vv['prj_id']}}&sp={{$mch_id}}&a={{$agent_id}}&order_no={{$order_no}}"  >编辑 &nbsp;&nbsp;</a>
								<a class="diy-work-operate diy-work-del" style="cursor: pointer" data-url = "/works/diy_delete/{{$vv['prj_id']}}" data-order-no = {{$order_no}} data-agent-id={{$agent_id}}>删除</a>
						@elseif($vv['prj_status'] == WORKS_DIY_STATUS_WAIT_CONFIRM)
								<a class="diy-work-operate diy-work-edit" target="_blank" href="http://{{$agent_url}}/ds_m/index.html?w={{$vv['prj_id']}}&sp={{$mch_id}}&a={{$agent_id}}&order_no={{$order_no}}"  >预览 &nbsp;&nbsp;</a>
								<a class="diy-work-operate diy-work-del" style="cursor: pointer" data-url = "/works/diy_delete/{{$vv['prj_id']}}" data-order-no = {{$order_no}} data-agent-id={{$agent_id}}>删除</a>
						@else
								<a class="diy-work-operate diy-work-edit" target="_blank" href="http://{{$agent_url}}/ds_m/index.html?w={{$vv['prj_id']}}&sp={{$mch_id}}&a={{$agent_id}}&order_no={{$order_no}}"  >预览</a>
						@endif
							</div>
					</div>


				</td>
			</tr>

		@endforeach
	<tr class="cart_tr" style="border-bottom: 1px solid #F5F5F5;">
		@if($v['prod_cate_flag'] == GOODS_MAIN_CATEGORY_PRINTER)
			@if($v['already_submit'] == $v['prod_num'])
				<td style="text-align: center"><p style="color: rgba(0, 171, 103, 1)">已完成</p></td>
			@elseif($v['waiting_make']>0)
				@if($v['is_single'])
					<td style="text-align: center"><a class="mobile-go-making" href="http://{{$agent_url}}/ds_m/index.html?sp={{$mch_id}}&a={{$agent_id}}&g={{$v['prod_id']}}&p={{$v['sku_id']}}&t={{$v['temp_id']}}&pc={{$v['pc']}}&order_no={{$order_no}}" target="_blank">开始设计</a></td>
				@else
					<td style="text-align: center"><a href="/goods/detail/template?mid={{$mch_id}}&aid={{$agent_id}}&prod_id={{$v['prod_id']}}&prod_attr_comb={{$v['prod_attr_comb']}}&order_no={{$order_no}}" class="mobile-go-making">开始设计</a></td>
				@endif

			@endif

		@else
			@if($v['isset_sku'] == 1)
				<td style="text-align: center"><p style="color: rgba(0, 171, 103, 1)">已完成</p></td>
			@else
				<td style="text-align: center"><p style="color:rgba(242, 17, 17, 1)">该货品不存在</p></td>
			@endif
		@endif
	</tr>

@empty
    <tr>
        <td colspan=20 style="text-align: center;">暂无记录</td>
    </tr>
@endforelse
{{--第一个tr--}}






