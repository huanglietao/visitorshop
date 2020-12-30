<!-- table列表数据显示  -->
@if(!empty($list))
	@foreach  ($list as $k=>$v)
		@inject('CommonPresenter','App\Presenters\CommonPresenter');
		{{--订单开始--}}
		<tr class="o_list_tr">
			<td colspan="9" style="vertical-align: middle; text-align: left;padding-left: 10px;">
				<label for="checkedres{{$k}}"></label>
				<span class="o_next_span">订单号：<span class="data-text">{{$v['order_no']}}</span></span>
				<span class="o_next_span" >交易时间：<span class="data-text">{{$CommonPresenter->exchangeTime($v['created_at'])}}</span> </span>
				<span class="o_next_span" >总数量：
				<span class="data-text">{{$v['nums']}} 件</span>
					@if($v['total'] > 3)
						<img src="/images/down.png" data-action="show"  class="data-img" alt="" data-val="{{$v['total']}}">
					@endif
			    </span>
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
						<td class="o_attr o_attr_num">{{$info['sp_nums']}}</td>
                        <td class="o_attr o_attr_other td_border o_attr_pay" rowspan="{{$v['total']}}">
                            <p><span  class="o_real_price">￥{{$v['sp_order_amount']}}</span>
								{{--<br>(含快递费用 ￥{{$v['sp_freight_amount']}})--}}
							</p>
                        </td>
                        <td class="o_attr td_border o_attr_address" rowspan="{{$v['total']}}">
							<div style="width: 80%;margin: 0 auto;">
								<span style="display: block;text-align: left;">
									<i title="收货人" style="margin-right: 4%;color: #4b4b50;" class="fa fa-user-circle fa-lg" aria-hidden="true"></i>
									{{$v['sp_ord_rcv_user']}}
								</span>
								<span style="display: block;text-align: left;margin-top: 5px;">
									<i title="联系电话" style="margin-right: 4%;color: #4b4b50;" class="fa fa-phone-square fa-lg" aria-hidden="true"></i>
									{{$v['sp_ord_rcv_phone']}}
								</span>
								<span style="display: block;text-align: left;margin-top: 5px;">
									<i title="省市区" style="margin-right: 4%;color: #4b4b50;" class="fa fa-th  fa-lg" aria-hidden="true"></i>
									<span>{{$v['province_name']}}</span><span style="margin-left: 5px;margin-right: 5px;">{{$v['city_name']}}</span><span>{{$v['area_name']}}</span>
								</span>
								<span style="display: block;text-align: left;margin-top: 5px;">
									<i title="收货地址" style="margin-right: 4%;color: #4b4b50;" class="fa fa-list fa-lg" aria-hidden="true"></i>
									{{$v['sp_ord_rcv_address']}}
								</span>
							</div>
                        </td>
						<td class="o_attr o_attr_sale td_border" rowspan="{{$v['total']}}">{{$CommonPresenter->exchangeProduction($v['sp_produce_status'])}}</td>
						<td class="o_attr  o_attr_logistics td_border" rowspan="{{$v['total']}}">
							<span class="o_logistics">{{$CommonPresenter->exchangeOrderStatus($v['sp_order_status'])}}<br>
								@if(!empty($v['sp_delivery_code']))
									<span class="o-car"><img src="/images/c1-car.png" alt=""></span>
									@component('component/tips_modal')
										@slot('slot_ele')
											<a class="o-logistics-text">{{$v['sp_delivery_code']}}</a>
										@endslot
										<p>中通快递：{{$v['sp_delivery_code']}}</p>
										<ul>
										{{--<li>[广州市]番禺南沙的自贸区派件师傅D[17665154055]正在派件（95720为中通快递员外呼专属号码，请放心接听）2019-09-02 09:33:33</li>--}}
										{{--<li>[广州市]快件已到达番禺南沙2019-09-02 06:54:29</li>--}}
										{{--<li>以上为最新跟踪信息查看全部</li>--}}
										<li>暂无物流信息</li>
									</ul>
									@endcomponent
								@endif
							</span>
						</td>
						<td class="o_attr o_attr_other o_attr_tags td_border">
							@if(isset($info['is_download']))
								<p style="margin-bottom: 0px;">{{$info['is_download']}}</p>
								@foreach($info['download'] as $download)
									@if($download['filetype'] == GOODS_SIZE_TYPE_COVER)
										<p style="margin-bottom: 5px;">
											<a href="/order/list/download?url={{$download['url']}}&id={{$download['sp_down_queue_id']}}&oid={{$download['ord_id']}}&item={{$download['ord_prod_id']}}&sid={{$download['sp_id']}}" target="_blank">
												<button class="btn  btn-primary btn-3F51B5">封面下载</button>
											</a>
										</p>
									@elseif($download['filetype'] == GOODS_SIZE_TYPE_INNER)
										<p>
											<a href="/order/list/download?url={{$download['url']}}&id={{$download['sp_down_queue_id']}}&oid={{$download['ord_id']}}&item={{$download['ord_prod_id']}}&sid={{$download['sp_id']}}" target="_blank">
												<button class="btn  btn-primary btn-3F51B5">内页下载</button>
											</a>
										</p>
									@endif
								@endforeach
							@endif
                        </td>
						<td class="o_attr o_attr_operate td_border" rowspan="{{$v['total']}}">
							@if($v['sp_order_status'] == ORDER_STATUS_WAIT_DELIVERY || $v['sp_order_status'] == ORDER_STATUS_WAIT_PRODUCE)
								<p style="margin:4px 0 ;padding:0">
									<span class="oa_operate op_tbl btn-dialog" data-area="['70%', '70%']" data-url="{{URL::asset('/order/list/delivery/'.$v['sp_ord_id'])}}" data-title="发货">发货</span>
								</p>
							@endif
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
						<td class="o_attr o_attr_num">{{$info['sp_nums']}}</td>
						<td class="o_attr o_attr_other">
							@if(isset($info['is_download']))
								<p style="margin-bottom: 0px;">{{$info['is_download']}}</p>
								@foreach($info['download'] as $download)
									@if($download['filetype'] == GOODS_SIZE_TYPE_COVER)
										<p style="margin-bottom: 5px;">
											<a href="/order/list/download?url={{$download['url']}}&id={{$download['sp_down_queue_id']}}&oid={{$download['ord_id']}}&item={{$download['ord_prod_id']}}&sid={{$download['sp_id']}}" target="_blank">
												<button class="btn  btn-primary btn-3F51B5">封面下载</button>
											</a>
										</p>
									@elseif($download['filetype'] == GOODS_SIZE_TYPE_INNER)
										<p>
											<a href="/order/list/download?url={{$download['url']}}&id={{$download['sp_down_queue_id']}}&oid={{$download['ord_id']}}&item={{$download['ord_prod_id']}}&sid={{$download['sp_id']}}" target="_blank">
												<button class="btn  btn-primary btn-3F51B5">内页下载</button>
											</a>
										</p>
									@endif
								@endforeach
							@endif
						</td>
						<td class="o_attr o_attr_address"></td>
                        <td class="o_attr o_attr_sale"></td>
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
				<td class="o_attr o_attr_price">￥{{$v['item'][0]['prod_price']}}</td>
				<td class="o_attr o_attr_num">{{$v['item'][0]['sp_nums']}}</td>
                <td class="o_attr o_attr_other o_attr_end td_border" rowspan="{{$v['total']}}">
                    <p><span  class="o_real_price">￥{{$v['sp_order_amount']}}</span>
						{{--<br>(含快递费用 ￥{{$v['sp_freight_amount']}})--}}
					</p>
                </td>
				<td class="o_attr o_attr_address o_attr_end td_border" rowspan="{{$v['total']}}">
					<div style="width: 80%;margin: 0 auto;">
						<span style="display: block;text-align: left;">
							<i title="收货人" style="margin-right: 4%;color: #4b4b50;" class="fa fa-user-circle fa-lg" aria-hidden="true"></i>
							{{$v['sp_ord_rcv_user']}}
						</span>
						<span style="display: block;text-align: left;margin-top: 5px;">
							<i title="联系电话" style="margin-right: 4%;color: #4b4b50;" class="fa fa-phone-square fa-lg" aria-hidden="true"></i>
							{{$v['sp_ord_rcv_phone']}}
						</span>
						<span style="display: block;text-align: left;margin-top: 5px;">
							<i title="省市区" style="margin-right: 4%;color: #4b4b50;" class="fa fa-th  fa-lg" aria-hidden="true"></i>
							<span>{{$v['province_name']}}</span><span style="margin-left: 5px;margin-right: 5px;">{{$v['city_name']}}</span><span>{{$v['area_name']}}</span>
						</span>
						<span style="display: block;text-align: left;margin-top: 5px;">
							<i title="收货地址" style="margin-right: 4%;color: #4b4b50;" class="fa fa-list fa-lg" aria-hidden="true"></i>
							{{$v['sp_ord_rcv_address']}}
						</span>
					</div>
				</td>
				<td class="o_attr o_attr_sale td_border" rowspan="{{$v['total']}}">{{$CommonPresenter->exchangeProduction($v['sp_produce_status'])}}</td>
				<td class="o_attr o_attr_other o_attr_end td_border" rowspan="{{$v['total']}}">
					<span class="o_logistics">{{$CommonPresenter->exchangeOrderStatus($v['sp_order_status'])}}<br>
						@if(!empty($v['sp_delivery_code']))
							<span class="o-car"><img src="/images/c1-car.png" alt=""></span>
							@component('component/tips_modal')
								@slot('slot_ele')
									<a class="o-logistics-text">{{$v['sp_delivery_code']}}</a>
								@endslot
								<p>中通快递：{{$v['sp_delivery_code']}}</p>
								<ul>
										{{--<li>[广州市]番禺南沙的自贸区派件师傅D[17665154055]正在派件（95720为中通快递员外呼专属号码，请放心接听）2019-09-02 09:33:33</li>--}}
										{{--<li>[广州市]快件已到达番禺南沙2019-09-02 06:54:29</li>--}}
										{{--<li>以上为最新跟踪信息查看全部</li>--}}
									<li>暂无物流信息</li>
								</ul>
							@endcomponent
						@endif
					</span>
				</td>
				<td class="o_attr o_attr_other o_attr_tags o_attr_end td_border">
{{--					@if($v['item'][0]['prj_type'] != WORKS_FILE_TYPE_EMPTY)--}}
						<p style="margin-bottom: 0px;">{{$v['item'][0]['is_download']}}</p>
						@foreach($v['item'][0]['download'] as $download)
							@if($download['filetype'] == GOODS_SIZE_TYPE_COVER)
								<p style="margin-bottom: 5px;">
									<a href="/order/list/download?url={{$download['url']}}&id={{$download['sp_down_queue_id']}}&oid={{$download['ord_id']}}&item={{$download['ord_prod_id']}}&sid={{$download['sp_id']}}" target="_blank">
										<button class="btn  btn-primary btn-3F51B5">封面下载</button>
									</a>
								</p>
							@elseif($download['filetype'] == GOODS_SIZE_TYPE_INNER)
								<p>
									<a href="/order/list/download?url={{$download['url']}}&id={{$download['sp_down_queue_id']}}&oid={{$download['ord_id']}}&item={{$download['ord_prod_id']}}&sid={{$download['sp_id']}}" target="_blank">
										<button class="btn  btn-primary btn-3F51B5">内页下载</button>
									</a>
								</p>
							@endif
						@endforeach
					{{--@endif--}}
				</td>
				<td class="o_attr o_attr_operate o_attr_end td_border" rowspan="{{$v['total']}}">
					@if($v['sp_order_status'] == ORDER_STATUS_WAIT_DELIVERY || $v['sp_order_status'] == ORDER_STATUS_WAIT_PRODUCE)
						<p style="margin:4px 0 ;padding:0">
							<span class="oa_operate op_tbl btn-dialog" data-area="['70%', '70%']" data-url="{{URL::asset('/order/list/delivery/'.$v['sp_ord_id'])}}" data-title="发货">发货</span>
						</p>
					@endif
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
						<td class="o_attr o_attr_price">￥{{$info['prod_price']}}</td>
						<td class="o_attr o_attr_num">{{$info['sp_nums']}}</td>
                        <td class="o_attr o_attr_other td_border o_attr_pay" rowspan="{{$v['total']}}">
                            <p><span  class="o_real_price">￥{{$v['sp_order_amount']}}</span>
								{{--<br>(含快递费用 ￥{{$v['sp_freight_amount']}})--}}
							</p>
                        </td>
						<td class="o_attr o_attr_address o_attr_end td_border" rowspan="{{$v['total']}}">
							<div style="width: 80%;margin: 0 auto;">
								<span style="display: block;text-align: left;">
									<i title="收货人" style="margin-right: 4%;color: #4b4b50;" class="fa fa-user-circle fa-lg" aria-hidden="true"></i>
									{{$v['sp_ord_rcv_user']}}
								</span>
								<span style="display: block;text-align: left;margin-top: 5px;">
									<i title="联系电话" style="margin-right: 4%;color: #4b4b50;" class="fa fa-phone-square fa-lg" aria-hidden="true"></i>
									{{$v['sp_ord_rcv_phone']}}
								</span>
								<span style="display: block;text-align: left;margin-top: 5px;">
									<i title="省市区" style="margin-right: 4%;color: #4b4b50;" class="fa fa-th  fa-lg" aria-hidden="true"></i>
									<span>{{$v['province_name']}}</span><span style="margin-left: 5px;margin-right: 5px;">{{$v['city_name']}}</span><span>{{$v['area_name']}}</span>
								</span>
								<span style="display: block;text-align: left;margin-top: 5px;">
									<i title="收货地址" style="margin-right: 4%;color: #4b4b50;" class="fa fa-list fa-lg" aria-hidden="true"></i>
									{{$v['sp_ord_rcv_address']}}
								</span>
							</div>
						</td>
						<td class="o_attr o_attr_sale td_border" rowspan="{{$v['total']}}">{{$CommonPresenter->exchangeProduction($v['sp_produce_status'])}}</td>
						<td class="o_attr  o_attr_logistics td_border" rowspan="{{$v['total']}}">
							<span class="o_logistics">{{$CommonPresenter->exchangeOrderStatus($v['sp_order_status'])}}<br>
								@if(!empty($v['sp_delivery_code']))
									<span class="o-car"><img src="/images/c1-car.png" alt=""></span>
									@component('component/tips_modal')
										@slot('slot_ele')
											<a class="o-logistics-text">{{$v['sp_delivery_code']}}</a>
										@endslot
										<p>中通快递：{{$v['sp_delivery_code']}}</p>
										<ul>
											{{--<li>[广州市]番禺南沙的自贸区派件师傅D[17665154055]正在派件（95720为中通快递员外呼专属号码，请放心接听）2019-09-02 09:33:33</li>--}}
											{{--<li>[广州市]快件已到达番禺南沙2019-09-02 06:54:29</li>--}}
											{{--<li>以上为最新跟踪信息查看全部</li>--}}
											<li>暂无物流信息</li>
										</ul>
									@endcomponent
								@endif
							</span>
						</td>
						<td class="o_attr o_attr_other o_attr_tags td_border">
							@if(isset($info['is_download']))
								<p style="margin-bottom: 0px;">{{$info['is_download']}}</p>
								@foreach($info['download'] as $download)
									@if($download['filetype'] == GOODS_SIZE_TYPE_COVER)
										<p style="margin-bottom: 5px;">
											<a href="/order/list/download?url={{$download['url']}}&id={{$download['sp_down_queue_id']}}&oid={{$download['ord_id']}}&item={{$download['ord_prod_id']}}&sid={{$download['sp_id']}}" target="_blank">
												<button class="btn  btn-primary btn-3F51B5">封面下载</button>
											</a>
										</p>
									@elseif($download['filetype'] == GOODS_SIZE_TYPE_INNER)
										<p>
											<a href="/order/list/download?url={{$download['url']}}&id={{$download['sp_down_queue_id']}}&oid={{$download['ord_id']}}&item={{$download['ord_prod_id']}}&sid={{$download['sp_id']}}" target="_blank">
												<button class="btn  btn-primary btn-3F51B5">内页下载</button>
											</a>
										</p>
									@endif
								@endforeach
							@endif
                        </td>
						<td class="o_attr o_attr_operate td_border" rowspan="{{$v['total']}}">
							@if($v['sp_order_status'] == ORDER_STATUS_WAIT_DELIVERY || $v['sp_order_status'] == ORDER_STATUS_WAIT_PRODUCE)
								<p style="margin:4px 0 ;padding:0">
									<span class="oa_operate op_tbl btn-dialog" data-area="['70%', '70%']" data-url="{{URL::asset('/order/list/delivery/'.$v['sp_ord_id'])}}" data-title="发货">发货</span>
								</p>
							@endif
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
						<td class="o_attr o_attr_price">￥{{$info['prod_price']}}</td>
						<td class="o_attr o_attr_num">{{$info['sp_nums']}}</td>
                        <td class="o_attr o_attr_other o_attr_end">
							@if(isset($info['is_download']))
								<p style="margin-bottom: 0px;">{{$info['is_download']}}</p>
								@foreach($info['download'] as $download)
									@if($download['filetype'] == GOODS_SIZE_TYPE_COVER)
										<p style="margin-bottom: 5px;">
											<a href="/order/list/download?url={{$download['url']}}&id={{$download['sp_down_queue_id']}}&oid={{$download['ord_id']}}&item={{$download['ord_prod_id']}}&sid={{$download['sp_id']}}" target="_blank">
												<button class="btn  btn-primary btn-3F51B5">封面下载</button>
											</a>
										</p>
									@elseif($download['filetype'] == GOODS_SIZE_TYPE_INNER)
										<p>
											<a href="/order/list/download?url={{$download['url']}}&id={{$download['sp_down_queue_id']}}&oid={{$download['ord_id']}}&item={{$download['ord_prod_id']}}&sid={{$download['sp_id']}}" target="_blank">
												<button class="btn  btn-primary btn-3F51B5">内页下载</button>
											</a>
										</p>
									@endif
								@endforeach
							@endif
						</td>
						<td class="o_attr o_attr_other o_attr_end"></td>
						<td class="o_attr o_attr_sale"></td>
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
						<td class="o_attr o_attr_price">￥{{$info['prod_price']}}</td>
						<td class="o_attr o_attr_num">{{$info['sp_nums']}}</td>
						<td class="o_attr o_attr_other o_attr_end">
							@if(isset($info['is_download']))
								<p style="margin-bottom: 0px;">{{$info['is_download']}}</p>
								@foreach($info['download'] as $download)
									@if($download['filetype'] == GOODS_SIZE_TYPE_COVER)
										<p style="margin-bottom: 5px;">
											<a href="/order/list/download?url={{$download['url']}}&id={{$download['sp_down_queue_id']}}&oid={{$download['ord_id']}}&item={{$download['ord_prod_id']}}&sid={{$download['sp_id']}}" target="_blank">
												<button class="btn  btn-primary btn-3F51B5">封面下载</button>
											</a>
										</p>
									@elseif($download['filetype'] == GOODS_SIZE_TYPE_INNER)
										<p>
											<a href="/order/list/download?url={{$download['url']}}&id={{$download['sp_down_queue_id']}}&oid={{$download['ord_id']}}&item={{$download['ord_prod_id']}}&sid={{$download['sp_id']}}" target="_blank">
												<button class="btn  btn-primary btn-3F51B5">内页下载</button>
											</a>
										</p>
									@endif
								@endforeach
							@endif
						</td>
						<td class="o_attr o_attr_other"></td>
                        <td class="o_attr o_attr_sale"></td>
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
						<td class="o_attr o_attr_price">￥{{$info['prod_price']}}</td>
						<td class="o_attr o_attr_num">{{$info['sp_nums']}}</td>
						<td class="o_attr o_attr_other o_attr_end">
							@if(isset($info['is_download']))
								<p style="margin-bottom: 0px;">{{$info['is_download']}}</p>
								@foreach($info['download'] as $download)
									@if($download['filetype'] == GOODS_SIZE_TYPE_COVER)
										<p style="margin-bottom: 5px;">
											<a href="/order/list/download?url={{$download['url']}}&id={{$download['sp_down_queue_id']}}&oid={{$download['ord_id']}}&item={{$download['ord_prod_id']}}&sid={{$download['sp_id']}}" target="_blank">
												<button class="btn  btn-primary btn-3F51B5">封面下载</button>
											</a>
										</p>
									@elseif($download['filetype'] == GOODS_SIZE_TYPE_INNER)
										<p>
											<a href="/order/list/download?url={{$download['url']}}&id={{$download['sp_down_queue_id']}}&oid={{$download['ord_id']}}&item={{$download['ord_prod_id']}}&sid={{$download['sp_id']}}" target="_blank">
												<button class="btn  btn-primary btn-3F51B5">内页下载</button>
											</a>
										</p>
									@endif
								@endforeach
							@endif
						</td>
						<td class="o_attr o_attr_other"></td>
                        <td class="o_attr o_attr_sale"></td>
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

