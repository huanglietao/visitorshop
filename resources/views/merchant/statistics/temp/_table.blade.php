<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
	{{--@inject('CommonPresenter','App\Presenters\CommonPresenter')--}}
	<tr>
		<td>{{$v['main_temp_name']}}</td>
		<td>@if(isset($tempThemeList[$v['main_temp_theme_id']])) {{$tempThemeList[$v['main_temp_theme_id']]}} @else 无 @endif</td>
		<td>{{$v['timesCount']}}</td>
		{{--<td>{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>--}}
	</tr>
@empty
	<tr>
		<td colspan=5>暂无记录</td>
	</tr>
@endforelse
