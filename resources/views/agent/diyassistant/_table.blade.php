<!-- table列表数据显示  -->
@forelse  ($workList as $k=>$v)
	@inject('CommonPresenter','App\Presenters\CommonPresenter')
	<tr class="cart_tr">

		<td class="first_td" >
		{{--	@component('component/checkbox',['checkbox'=>[''],'name'=>['checkbox[]'],'custom_class'=>"cc_checkedres checkbox",'right_distance'=>10])
			@endcomponent--}}
		</td>
		<td class="o_attr_goods">

			<div class="o_works_info">

				<div class="o_works_img sc_works_img">
					<img src="{{$v['prod_image']}}"  onerror="this.src='/images/home/moren.jpg'">
				</div>
				<div class="s_works_detail ">
					<p class="s_works_name" style="width:80%; margin-bottom: 8px!important;word-break: break-all;">{{$v['prod_name']}}</p>
					<p class="o_works_spec" style="width:80%;margin-bottom: 8px!important;word-break: break-all;">{{$v['prod_attr']}}</p>
				</div>

			</div>

		</td>
		<td class="o_attr s_works_spec d_d_o_works_spec_info ">
			<p class="s_o_works_spec">{{$v['sku_sn']}}</p>
		</td>
		<td class="o_attr">
			<div class="s_works_detail ">
				<p class="o_works_spec" style="margin-bottom: 8px!important;">{{$v['prod_num']}}</p>
			</div>
		</td>
		<td class="o_attr s_works_spec d_d_o_works_spec_info " style="margin: 10px 5px 10px 0;">
			@if($v['prod_cate_flag'] == GOODS_MAIN_CATEGORY_PRINTER)
				<p class="s_o_works_spec" style="color: @if($v['already_submit']<$v['prod_num'])rgba(242, 17, 17, 1); @else rgba(0, 171, 103, 1); @endif">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$v['already_submit']}}/{{$v['prod_num']}}
					{{--<img src="/images/eye.png" class="s_o_work_eye" style="width: 14px;vertical-align: text-top;margin-left: 2px;cursor: pointer" title="查看"> --}}</p>
				<p class="s_o_works_spec">制作中:&nbsp;<span style="color: rgba(64, 158, 255, 0.9)">{{$v['making']}}</span></p>
				<p class="s_o_works_spec">已提交:&nbsp;<span style="color: rgba(64, 158, 255, 0.9)">{{$v['already_submit']}}</span></p>
				<p class="s_o_works_spec">待制作:&nbsp;@if($v['waiting_make']<0)0 @else{{$v['waiting_make']}}@endif
					{{--@if($v['waiting_make']>0)<a href="/goods/detail/template?mid={{$mch_id}}&aid={{$agent_id}}&prod_id={{$v['prod_id']}}&prod_attr_comb={{$v['prod_attr_comb']}}&order_no={{$order_no}}" target="_blank"><i class="fa fa-plus" title="继续制作" style="cursor: pointer;color:rgba(242, 17, 17, 1) "></i></a>@endif--}}
				</p>
				<input type="hidden" class="sku_id" value="{{$v['sku_id']}}">
			@else
				@if($v['isset_sku'] == 1)
					<p style="color: rgba(0, 171, 103, 1)">已完成</p>
					@else
					<p style="color:rgba(242, 17, 17, 1)">该货品不存在</p>
					@endif
			@endif
		</td>
		<td>
			@if($v['prod_cate_flag'] == GOODS_MAIN_CATEGORY_PRINTER)
				<span class="s_o_work_eye" data-sku-id="{{$v['sku_id']}}" data-sku-sn = "{{$v['sku_sn']}}">查看作品</span>
				{{--冲印与其他的商品编辑器地址不同--}}
				@if($v['is_single'])
					@if($v['waiting_make']>0)<a class="go_design" href="/printer/index.html?sp={{$mch_id}}&a={{$agent_id}}&g={{$v['prod_id']}}&p={{$v['sku_id']}}&t={{$v['temp_id']}}&pc={{$v['pc']}}&order_no={{$order_no}}" target="_blank">开始设计</a>@endif
				@else
					@if($v['waiting_make']>0)<a class="go_design" href="/goods/detail/template?mid={{$mch_id}}&aid={{$agent_id}}&prod_id={{$v['prod_id']}}&prod_attr_comb={{$v['prod_attr_comb']}}&order_no={{$order_no}}" target="_blank">开始设计</a>@endif
				@endif
			@endif
		</td>

	</tr>


@empty
    <tr>
        <td colspan=20 style="text-align: center;">暂无记录</td>
    </tr>
@endforelse
{{--第一个tr--}}






