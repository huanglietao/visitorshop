<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
    @inject('CommonPresenter','App\Presenters\CommonPresenter');
    <tr>
        <td>
            @if(!empty($v['ad_thumb']))
            <img src="{{$v['ad_thumb']}}" style="width: 60px"/>
            @else
                无
            @endif
        </td>
        <td>@if(isset($channelArr[$v['channel_id']])) {{$channelArr[$v['channel_id']]}} @else 无 @endif </td>
        <td>{{$v['ad_position']}}</td>
        <td>{{$v['pos_flag']}}</td>
        <td>
              <span @if($v['ad_status']==ONE) style="color:green" @endif style="color:red" >
                {{$CommonPresenter->getEnabledOrDisabled($v['ad_status'])}}
              </span>
        </td>
        <td>{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>
		<td>
		<p >
            <span class="oa_operate op_tbl btn-dialog" data-area="['60%', '55%']" data-url="{{URL::asset('/advertisement/adposition/form')}}?id={{$v['ad_pos_id']}}" data-title = "编辑">编辑</span>
            {{--<span class="btn-del oa_operate op_tbl" data-url="{{URL::asset('/advertisement/adposition/del/'.$v['ad_pos_id'])}}" data-title="删除记录" data-text="您确认要删除此记录吗?" style="">删除</span>--}}
        </p>
		</td>
    </tr>
    @empty
    <tr>
        <td colspan=5>暂无记录</td>
    </tr>
@endforelse
