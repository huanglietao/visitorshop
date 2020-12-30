<!-- table列表数据显示  -->

@forelse($list as $k=>$v)
	@inject('CommonPresenter','App\Presenters\CommonPresenter')

	<tr style="text-align: left">
		<td style="padding-left: 10px">
			<div>
				<div>
					@component('component/checkbox',['checkbox'=>[''],'data_value'=>$v['cus_pri_id'],'name'=>['checkedres'],'custom_class'=>"checkedres checkbox",'left_distance'=>10])
					@endcomponent
				</div>
			</div>
		</td>
		<td>
			{{$v['pri_trade_no']}}
		</td>
		<td>
		{{$v['express_str']}}
		</td>
		<td>{{$v['waybill_code']}}</td>
		<td style="text-align: left!important;">
			<div style="position: relative">
				<span>{{$v['pri_rece_username']}}&nbsp;{{$v['pri_rece_tel']}}@if(!empty($v['pri_rece_tel']) && !empty($v['pri_rece_mobile']))/@endif{{$v['pri_rece_mobile']}}</span><br/>
				<span>{{$v['pri_rece_province']}}&nbsp;{{$v['pri_rece_city']}}&nbsp;{{$v['pri_rece_area']}}</span><br/>
				<span>{{$v['pri_rece_address']}}</span>
				<i class="fa fa-pencil-square-o pri_edit btn-dialog" data-area="['65%', '60%']" data-url="{{URL::asset('/custom_print/print/info-edit/'.$v['cus_pri_id'].'/consignee')}}" data-title = "调整收件人信息" data-value="{{$v['cus_pri_id']}}" data-type="consignee" style="position: absolute;right: 5px;bottom: 0;font-size: 14px;cursor: pointer"></i>
			</div>
		</td>
		<td style="text-align: left!important;">
			<div style="position: relative">
			<span>{{$v['pri_send_username']}}&nbsp;{{$v['pri_send_tel']}}@if(!empty($v['pri_send_tel']) && !empty($v['pri_send_mobile']))/@endif{{$v['pri_send_mobile']}}</span><br/>
			<span>{{$v['pri_send_province']}}&nbsp;{{$v['pri_send_city']}}&nbsp;{{$v['pri_send_area']}}</span><br/>
			<span>{{$v['pri_send_address']}}</span>
			<i class="fa fa-pencil-square-o pri_edit btn-dialog" data-area="['65%', '60%']" data-url="{{URL::asset('/custom_print/print/info-edit/'.$v['cus_pri_id'].'/sender')}}" data-title = "调整寄件人信息"  data-value="{{$v['cus_pri_id']}}" data-type="sender"  style="position: absolute;right: 5px;bottom: 0;font-size: 14px;cursor: pointer"></i>
			</div>
		</td>
		<td>
			{{$v['prod_name']}}
			@if(!empty($v['prod_attribute']))&nbsp;{{$v['prod_attribute']}}@endif
			@if(!empty($v['prod_num']))&nbsp;{{$v['prod_num']}}件@endif
			@if(!empty($v['pri_weight']))&nbsp;{{$v['pri_weight']}}Kg @endif
			@if(!empty($v['pri_volume']))&nbsp;{{$v['pri_volume']}}m³ @endif
		</td>
		<td>{{$v['pri_note']}}</td>

		<td>{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>
		<td>
			@if($v['is_print'])已打印@else未打印@endif
		</td>
		<td>
			<span class="btn-del oa_operate op_tbl" data-url="{{URL::asset('/custom_print/print/del/'.$v['cus_pri_id'])}}" data-title="删除记录" data-text="您确认要删除此记录吗?" style="">删除</span>
		</td>

        </tr>
    @empty
        <tr>
            <td colspan=12>暂无记录</td>
        </tr>
    @endforelse