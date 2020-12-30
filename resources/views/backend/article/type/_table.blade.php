<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
	@inject('CommonPresenter','App\Presenters\CommonPresenter');
    <tr>
		<td>{{$v['art_type_name']}}</td>
		<td>@if(isset($channelArr[$v['channel_id']])) {{$channelArr[$v['channel_id']]}} @else 无 @endif </td>
		<td>{{$v['art_type_sign']}}</td>
		<td>
              <span @if($v['art_type_status']==ONE) style="color:green" @endif style="color:red" >
                {{$CommonPresenter->getEnabledOrDisabled($v['art_type_status'])}}
              </span>
		</td>
		<td>{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>
		<td>
		<p >
            <span class="oa_operate op_tbl btn-dialog" data-area="['60%', '60%']" data-url="{{URL::asset('/article/type/form')}}?id={{$v['art_type_id']}}" data-title = "编辑">编辑</span>
            <span class="btn-del oa_operate op_tbl" data-url="{{URL::asset('/article/type/del/'.$v['art_type_id'])}}" data-title="删除记录" data-text="您确认要删除此记录吗?" style="">删除</span>
        </p>
		</td>
    </tr>
    @empty
    <tr>
        <td colspan=6>暂无记录</td>
    </tr>
@endforelse
