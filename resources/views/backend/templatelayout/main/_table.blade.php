<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
	@inject('CommonPresenter','App\Presenters\CommonPresenter');
    <tr>
		<td><img src="{{$v['temp_layout_thumb']}}" style="width: 45px"/></td>
		<td>{{$v['temp_layout_name']}}</td>
		<td>@if(isset($layoutType[$v['temp_layout_type']])){{$layoutType[$v['temp_layout_type']]}} @else 无 @endif</td>
		<td>@if($v['layout_spec_style']==ZERO)无 @else {{$sizeType[$v['layout_spec_style']]}}@endif</td>
		<td>@if(isset($specLink[$v['specifications_id']])){{$specLink[$v['specifications_id']]}} @else 无 @endif</td>
		<td>
			<select name="layout_check_status" class="check_status" data-id="{{$v['temp_layout_id']}}">
				@foreach($checkStatus as $ck=>$cv)
					<option value={{$ck}} @if($ck == $v['layout_check_status']) selected @endif >{{$cv}}</option>
				@endforeach
			</select>
		</td>
		<td>{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>
		<td>{{$CommonPresenter->exchangeTime($v['updated_at'])}}</td>
		<td>
			<p style="margin:4px 0;">
				<span class="oa_operate op_tbl btn-dialog" data-area="['65%', '70%']" data-url="{{URL::asset('/templatelayout/main/form')}}?id={{$v['temp_layout_id']}}" data-title = "编辑">编辑</span>
				<span class="oa_operate op_tbl templayout-copy" data-id="{{$v['temp_layout_id']}}" title = "克隆">克隆</span>
				<a target="_blank" href="/ds/ed.html?sp=-1&lay={{$v['temp_layout_id']}}" class="oa_operate op_tbl" title = "定制">定制</a>
				<span class="btn-del oa_operate op_tbl" data-url="{{URL::asset('/templatelayout/main/del/'.$v['temp_layout_id'])}}" data-title="删除记录" data-text="您确认要删除此记录吗?" style="">删除</span>
			</p>
		</td>
    </tr>
    @empty
    <tr>
        <td colspan=12>暂无记录</td>
    </tr>
@endforelse
