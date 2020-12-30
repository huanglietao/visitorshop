<!-- table列表数据显示  -->

{{--购物车的订单开始--}}
<tr class="o_list_tr">
	<td colspan="9" style="vertical-align: middle; text-align: left">
			<input type="checkbox" id="checkall1" class="checkall local-checkall checkall2 checkbox"  data-id="2"  data-value="local-all" data-num="{{$list['count']}}">
			<label for="checkall1" class="checkbox-label"></label>

				{{--<div class="s_z_coupon-f">
					<div class="s_z_coupon">
						<img class="s_caret_right" src="/images/caret-right.png">
						<span class="s_coupon_text">优惠券</span>
						<i class="fa fa-chevron-down s_chevron s_chevron-down"></i>
					</div>

					<div class="s_coupon_list" style="display: none">
						<div class="s_coupon_main">
								<div class="sc_main_head">
									<span class="sc_text">已领取 1 张优惠劵</span>
									<img class="sc_img" src="/images/times-circle.png" alt="">
								</div>
								<div class="s_coupon">
									<div class="sc_left">
											￥&nbsp5
										<img class="sc_cut" src="/images/cut.png" alt="">
									</div>
									<div class="sc_mid">
											<p class="sc_mid_text st_title">8月份5元优惠劵 满48减3</p>
											<p class="sc_mid_text st_time">2019.08.01-2019.08.31</p>

									</div>

									<div class="s_d_receive">
										领取成功
									</div>
								</div>
								<div class="s_coupon">
								<div class="sc_left">
									￥&nbsp5
									<img class="sc_cut" src="/images/cut.png" alt="">
								</div>
								<div class="sc_mid">
									<p class="sc_mid_text st_title">8月份5元优惠劵 满48减3</p>
									<p class="sc_mid_text st_time">2019.08.01-2019.08.31</p>

								</div>

								<div class="s_receive">
									领取
								</div>
							</div>
								<div class="s_coupon">
								<div class="sc_left">
									￥&nbsp5
									<img class="sc_cut" src="/images/cut.png" alt="">
								</div>
								<div class="sc_mid">
									<p class="sc_mid_text st_title">8月份5元优惠劵 满48减3</p>
									<p class="sc_mid_text st_time">2019.08.01-2019.08.31</p>

								</div>

								<div class="s_receive">
									领取
								</div>
							</div>
								<div class="s_coupon">
								<div class="sc_left">
									￥&nbsp5
									<img class="sc_cut" src="/images/cut.png" alt="">
								</div>
								<div class="sc_mid">
									<p class="sc_mid_text st_title">8月份5元优惠劵 满48减3</p>
									<p class="sc_mid_text st_time">2019.08.01-2019.08.31</p>

								</div>

								<div class="s_receive">
									领取
								</div>
							</div>
						</div>

					</div>
				</div>--}}

	</td>

</tr>
@php $i =1; @endphp
@forelse ($list['data'] as $k =>$v)
	<tr class="cart_tr">
		<td class="o_attr_goods">

			<div class="o_works_info">
				<div class="s_check">
					<input type="checkbox" name="checkedres" id="checkedres{{$i}}" class="checkedres g_checkedres checkedres2  checkbox" data-pid="2" data-id="{{$i}}" data-cart-id = "{{$v['cart_id']}}" data-sku-id = "{{$v['sku_id']}}" data-project-id ="{{$v['project_id']}}">
					<label for="checkedres{{$i}}" style="margin-left: 5px" class="checkbox-label"></label>
				</div>

				<div class="o_works_img sc_works_img">
					<img src="{{$v['prod_photo']}}">
				</div>
				<div class="s_works_detail ">
					<p class="s_works_name">{{$v['prod_name']}}</p>
					<p class="o_works_spec">商品货号：{{$v['prod_sn']}}</p>
				</div>

			</div>

		</td>
		<td class="o_attr s_works_spec d_d_o_works_spec_info ">
			@foreach($v['sku_attr'] as $kk =>$vv)
				<p class="s_o_works_spec">{{$vv}}</p>
			@endforeach
		</td>
		<td class="o_attr s_works_price">
			{{--<a class="s_first_price">￥238.00</a>--}}
			<input type="hidden" value="{{$v['sku_price']}}">
			<p>￥{{$v['sku_price']}} </p>
		</td>

		<td class="o_attr s_works_num">
			<input type="hidden" class="project_id" value="{{$v['project_id']}}">
			<input type="hidden" class="cart_id" value="{{$v['cart_id']}}">
			<input type="hidden" class="sku_id" value="{{$v['sku_id']}}">
			<div class="s_works_num_main">
				<div class="s_reduce" data-value="reduce">
					<i class="fa fa-minus s_compute"></i>
				</div>
				<div class="s_num_d">

					<input class="s_num_input" type="text" name="" id="" value="{{$v['num']}}">
				</div>
				<div class="s_addition" data-value="addition">
					<i class="fa fa-plus s_compute"></i>
				</div>
			</div>


		</td>

		<td class="o_attr s_works_amount">
			<input type="hidden" value="{{$v['total_price']}}">
			<p>
				￥{{number_format($v['total_price'],2)}}
			</p>

		</td>
		<td class="o_attr o_attr_other o_attr_operate">
			<p>
				<a class="btn-to-collect" data-cart-id = "{{$v['cart_id']}}" data-project-id="{{$v['project_id']}}" data-sku-id="{{$v['sku_id']}}">移入我的收藏</a><br>
				<a class="btn-del" data-url="/orders/del_cart_goods?cid={{$v['cart_id']}}&pid={{$v['project_id']}}&sid={{$v['sku_id']}}" data-cart-id = "{{$v['cart_id']}}" data-project-id="{{$v['project_id']}}">删除</a><br>
			</p>
		</td>
	</tr>
	<tr class="o_spacing"></tr>
	{{++$i}}
	@empty
		<tr>
			<td colspan=6>暂无记录</td>
		</tr>
	@endforelse
{{--第一个tr--}}




