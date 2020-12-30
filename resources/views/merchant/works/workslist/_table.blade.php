<!-- table列表数据显示  -->

@forelse($list as $k=>$v)
	@inject('CommonPresenter','App\Presenters\CommonPresenter')

	<tr>
		<td class="works_msg works_info_td">
			<div class="works_info">
				<div class="works_opt">
					<input type="hidden" id="prj{{$v['prj_id']}}" value="{{$v['prj_file_type']}}" data-value="{{$v['prj_name']}}"/>
					@component('component/checkbox',['checkbox'=>[''],'data_value'=>$v['prj_id'],'name'=>['checkworks'],'custom_class'=>"checkedres checkbox",'left_distance'=>10,'right_distance'=>10])
					@endcomponent
				</div>
				<div class="works_img">
					@if($v['prj_image'])
						<a href="{{$v['url']}}" target="_blank"><img src="{{$v['prj_image']}}"/></a>
					@else
						<a href="{{$v['url']}}" target="_blank"><img src="{{URL::asset('images/default-photo.png')}}"/></a>
					@endif
				</div>
				<div class="works_detail">
					<p>{{$v['prj_name']}}</p>
					<p>作品编号：{{$v['prj_sn']}}</p>
					<p class="last_p"><a href="###" data-area="['50%', '50%']" class="btn-dialog" data-url="{{URL::asset('/works/workslist/log')}}?prj_id={{$v['prj_id']}}" data-title = "操作日志">作品操作日志</a></p>
					<span class="last_p" style="padding-top: 10px">
						<p id="mobile_preview" data-value="{{$v['prj_id']}}" style="margin: 10px 0;color: rgba(63, 81, 181, 1);cursor: pointer;">手机预览</p>
						<div id="qrcode{{$v['prj_id']}}" style="display: none;"></div>
						<input id="qrcode_url{{$v['prj_id']}}" type="hidden" value="{{$v['mobile_url']}}"/>
					</span>
					@if($v['empty_mask_count']>0 || $v['bad_px_count']>0)
						<p style="cursor: pointer">
							<span id="yicang" data-value="{{$v['prj_id']}}" style="color: red" >查看异常</span>
						</p>
					@endif
				</div>
			</div>
		</td>
		<td class="works_msg works_attr_td">
			<div class="works_attr">
				<div class="works_attr_detail">
					<p>货品名称：{{$v['prod']['prod_name']}}</p>
					<p>货品编号：{{$v['prod_sku']['prod_sku_sn']}}</p>
					<p class="last_p">p数：{{$v['prj_page_num']}}p</p>
				</div>
			</div>
		</td>
		<td class="works_status_td">{{$prjStatus[$v['prj_status']]}}</td>
		<td class="works_msg works_buy_td" style="width: 14%">
			<div class="works_attr">
				<div class="works_attr_detail">
					<p>【{{$v['cha_name']}}】</p>
					<p>{{$v['agent_name']}}</p>

				</div>
			</div>
		</td>
		<td class="works_msg works_buy_td" style="width: 14%">
			<div class="works_attr">
				<div class="works_attr_detail">
					<p>名称：{{$v['prj_temp']['prj_outer_account']}}</p>
					<p>电话：{{$v['prj_temp']['prj_rcv_phone']}}</p>
					<p>关联单号：{{$v['prj_temp']['order_no']}}</p>
				</div>
			</div>
		</td>
		{{--<td class="works_label_td">
			@foreach($v['prj_label'] as $key=>$val)
				@if($val!=""){{$prjLabel[$val]}}<br>@endif
			@endforeach
		</td>--}}
		<td class="works_operate_td">{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>
		<td class="works_operate_td">
			<div class="works_operate">
				@if($v['prj_status']==WORKS_DIY_STATUS_MAKING && $v['prj_file_type']!=2)<p><a href="{{$v['url']}}" target="_blank"><span>制作作品</span></a></p>@endif
				<p><a href="{{$v['url']}}" target="_blank"><span>预览作品</span></a></p>
				{{--@if($v['prj_status']==WORKS_DIY_STATUS_MAKING)<p><a onclick="review({{$v['prj_id']}})"><span>审核作品</span></a></p>@endif
				@if($v['prj_status']==WORKS_DIY_STATUS_WAIT_CONFIRM)
					<p><a href="###" @if($sync_sdk==0) class="btn-check" @else class="btn-tongbu-check" @endif data-id="{{$v['prj_id']}}"><span>订购作品</span></a></p>
				@endif
				@if($v['prj_status']==WORKS_DIY_STATUS_MAKING || $v['prj_status']==WORKS_DIY_STATUS_WAIT_CONFIRM)<p><a href="###" data-area="['70%', '70%']" class="btn-dialog" data-url="{{URL::asset('/works/workslist/edit')}}?prj_id={{$v['prj_id']}}" data-title = "修改作品"><span>修改作品</span></a></p>@endif
            @if($v['prj_status']!=WORKS_DIY_STATUS_DELETE)<p><a href="###" data-area="['70%', '70%']" class="btn-dialog" data-url="{{URL::asset('/works/remarks')}}?prj_id={{$v['prj_id']}}" data-title = "标签作品"><span>标签作品</span></a></p>@endif
				@if( ($v['prj_status']==WORKS_DIY_STATUS_WAIT_CONFIRM || $v['prj_status']==WORKS_DIY_STATUS_ORDER) && $v['prj_file_type']!=2)<p class=""><a href="###" data-area="['70%', '70%']" class="btn-dialog" data-url="{{URL::asset('/works/workslist/clone_works')}}?prj_id={{$v['prj_id']}}" data-title = "克隆作品"><span>克隆作品</span></a></p>@endif
				@if($v['prj_status']!=WORKS_DIY_STATUS_DELETE)<p><a class="btn-del" data-url="{{URL::asset('/works/workslist/delete/'.$v['prj_id'])}}" data-title="删除作品" data-text="作品删除后将被放进回收站，确认删除吗？" data-recover="1"><span>删除作品</span></a></p>@endif
				@if($v['prj_status']==WORKS_DIY_STATUS_DELETE)<p><a class="btn-del" data-url="{{URL::asset('/works/workslist/regain')}}?prj_id={{$v['prj_id']}}" data-title="恢复作品" data-text="确定恢复该作品吗？恢复后状态为制作中" data-recover="1"><span>恢复作品</span></a></p>@endif--}}
			</div>
		</td>
	</tr>
@empty
	<tr>
		<td colspan=8>暂无记录</td>
	</tr>
@endforelse