<!-- table列表数据显示  -->
<div class="container" style="width: 100%">
	<div class="c-main c-main-{{$unique}}" >

		<div id="table">
			<input type="hidden" class="is_order" value="{{$is_order}}">
			<table class="no-border-table">
				<thead>
				<tr class="s_header_tr"></tr>
				<tr class="table-head">
					<td width="3%"></td>
					<td width="42%">
						<span>作品名称</span>
					</td>
					<td width="18%">数量</td>
					<td width="12%">状态</td>
					<td width="10%">编辑时间</td>
					{{--<td>标签</td>--}}
					<td width="15%">操作</td>
				</tr>
				<tr class="s_header_tr"></tr>
				</thead>
				<tbody class="wt-tbl-content">
				@forelse  ($workList as $k=>$v)
					<tr class="cart_tr">

						<td class="first_td" >
							{{--	@component('component/checkbox',['checkbox'=>[''],'name'=>['checkbox[]'],'custom_class'=>"cc_checkedres checkbox",'right_distance'=>10])
                                @endcomponent--}}
						</td>
						<td class="o_attr_goods">

							<div class="o_works_info">

								<div class="o_works_img sc_works_img">
									<img src="{{$v['prj_image']}}"  onerror="this.src='/images/home/moren.jpg'">
								</div>
								<div class="s_works_detail ">
									<p class="s_works_name" style="margin-bottom: 8px!important;">{{$v['prj_name']}}</p>
									<p class="o_works_spec" style="margin-bottom: 8px!important;">作品编号:{{$v['prj_sn']}}</p>
								</div>

							</div>

						</td>
						@if(empty($is_order))
							<td class="o_attr s_works_spec d_d_o_works_spec_info ">
								<input type="hidden" class="order_no" value="{{$order_no}}">
								<input type="hidden" class="project_all_num" value="{{$projects_all_num}}">
								<input type="hidden" class="agent_id" value="{{$agent_id}}">
								<input type="hidden" class="prj_info_id" value="{{$v['prj_info_id']}}">
								<input type="hidden" class="unique" value="{{$unique}}">
								<div class="s_works_num_main">
									<div class="s_reduce" data-value="reduce">
										<i class="fa fa-minus s_compute"></i>
									</div>
									<div class="s_num_d">
										<input class="s_num_input works_num_{{$v['prj_info_id']}} works_num_{{$unique}}" data-defalut-value="{{$v['ord_quantity']}}" type="text" name="" id="" value="{{$v['ord_quantity']}}">
									</div>
									<div class="s_addition" data-value="addition">
										<i class="fa fa-plus s_compute"></i>
									</div>
								</div>
							</td>
						@else
							<td class="o_attr s_works_spec d_d_o_works_spec_info ">
								<p class="s_o_works_spec">{{$v['ord_quantity']}}</p>
							</td>
						@endif


						<td class="o_attr">
							<div class="s_works_detail ">
								<p class="o_works_spec" style="margin-bottom: 8px!important;">
								@if($v['prj_status'] == WORKS_DIY_STATUS_MAKING)
									制作中
									@elseif($v['prj_status'] == WORKS_DIY_STATUS_WAIT_CONFIRM)
									已提交
									@elseif($v['prj_status'] == WORKS_DIY_STATUS_ORDER)
									已下单
									@endif
								</p>
							</div>
						</td>
						<td class="o_attr">
							{{date('Y/m/d H:i:s',$v['updated_at'])}}
						</td>
						<td class="o_attr s_works_spec d_d_o_works_spec_info " style="margin: 10px 5px 10px 0;">
							@if($v['prj_status'] == WORKS_DIY_STATUS_MAKING)
								@if($v['is_single'])
									<a class="diy-work-operate diy-work-edit" href="/printer/index.html?w={{$v['prj_id']}}&sp={{$mch_id}}&a={{$agent_id}}&order_no={{$order_no}}" target="_blank">编辑</a>
								@else
									<a class="diy-work-operate diy-work-edit" target="_blank" href="http://{{$agent_url}}/ds/ed.html?w={{$v['prj_id']}}&sp={{$mch_id}}&a={{$agent_id}}&order_no={{$order_no}}"  >编辑 &nbsp;&nbsp;</a>
								@endif
								<a class="diy-work-operate diy-work-del" style="cursor: pointer" data-url = "/works/diy_delete/{{$v['prj_id']}}" data-order-no = {{$order_no}} data-agent-id={{$agent_id}}>删除</a>
							@elseif($v['prj_status'] == WORKS_DIY_STATUS_WAIT_CONFIRM)
								@if($v['is_single'])
									<a class="diy-work-operate diy-work-edit" href="/printer/index.html?w={{$v['prj_id']}}&sp={{$mch_id}}&a={{$agent_id}}&order_no={{$order_no}}" target="_blank">预览</a>
								@else
									<a class="diy-work-operate diy-work-edit" target="_blank" href="http://{{$agent_url}}/ds/ed.html?w={{$v['prj_id']}}&sp={{$mch_id}}&a={{$agent_id}}&order_no={{$order_no}}"  >预览</a>
								@endif
								<a class="diy-work-operate diy-work-del" style="cursor: pointer" data-url = "/works/diy_delete/{{$v['prj_id']}}" data-order-no = {{$order_no}} data-agent-id={{$agent_id}}>删除</a>
							@else
								@if($v['is_single'])
									<a class="diy-work-operate diy-work-edit" href="/printer/index.html?w={{$v['prj_id']}}&sp={{$mch_id}}&a={{$agent_id}}&order_no={{$order_no}}" target="_blank">预览</a>
								@else
									<a class="diy-work-operate diy-work-edit" target="_blank" href="http://{{$agent_url}}/ds/ed.html?w={{$v['prj_id']}}&sp={{$mch_id}}&a={{$agent_id}}&order_no={{$order_no}}"  >预览</a>
								@endif
							@endif
						</td>
					</tr>
				@empty
					<tr>
						<td colspan=20 style="padding:20px 0;text-align: center;">暂无记录</td>
					</tr>
				@endforelse

				</tbody>

			</table>
			<div class="form-group layer-footer">
				<label class="control-label col-xs-12 col-sm-2"></label>
				<div class="col-xs-12 col-sm-8">
					<button type="submit" class="btn diy-wt-sure btn-3F51B5 btn-sure btn-submit" data-num="{{$unique}}">确定</button>
					&nbsp;&nbsp;&nbsp;&nbsp;
				</div>
			</div>
		</div>
	</div>
</div>






