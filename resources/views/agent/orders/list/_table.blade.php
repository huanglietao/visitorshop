<!-- table列表数据显示  -->
@if(!empty($list))
	@foreach  ($list as $k=>$v)
		@inject('CommonPresenter','App\Presenters\CommonPresenter');
		{{--订单开始--}}
		<tr class="o_list_tr">
			<td colspan="8" style="vertical-align: middle; text-align: left">
				<input type="checkbox" name="checkedres" id="checkedres{{$k}}" class="checkedres checkbox" value="{{$v['order_id']}}">
				<label for="checkedres{{$k}}"></label>
				<span class="o_next_span">订单号：<span class="data-text">{{$v['order_no']}}</span></span>
				<span class="o_next_span" >交易时间：<span class="data-text">{{$CommonPresenter->exchangeTime($v['created_at'])}}</span> </span>
				<span class="o_next_span" >总数量：
				<span class="data-text">{{$v['nums']}} 件</span>
					@if($v['total'] > 3)
						<img src="/images/down.png" data-action="show"  class="data-img" alt="" data-val="{{$v['total']}}">
					@endif
			</span>
				<span class="o_next_span" >买家：{{$v['order_rcv_user']}} （{{$v['order_rcv_phone']}}） </span>
				<span class="o_next_span" >交易关联单号：<span class="data-text">{{$v['order_relation_no']}}</span></span>
				<span class="o_next_span" >来源：<span class="data-text">{{$v['agent_name']}}【{{$v['cha_name']}}】</span></span>
			</td>
		</tr>

		@if($v['total'] <= 3 && $v['total'] > 1)
			{{--一订单少于4商品情况--}}
			@foreach ($v['item'] as $info)
				@if($loop->first)
					{{--第一个tr--}}
					<tr>
						<td class="o_attr_goods">
							<div class="o_works_info">
								<div class="o_works_img">
									<img src="{{$info['prod_main_thumb']}}">
								</div>
								<div class="o_works_detail">
									<p class="o_works_name">{{$info['prod_name']}}</p>
									<p class="o_works_spec">{{$info['attr_str']}}</p>
								</div>
							</div>
						</td>
						<td class="o_attr o_attr_price">￥{{$info['prod_sku_price']}}</td>
						<td class="o_attr o_attr_num">{{$info['prod_num']}}</td>
						<td class="o_attr o_attr_sale td_border" rowspan="{{$v['total']}}">{{$CommonPresenter->exchangeProduction($v['order_prod_status'])}}</td>
						<td class="o_attr  o_attr_logistics td_border" rowspan="{{$v['total']}}">
							<span class="o_logistics">{{$CommonPresenter->exchangeOrderStatus($v['order_status'])}}<br>
								@if(!empty($v['delivery_code']))
									<span class="o-car"><img src="/images/c1-car.png" alt=""></span>
									<a class="btn-dialog" data-url="{{URL::asset('/order/list/logistics/'.$v['order_id'])}}" data-title = "物流信息">{{$v['delivery_code']}}</a>
								@endif
							</span>
						</td>
						<td class="o_attr o_attr_other td_border o_attr_pay" rowspan="{{$v['total']}}">
							<p><span  class="o_real_price">￥{{$v['order_real_total']}}</span><br>(含快递费用 ￥{{$v['order_exp_fee']}})</p>
						</td>
						<td class="o_attr o_attr_other o_attr_tags td_border" rowspan="{{$v['total']}}">
							@foreach($v['tag_name'] as $kk=>$vv)
								<p style="margin-bottom: 0px;">{{$vv}}</p>
							@endforeach
						</td>
						<td class="o_attr o_attr_operate td_border" rowspan="{{$v['total']}}">
							<p>
								<a target="_blank" href="{{ URL::asset('/order/detail/'.$v['order_id']) }}">订单详情</a><br>
								<a class="btn-dialog" data-url="{{URL::asset('/order/list/tag/'.$v['order_id'])}}" data-title ="设置标签">设置标签</a><br>
								@if($v['order_status'] == ORDER_STATUS_WAIT_CONFIRM || $v['order_status'] == ORDER_STATUS_WAIT_PAY || $v['order_status'] == ORDER_STATUS_WAIT_PRODUCE)
									{{--订单提交生产前可取消交易--}}
									<a class="btn-del" data-url="{{URL::asset('/order/cancel/'.$v['order_id'])}}" data-title="取消订单" data-text="是否确定取消订单">取消订单</a><br>
								@endif
							</p>
						</td>
					</tr>
				@else
					{{--中间tr--}}
					<tr>
						<td class="o_attr_goods">
							<div class="o_works_info">
								<div class="o_works_img">
									<img src="{{$info['prod_main_thumb']}}">
								</div>
								<div class="o_works_detail">
									<p class="o_works_name">{{$info['prod_name']}}</p>
									<p class="o_works_spec">{{$info['attr_str']}}</p>
								</div>
							</div>
						</td>
						<td class="o_attr o_attr_price">￥{{$info['prod_sku_price']}}</td>
						<td class="o_attr o_attr_num">{{$info['prod_num']}}</td>
						<td class="o_attr o_attr_sale"></td>
						<td class="o_attr o_attr_other"></td>
						<td class="o_attr o_attr_other"></td>
						<td class="o_attr o_attr_other"></td>
						<td class="o_attr o_attr_other"></td>
					</tr>
				@endif
			@endforeach
		@elseif($v['total'] == 1)
			{{--一订单一商品情况--}}
			<tr>
				<td class="o_attr_goods">
					<div class="o_works_info">
						<div class="o_works_img">
							<img src="{{$v['item'][0]['prod_main_thumb']}}">
						</div>
						<div class="o_works_detail">
							<p class="o_works_name">{{$v['item'][0]['prod_name']}}</p>
							<p class="o_works_spec">{{$v['item'][0]['attr_str']}}</p>
						</div>
					</div>
				</td>
				<td class="o_attr o_attr_price">￥{{$v['item'][0]['prod_sale_price']}}</td>
				<td class="o_attr o_attr_num">{{$v['item'][0]['prod_num']}}</td>
				<td class="o_attr o_attr_sale td_border" rowspan="{{$v['total']}}">{{$CommonPresenter->exchangeProduction($v['order_prod_status'])}}</td>
				<td class="o_attr o_attr_other o_attr_end td_border" rowspan="{{$v['total']}}">
					<span class="o_logistics">{{$CommonPresenter->exchangeOrderStatus($v['order_status'])}}<br>
						@if(!empty($v['delivery_code']))
							<span class="o-car"><img src="/images/c1-car.png" alt=""></span>
							<a class="btn-dialog" data-url="{{URL::asset('/order/list/logistics/'.$v['order_id'])}}" data-title = "物流信息">{{$v['delivery_code']}}</a>
						@endif
					</span>
				</td>
				<td class="o_attr o_attr_other o_attr_end td_border" rowspan="{{$v['total']}}">
					<p><span  class="o_real_price">￥{{$v['order_real_total']}}</span><br>(含快递费用 ￥{{$v['order_exp_fee']}})</p>
				</td>
				<td class="o_attr o_attr_other o_attr_tags o_attr_end td_border" rowspan="{{$v['total']}}">
					@foreach($v['tag_name'] as $kk=>$vv)
						<p style="margin-bottom: 0px;">{{$vv}}</p>
					@endforeach
				</td>
				<td class="o_attr o_attr_operate o_attr_end td_border" rowspan="{{$v['total']}}">
					<p>
						<a target="_blank" href="{{ URL::asset('/order/detail/'.$v['order_id']) }}">订单详情</a><br>
						<a class="btn-dialog" data-url="{{URL::asset('/order/list/tag/'.$v['order_id'])}}" data-title = "设置标签">设置标签</a><br>
						@if($v['order_status'] == ORDER_STATUS_WAIT_CONFIRM || $v['order_status'] == ORDER_STATUS_WAIT_PAY || $v['order_status'] == ORDER_STATUS_WAIT_PRODUCE)
							{{--订单提交生产前可取消交易--}}
							<a class="btn-del" data-url="{{URL::asset('/order/cancel/'.$v['order_id'])}}" data-title="取消订单" data-text="是否确定取消订单">取消订单</a><br>
						@endif
					</p>
				</td>
			</tr>
		@else
			{{--一订单多商品情况--}}
			@foreach ($v['item'] as $info)
				@if($loop->first)
					{{--第一个tr--}}
					<tr>
						<td class="o_attr_goods">
							<div class="o_works_info">
								<div class="o_works_img">
									<img src="{{$info['prod_main_thumb']}}">
								</div>
								<div class="o_works_detail">
									<p class="o_works_name">{{$info['prod_name']}}</p>
									<p class="o_works_spec">{{$info['attr_str']}}</p>
								</div>
							</div>
						</td>
						<td class="o_attr o_attr_price">￥{{$info['prod_sale_price']}}</td>
						<td class="o_attr o_attr_num">{{$info['prod_num']}}</td>
						<td class="o_attr o_attr_sale td_border" rowspan="3">{{$CommonPresenter->exchangeProduction($v['order_prod_status'])}}</td>
						<td class="o_attr  o_attr_logistics td_border" rowspan="3">
							<span class="o_logistics">{{$CommonPresenter->exchangeOrderStatus($v['order_status'])}}<br>
								@if(!empty($v['delivery_code']))
									<span class="o-car"><img src="/images/c1-car.png" alt=""></span>
									<a class="btn-dialog" data-url="{{URL::asset('/order/list/logistics/'.$v['order_id'])}}" data-title = "物流信息">{{$v['delivery_code']}}</a>
								@endif
							</span>
						</td>
						<td class="o_attr o_attr_other td_border o_attr_pay" rowspan="3">
							<p><span  class="o_real_price">￥{{$v['order_real_total']}}</span><br>(含快递费用 ￥{{$v['order_exp_fee']}})</p>
						</td>
						<td class="o_attr o_attr_other o_attr_tags td_border" rowspan="3">
							@foreach($v['tag_name'] as $kk=>$vv)
								<p style="margin-bottom: 0px;">{{$vv}}</p>
							@endforeach
						</td>
						<td class="o_attr o_attr_operate td_border" rowspan="3">
							<p>
								<a target="_blank" href="{{ URL::asset('/order/detail/'.$v['order_id']) }}">订单详情</a><br>
								<a class="btn-dialog" data-url="{{URL::asset('/order/list/tag/'.$v['order_id'])}}" data-title = "设置标签">设置标签</a><br>
								@if($v['order_status'] == ORDER_STATUS_WAIT_CONFIRM || $v['order_status'] == ORDER_STATUS_WAIT_PAY || $v['order_status'] == ORDER_STATUS_WAIT_PRODUCE)
									{{--订单提交生产前可取消交易--}}
									<a class="btn-del" data-url="{{URL::asset('/order/cancel/'.$v['order_id'])}}" data-title="取消订单" data-text="是否确定取消订单">取消订单</a><br>
								@endif
							</p>
						</td>
					</tr>
				@elseif($loop->last)
					{{--最后一个tr--}}
					<tr>
						<td class="o_attr_goods">
							<div class="o_works_info">
								<div class="o_works_img">
									<img src="{{$info['prod_main_thumb']}}">
								</div>
								<div class="o_works_detail">
									<p class="o_works_name">{{$info['prod_name']}}</p>
									<p class="o_works_spec">{{$info['attr_str']}}</p>
								</div>
							</div>
						</td>
						<td class="o_attr o_attr_price">￥{{$info['prod_sale_price']}}</td>
						<td class="o_attr o_attr_num">{{$info['prod_num']}}</td>
						<td class="o_attr o_attr_sale"></td>
						<td class="o_attr o_attr_other o_attr_end">
						</td>
						<td class="o_attr o_attr_other o_attr_end"></td>
						<td class="o_attr o_attr_other o_attr_end"></td>
						<td class="o_attr o_attr_other o_attr_end"></td>
					</tr>
				@elseif($loop->index > 1)
					{{--需隐藏的tr--}}
					<tr class="o_list_tr_hide">
						<td class="o_attr_goods">
							<div class="o_works_info">
								<div class="o_works_img">
									<img src="{{$info['prod_main_thumb']}}">
								</div>
								<div class="o_works_detail">
									<p class="o_works_name">{{$info['prod_name']}}</p>
									<p class="o_works_spec">{{$info['attr_str']}}</p>
								</div>
							</div>
						</td>
						<td class="o_attr o_attr_price">￥{{$info['prod_sale_price']}}</td>
						<td class="o_attr o_attr_num">{{$info['prod_num']}}</td>
						<td class="o_attr o_attr_sale"></td>
						<td class="o_attr o_attr_other">
						</td>
						<td class="o_attr o_attr_other"></td>
						<td class="o_attr o_attr_other"></td>
						<td class="o_attr o_attr_other"></td>
					</tr>
				@else
					{{--中间tr--}}
					<tr>
						<td class="o_attr_goods">
							<div class="o_works_info">
								<div class="o_works_img">
									<img src="{{$info['prod_main_thumb']}}">
								</div>
								<div class="o_works_detail">
									<p class="o_works_name">{{$info['prod_name']}}</p>
									<p class="o_works_spec">{{$info['attr_str']}}</p>
								</div>
							</div>
						</td>
						<td class="o_attr o_attr_price">￥{{$info['prod_sale_price']}}</td>
						<td class="o_attr o_attr_num">{{$info['prod_num']}}</td>
						<td class="o_attr o_attr_sale"></td>
						<td class="o_attr o_attr_other"></td>
						<td class="o_attr o_attr_other"></td>
						<td class="o_attr o_attr_other"></td>
						<td class="o_attr o_attr_other"></td>
					</tr>
				@endif
			@endforeach
		@endif
		{{--订单结束--}}

		{{--订单间隔--}}
		<tr class="o_spacing"></tr>
	@endforeach
@else
	<tr><td colspan=8>暂无记录</td></tr>
@endif

