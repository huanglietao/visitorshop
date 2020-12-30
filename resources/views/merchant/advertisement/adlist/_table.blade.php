<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
	@inject('CommonPresenter','App\Presenters\CommonPresenter');
	<tr>
		<td>
			@if(!empty($v['ad_images']))
				@foreach($v['ad_images'] as $kk=>$vv)
				<img src="{{$vv}}" style="width: 40px"/>
				@endforeach
			@else
				无
			@endif
		</td>
		<td>{{$v['ad_title']}}</td>
		<td>@if(isset($channelArr[$v['channel_id']])) {{$channelArr[$v['channel_id']]}} @else 无 @endif </td>
		<td>@if(isset($adType[$v['ad_type']])) {{$adType[$v['ad_type']]}} @else 无 @endif </td>
		<td>@if(isset($posList[$v['ad_position']]))<span @if($v['ad_position']==ONE) style="color: red" @endif> {{$posList[$v['ad_position']]}}</span> @else 无 @endif </td>
	{{--	<td>{{$v['ad_flag']}}</td>--}}
		<td>{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>
		<td>
			<p >
				<span class="oa_operate op_tbl btn-dialog" data-area="['65%', '60%']" data-url="{{URL::asset('/advertisement/adlist/form')}}?id={{$v['ad_id']}}" data-title = "编辑">编辑</span>
				{{--<span class="btn-del oa_operate op_tbl" data-url="{{URL::asset('/advertisement/adlist/del/'.$v['ad_id'])}}" data-title="删除记录" data-text="您确认要删除此记录吗?" style="">删除</span>--}}
			</p>
		</td>
	</tr>
    @empty
    <tr>
        <td colspan=8>暂无记录</td>
    </tr>
@endforelse
