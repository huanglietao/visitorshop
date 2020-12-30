<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
	@inject('CommonPresenter','App\Presenters\CommonPresenter')
	<tr class="cart_tr">

		<td class="first_td" >
		{{--	@component('component/checkbox',['checkbox'=>[''],'name'=>['checkbox[]'],'custom_class'=>"cc_checkedres checkbox",'right_distance'=>10])
			@endcomponent--}}
		</td>
		<td class="o_attr_goods">

			<div class="o_works_info">

				<div class="o_works_img sc_works_img">
					<img src="{{$v['prod_main_thumb']??""}}">
				</div>
				<div class="s_works_detail ">
					<p class="s_works_name" style="margin-bottom: 8px!important;">{{$v['prod_name']}}</p>
					<p class="o_works_spec" style="margin-bottom: 8px!important;">商品编码：{{$v['prod_sn']}}</p>
					<p class="o_works_spec">品牌：{{$v['prod_brand_name']}}</p>
				</div>

			</div>

		</td>
		<td class="o_attr s_works_spec d_d_o_works_spec_info ">
			<p class="s_o_works_spec">{{$v['prod_cate_uid']}}</p>
		</td>
		<td class="o_attr">
			<div class="s_works_detail ">
				<p class="o_works_spec" style="margin-bottom: 8px!important;">价格:{{$v['prod_fee']}}</p>
				<p class="o_works_spec" style="margin-bottom: 8px!important;">运费:{{$v['prod_express_fee']}}</p>
			</div>
		</td>
		{{--<td class="o_attr s_works_amount">

			<div class="s_works_detail ">

				<div class="s_works_name" style="margin-bottom: 8px!important;">
					<span>精品</span>
					@component('component/switch')
					@endcomponent
				</div>
				<div class="o_works_spec" style="margin-bottom: 8px!important;">
					<span>新品</span>
					@component('component/switch',['custom_class'=> 'prod_label','data_id' => 15,'status' => 1])
					@endcomponent
				</div>
				<div class="o_works_spec" style="margin-bottom: 8px!important;">
					<span>热品</span>
					@component('component/switch',['status' => 0,'data_id' => 15])
					@endcomponent
				</div>

			</div>

		</td>--}}
		<td class="o_attr s_works_spec d_d_o_works_spec_info " style="width: 10%">
			<input class="s_o_works_spec s_o_prod_sort" style="width: 30px;text-align: center; margin-top: -10px;" value="{{$v['sort']}}"  data-gid="{{$v['prod_id']}}"/>
		</td>
		{{--<td class="o_attr s_works_amount" style="width: 10%">

			<p>
			<div class="s_num_d">
			<p class="s_o_works_spec">{{$v['sort']}}</p>
				<input class="s_num_input" type="text" name="" id="" value="{{$v['sort']}}">
			</div>
			</p>

		</td>--}}

		<td class="o_attr s_works_num" style="width: 10%">
			<div class="s_works_num_main">
				<p class="s_o_works_spec">{{$v['prod_stock_inventory']}}</p>
				{{--<div class="s_num_d">
					<input class="s_num_input" type="text" name="" id="" value="{{$v['prod_stock_inventory']}}">
				</div>--}}
			</div>


		</td>

		<td>
			<div class="o_attr s_works_detail ">
				<div class="s_works_name" >
					@component('component/switch',['status' => $v['prod_onsale_status'],'data_id' => $v['prod_id']])
					@endcomponent
					<span>上架</span>
				</div>

			</div>
		</td>
		<td class="o_attr s_works_spec d_d_o_works_spec_info " style="width: 10%">
			{{$CommonPresenter->exchangeTime($v['created_at'])}}
		</td>
		{{--<td class="o_attr s_works_detail">
            <span>无需审核</span>
        </td>--}}

		<td class="o_attr s_works_detail" style="color: #3F51B5">
			<p class="o_works_spec op_tbl goods-edit" style="margin-bottom: 4px!important;vertical-align: top;" data-url="{{URL::asset('/goods/products/edit/'.$v['prod_id'])}}">编辑</p>
			{{--<p class="o_works_spec op_tbl" style="margin-bottom: 4px!important;">日志</p>
			<p class="o_works_spec op_tbl" style="margin-bottom: 4px!important;">审核</p>--}}
			<p class="o_works_spec btn-del op_tbl" style="margin-bottom: 4px!important;"  data-url="{{URL::asset('/goods/products/del/'.$v['prod_id'])}}" data-title="删除记录" data-text="您确认要删除此记录吗?">删除</p>
		</td>
	</tr>


@empty
    <tr>
        <td colspan=20>暂无记录</td>
    </tr>
@endforelse
{{--第一个tr--}}






