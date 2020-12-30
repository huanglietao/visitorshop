@forelse ($list as $k=>$v)
	@inject('CommonPresenter','App\Presenters\CommonPresenter');
	<tr>
		<td style="text-align: center !important;">
			@component('component/checkbox',['checkbox'=>[''],'name'=>['checkworks[]'],'custom_class'=>"checkedres checkbox",'left_distance'=>25,'right_distance'=>15])
			@endcomponent
		</td>
		<td>
			<div class="news_title">
				@if(!isset($v['link_mch_news'][0]))
					<i class="news_readsign"></i>
				@endif
				<a href="/news/detail?id={{$v['art_id']}}" data-nid="{{$v['art_id']}}">
					<span class="newslist_left">{{$v['art_title']}}</span>
					@if(!isset($v['link_mch_news'][0]))
						<span class="newsign">NEW</span>
					@endif
				</a>
			</div>
		</td>
		{{--<td>@if(isset($artTypeList[$v['art_type']])) {!! $artTypeList[$v['art_type']] !!} @else 无 @endif </td>--}}
		<td>{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>
	</tr>
	@empty
		<tr>
			<td colspan=9 style="text-align: center !important;">暂无记录</td>
		</tr>
@endforelse
{{--
@foreach ($list as $k=>$v)
	<li>
		<a href="#" data-nid="{{$v['id']}}">
			<span class="newslist_left">{{$v['title']}}</span>
			@if($v['is_read']==0)
				<div class="newsign"><p>NEW</p></div>
			@endif
			<span class="newslist_right">{{$v['createtime']}}</span>
		</a>
	</li>
@endforeach--}}
