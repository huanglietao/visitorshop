<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
	@inject('CommonPresenter','App\Presenters\CommonPresenter');
    <tr>
		<td><img src="{{$v['main_temp_thumb']}}" style="height: 60px"/></td>
		<td>{{$v['main_temp_name']}}</td>
		<td>@if(isset($tempThemeList[$v['main_temp_theme_id']])){{$tempThemeList[$v['main_temp_theme_id']]}} @else 无 @endif</td>
		<td>@if(isset($specList[$v['specifications_id']])){{$specList[$v['specifications_id']]}} @else 无 @endif</td>
		<td>
			<a href="javascript:;" data-url="{{URL::asset('/templatecenter/mainchild/childindex')}}?id={{$v['main_temp_id']}}" class="addtabsit btn btn-xs btn-default btn-tempchild" title="子页数">{{$v['page_count']}}</a>
		</td>

		{{--<td>
			<a href="javascript:;" data-flag="main_temp_is_vip={{$v['main_temp_is_vip']}}" data-id="{{$v['main_temp_id']}}" class="addtabsit btn btn-xs btn-change @if($v['main_temp_is_vip']==0)btn-default @else btn-info @endif" title="子页数">{{$yn[$v['main_temp_is_vip']]}}</a>
		</td>--}}
		<td>
			<a href="javascript:;" data-flag="main_temp_is_ads_display={{$v['main_temp_is_ads_display']}}" data-id="{{$v['main_temp_id']}}" class="addtabsit btn btn-xs @if($v['main_temp_is_ads_display']==0)btn-default @else btn-info @endif btn-change" title="">{{$yn[$v['main_temp_is_ads_display']]}}</a>
		</td>
		<td>
			<select name="main_temp_check_status" class="check_status" data-id="{{$v['main_temp_id']}}">
				@foreach($checkStatus as $ck=>$cv)
					<option value={{$ck}} @if($ck == $v['main_temp_check_status']) selected @endif >{{$cv}}</option>
				@endforeach
			</select>
		</td>

		<td>{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>
		<td>
		<p style="margin:4px 0;">
			<a target="_blank" href="/#/templatecenter/main/form?id={{$v['main_temp_id']}}" class="oa_operate op_tbl" title = "编辑">编辑</a>
            {{--<span class="oa_operate op_tbl btn-temp" data-url="{{URL::asset('/templatecenter/main/form')}}?id={{$v['main_temp_id']}}" data-title = "编辑">编辑</span>--}}
			<span class="oa_operate op_tbl temp-copy" data-id="{{$v['main_temp_id']}}" title = "克隆">克隆</span>
			<a target="_blank" href="/ds/ed.html?sp=-1&t={{$v['main_temp_id']}}" class="oa_operate op_tbl" title = "定制">定制</a>
			<span class="btn-del oa_operate op_tbl" data-url="{{URL::asset('/templatecenter/main/del/'.$v['main_temp_id'])}}" data-title="删除记录" data-text="您确认要删除此记录吗?" style="">删除</span>
		</p>
		</td>
    </tr>
    @empty
    <tr>
        <td colspan=15>暂无记录</td>
    </tr>
@endforelse
<style>
	.btn-info {background-color: #3498db;
		border-color: #ddd;
		margin-left: 3% !important;
		height: 30px;
	}
	 td{ padding: 8px;}
</style>