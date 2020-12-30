<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
	@inject('CommonPresenter','App\Presenters\CommonPresenter');
    <tr>
		<td>{{$v['art_title']}}</td>
		<td>@if(isset($artTypeList[$v['art_type']])) {!! $artTypeList[$v['art_type']] !!} @else 无 @endif </td>
{{--		<td>@if(isset($channelArr[$v['channel_id']])) {{$channelArr[$v['channel_id']]}} @else 无 @endif </td>--}}
		<td>{{$v['art_sign']}}</td>
		<td>{{$v['art_author']}}</td>
		<td>{{$CommonPresenter->getYesOrNo($v['is_open'])}}</td>
		<td>{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>
		<td>
		<p >
            <span class="oa_operate op_tbl btn-dialog" data-area="['60%', '60%']" data-url="{{URL::asset('/article/list/form')}}?id={{$v['art_id']}}" data-title = "编辑">编辑</span>
            <span class="btn-del oa_operate op_tbl" data-url="{{URL::asset('/article/list/del/'.$v['art_id'])}}" data-title="删除记录" data-text="您确认要删除此记录吗?" style="">删除</span>
        </p>
		</td>
    </tr>
    @empty
    <tr>
        <td colspan=8>暂无记录</td>
    </tr>
@endforelse
