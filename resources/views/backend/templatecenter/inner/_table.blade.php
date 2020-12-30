<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
	@inject('CommonPresenter','App\Presenters\CommonPresenter');
    <tr>
		<td><img src="{{$v['inner_temp_thumb']}}" style="height: 60px"/></td>
		<td>{{$v['inner_temp_name']}}</td>
		<td>@if(isset($tempThemeList[$v['inner_temp_theme_id']])){{$tempThemeList[$v['inner_temp_theme_id']]}} @else 无 @endif</td>
		<td>@if(isset($specList[$v['specifications_id']])){{$specList[$v['specifications_id']]}} @else 无 @endif</td>
		<td>{{$v['size_design_w']}}X{{$v['size_design_h']}}</td>
		<td>{{$v['size_location_top']}}X{{$v['size_location_left']}}X{{$v['size_location_bottom']}}X{{$v['size_location_right']}}</td>
		<td>
			<select name="cover_temp_check_status" class="check_status" data-id="{{$v['inner_temp_id']}}">
				@foreach($checkStatus as $ck=>$cv)
					<option value={{$ck}} @if($ck == $v['inner_temp_check_status']) selected @endif >{{$cv}}</option>
				@endforeach
			</select>
		</td>
		<td>{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>
		<td>
			<p style="margin:4px 0;">
				<a target="_blank" href="/#/templatecenter/inner/form?id={{$v['inner_temp_id']}}" class="oa_operate op_tbl" title = "编辑">编辑</a>
				<span class="oa_operate op_tbl btn-innerchild" data-url="{{URL::asset('/templatecenter/innerchild/childindex')}}?id={{$v['inner_temp_id']}}" data-title = "查看子页">子页</span>
				<a target="_blank" href="/ds/ed.html?sp=-1&t=inpage{{$v['inner_temp_id']}}" class="oa_operate op_tbl" title = "定制">定制</a>
				<span class="btn-del oa_operate op_tbl" data-url="{{URL::asset('/templatecenter/inner/del/'.$v['inner_temp_id'])}}" data-title="删除记录" data-text="您确认要删除此记录吗?" style="">删除</span>
			</p>
		</td>
    </tr>
    @empty
    <tr>
        <td colspan=11>暂无记录</td>
    </tr>
@endforelse
<style>
	td{ padding: 8px;}
</style>