{{--订单间隔--}}


{{--第二个tr--}}
{{--<tr class="cart_tr">

	<td class="o_attr_goods">

		<div class="o_works_info">
			<div class="s_check">
				<input type="checkbox" name="checkedres" id="checkedres2" class="checkedres g_checkedres checkedres2 checkbox" data-pid="2" data-id = "2">
				<label for="checkedres2" style="margin-left: 5px" class="checkbox-label"></label>
			</div>

			<div class="o_works_img sc_works_img">
				<img src="/images/1.jpg">
			</div>
			<div class="s_works_detail ">
				<p class="s_works_name">经典对裱纪念册1ss2寸竖（16/22/30/40P）</p>
				<p class="o_works_spec">商品货号：ECS1688-6</p>
			</div>

		</div>

	</td>
	<td class="o_attr s_works_spec d_d_o_works_spec_info ">
		<p class="s_o_works_spec">尺寸：10寸竖（20*25CM）</p>
		<p class="s_o_works_spec">画框：原木级大三级，页数：1P</p>
	</td>
	<td class="o_attr s_works_price">
		<a class="s_first_price">￥238.00</a>
		<p>￥78.00 </p>
	</td>

	<td class="o_attr s_works_num">
		<div class="s_works_num_main">
			<div class="s_reduce" data-value="reduce">
				<i class="fa fa-minus s_compute"></i>
			</div>
			<div class="s_num_d">
				<input class="s_num_input" type="text" name="" id="" value="2">
			</div>
			<div class="s_addition" data-value="addition">
				<i class="fa fa-plus s_compute"></i>
			</div>
		</div>


	</td>

	<td class="o_attr s_works_amount">

		<p>
			￥78.00
		</p>

	</td>
	<td class="o_attr o_attr_other o_attr_operate">
		<p>
			<a class="btn-tips">移入我的收藏</a><br>
			<a class="btn-del">删除</a><br>
		</p>
	</td>
</tr>

订单间隔
<tr class="o_spacing"></tr>

第三个tr
<tr class="cart_tr">

	<td class="o_attr_goods">

		<div class="o_works_info">
			<div class="s_check">
				<input type="checkbox" name="checkedres" id="checkedres3" class="checkedres g_checkedres checkedres2 checkbox" data-pid="2" data-id = "3">
				<label for="checkedres3" style="margin-left: 5px" class="checkbox-label"></label>
			</div>

			<div class="o_works_img sc_works_img">
				<img src="/images/1.jpg">
			</div>
			<div class="s_works_detail ">
				<p class="s_works_name">经典对裱纪念册1ss2寸竖（16/22/30/40P）</p>
				<p class="o_works_spec">商品货号：ECS1688-6</p>
			</div>

		</div>

	</td>
	<td class="o_attr s_works_spec d_d_o_works_spec_info ">
		<p class="s_o_works_spec">尺寸：10寸竖（20*25CM）</p>
		<p class="s_o_works_spec">画框：原木级大三级，页数：1P</p>
	</td>
	<td class="o_attr s_works_price">
		<a class="s_first_price">￥238.00</a>
		<p>￥78.00 </p>
	</td>

	<td class="o_attr s_works_num">
		<div class="s_works_num_main">
			<div class="s_reduce" data-value="reduce">
				<i class="fa fa-minus s_compute"></i>
			</div>
			<div class="s_num_d">
				<input class="s_num_input" type="text" name="" id="" value="3">
			</div>
			<div class="s_addition" data-value="addition">
				<i class="fa fa-plus s_compute"></i>
			</div>
		</div>


	</td>

	<td class="o_attr s_works_amount">

		<p>
			￥78.00
		</p>

	</td>
	<td class="o_attr o_attr_other o_attr_operate">
		<p>
			<a class="btn-tips">移入我的收藏</a><br>
			<a class="btn-del">删除</a><br>
		</p>
	</td>
</tr>--}}






