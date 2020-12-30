<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
	@inject('CommonPresenter','App\Presenters\CommonPresenter');
    <tr>
		<td>{{$v['main_temp_page_name']}}</td>
		<td>@if(isset($pageType[$v['main_temp_page_type']])){{$pageType[$v['main_temp_page_type']]}} @else 无 @endif</td>
		<td>@if(isset($isCross[$v['size_is_cross']])){{$isCross[$v['size_is_cross']]}} @else 无 @endif</td>
		<td>{{$v['size_design_w']}}X{{$v['size_design_h']}}</td>
		<td>{{$v['main_temp_page_dpi']}}</td>
		<td>{{$v['main_temp_page_sort']}}</td>
		<td>{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>
		<td>
		<p style="margin:4px 0;">
            <span class="btn-dialog oa_operate op_tbl" data-area="['50%', '70%']" data-url="{{URL::asset('/templatecenter/mainchild/form')}}?id={{$v['main_temp_page_id']}}" data-title = "编辑">编辑</span>
			<span class="btn-dialog oa_operate op_tbl" data-area="['40%', '30%']" data-url="{{URL::asset('/templatecenter/mainchild/copy')}}?pageid={{$v['main_temp_page_id']}}" data-title = "克隆{{$v['main_temp_page_name']}}">克隆</span>
			<span class="btn-del oa_operate op_tbl" data-url="{{URL::asset('/templatecenter/mainchild/del/'.$v['main_temp_page_id'])}}" data-title="删除记录" data-text="您确认要删除此记录吗?" style="">删除</span>
        </p>
		</td>
    </tr>
    @empty
    <tr>
        <td colspan=15>暂无记录</td>
    </tr>
@endforelse