{{--
--}}{{--购物车的第二个订单开始--}}{{--
<tr class="o_list_tr">
	<td colspan="9" style="vertical-align: middle; text-align: left">
		<input type="checkbox" id="checkall5" class="checkall local-checkall checkall5 checkbox"  data-id="5" data-value="local-all" data-num="3">
		<label for="checkall5" class="checkbox-label"></label>

		<div class="s_z_coupon-f">
			<div class="s_z_coupon">
				<img class="s_caret_right" src="/images/caret-right.png">
				<span class="s_coupon_text">优惠券</span>
				<i class="fa fa-chevron-down s_chevron s_chevron-down"></i>
			</div>

			<div class="s_coupon_list" style="display: none">
				<div class="s_coupon_main">
					<div class="sc_main_head">
						<span class="sc_text">已领取 1 张优惠劵</span>
						<img class="sc_img" src="/images/times-circle.png" alt="">
					</div>
					<div class="s_coupon">
						<div class="sc_left">
							￥&nbsp5
							<img class="sc_cut" src="/images/cut.png" alt="">
						</div>
						<div class="sc_mid">
							<p class="sc_mid_text st_title">8月份5元优惠劵 满48减3</p>
							<p class="sc_mid_text st_time">2019.08.01-2019.08.31</p>

						</div>

						<div class="s_d_receive">
							领取成功
						</div>
					</div>
					<div class="s_coupon">
						<div class="sc_left">
							￥&nbsp5
							<img class="sc_cut" src="/images/cut.png" alt="">
						</div>
						<div class="sc_mid">
							<p class="sc_mid_text st_title">8月份5元优惠劵 满48减3</p>
							<p class="sc_mid_text st_time">2019.08.01-2019.08.31</p>

						</div>

						<div class="s_receive">
							领取
						</div>
					</div>
					<div class="s_coupon">
						<div class="sc_left">
							￥&nbsp5
							<img class="sc_cut" src="/images/cut.png" alt="">
						</div>
						<div class="sc_mid">
							<p class="sc_mid_text st_title">8月份5元优惠劵 满48减3</p>
							<p class="sc_mid_text st_time">2019.08.01-2019.08.31</p>

						</div>

						<div class="s_receive">
							领取
						</div>
					</div>
					<div class="s_coupon">
						<div class="sc_left">
							￥&nbsp5
							<img class="sc_cut" src="/images/cut.png" alt="">
						</div>
						<div class="sc_mid">
							<p class="sc_mid_text st_title">8月份5元优惠劵 满48减3</p>
							<p class="sc_mid_text st_time">2019.08.01-2019.08.31</p>

						</div>

						<div class="s_receive">
							领取
						</div>
					</div>
				</div>

			</div>
		</div>

	</td>

</tr>
--}}{{--第一个tr--}}{{--
<tr class="cart_tr">

	<td class="o_attr_goods">

		<div class="o_works_info">
			<div class="s_check">
				<input type="checkbox" name="checkedres" id="checkedres4" class="checkedres g_checkedres checkedres5 g_checkedres checkbox" data-pid="5" data-id = "6">
				<label for="checkedres4" style="margin-left: 5px" class="checkbox-label"></label>
			</div>

			<div class="o_works_img sc_works_img">
				<img src="/images/1.jpg">
			</div>
			<div class="s_works_detail ">
				<p class="s_works_name">经典对裱纪念册1ss2寸竖（16/22/30/40P）</p>
				<p class="o_works_spec">商品货号：ECS1688-6</p>
			</div>

		</div>

	</td>
	<td class="o_attr s_works_spec d_d_o_works_spec_info ">
		<p class="s_o_works_spec">尺寸：10寸竖（20*25CM）</p>
		<p class="s_o_works_spec">画框：原木级大三级，页数：1P</p>
	</td>
	<td class="o_attr s_works_price">
		<a class="s_first_price">￥238.00</a>
		<p>￥78.00 </p>
	</td>

	<td class="o_attr s_works_num">
		<div class="s_works_num_main">
			<div class="s_reduce" data-value="reduce">
				<i class="fa fa-minus s_compute"></i>
			</div>
			<div class="s_num_d">
				<input class="s_num_input" type="text" name="" id="" value="1">
			</div>
			<div class="s_addition" data-value="addition">
				<i class="fa fa-plus s_compute"></i>
			</div>
		</div>


	</td>

	<td class="o_attr s_works_amount">

		<p>
			￥78.00
		</p>

	</td>
	<td class="o_attr o_attr_other o_attr_operate">
		<p>
			<a class="btn-tips">移入我的收藏</a><br>
			<a class="btn-del">删除</a><br>
		</p>
	</td>
</tr>



--}}{{--订单间隔--}}{{--
<tr class="o_spacing"></tr>

--}}{{--第二个tr--}}{{--
<tr class="cart_tr">

	<td class="o_attr_goods">

		<div class="o_works_info">
			<div class="s_check">
				<input type="checkbox" name="checkedres" id="checkedres6" class="checkedres g_checkedres checkedres5  checkbox" data-pid="5" data-id="4">
				<label for="checkedres6" style="margin-left: 5px" class="checkbox-label"></label>
			</div>

			<div class="o_works_img sc_works_img">
				<img src="/images/1.jpg">
			</div>
			<div class="s_works_detail ">
				<p class="s_works_name">经典对裱纪念册1ss2寸竖（16/22/30/40P）</p>
				<p class="o_works_spec">商品货号：ECS1688-6</p>
			</div>

		</div>

	</td>
	<td class="o_attr s_works_spec d_d_o_works_spec_info ">
		<p class="s_o_works_spec">尺寸：10寸竖（20*25CM）</p>
		<p class="s_o_works_spec">画框：原木级大三级，页数：1P</p>
	</td>
	<td class="o_attr s_works_price">
		<a class="s_first_price">￥238.00</a>
		<p>￥78.00 </p>
	</td>

	<td class="o_attr s_works_num">
		<div class="s_works_num_main">
			<div class="s_reduce" data-value="reduce">
				<i class="fa fa-minus s_compute"></i>
			</div>
			<div class="s_num_d">
				<input class="s_num_input" type="text" name="" id="" value="2">
			</div>
			<div class="s_addition" data-value="addition">
				<i class="fa fa-plus s_compute"></i>
			</div>
		</div>


	</td>

	<td class="o_attr s_works_amount">

		<p>
			￥78.00
		</p>

	</td>
	<td class="o_attr o_attr_other o_attr_operate">
		<p>
			<a class="btn-tips">移入我的收藏</a><br>
			<a class="btn-del">删除</a><br>
		</p>
	</td>
</tr>

--}}{{--订单间隔--}}{{--
<tr class="o_spacing"></tr>

--}}{{--第三个tr--}}{{--
<tr class="cart_tr">

	<td class="o_attr_goods">

		<div class="o_works_info">
			<div class="s_check">
				<input type="checkbox" name="checkedres" id="checkedres7" class="checkedres g_checkedres checkedres5  checkbox" data-pid="5" data-id = "5">
				<label for="checkedres7" style="margin-left: 5px" class="checkbox-label"></label>
			</div>

			<div class="o_works_img sc_works_img">
				<img src="/images/1.jpg">
			</div>
			<div class="s_works_detail ">
				<p class="s_works_name">经典对裱纪念册1ss2寸竖（16/22/30/40P）</p>
				<p class="o_works_spec">商品货号：ECS1688-6</p>
			</div>

		</div>

	</td>
	<td class="o_attr s_works_spec d_d_o_works_spec_info ">
		<p class="s_o_works_spec">尺寸：10寸竖（20*25CM）</p>
		<p class="s_o_works_spec">画框：原木级大三级，页数：1P</p>
	</td>
	<td class="o_attr s_works_price">
		<a class="s_first_price">￥238.00</a>
		<p>￥78.00 </p>
	</td>

	<td class="o_attr s_works_num">
		<div class="s_works_num_main">
			<div class="s_reduce" data-value="reduce">
				<i class="fa fa-minus s_compute"></i>
			</div>
			<div class="s_num_d">
				<input class="s_num_input" type="text" name="" id="" value="3">
			</div>
			<div class="s_addition" data-value="addition">
				<i class="fa fa-plus s_compute"></i>
			</div>
		</div>


	</td>

	<td class="o_attr s_works_amount">

		<p>
			￥78.00
		</p>

	</td>
	<td class="o_attr o_attr_other o_attr_operate">
		<p>
			<a class="btn-tips">移入我的收藏</a><br>
			<a class="btn-del">删除</a><br>
		</p>
	</td>
</tr>--}}






