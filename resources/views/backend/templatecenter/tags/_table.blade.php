<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
    @inject('CommonPresenter','App\Presenters\CommonPresenter');
    <tr>
		<td>{{$v['temp_tags_id']}}</td>
		<td>{{$v['temp_tages_name']}}</td>
        <td>{{$v['temp_tags_sign']}}</td>
		<td>
            <span @if($v['temp_tags_status']==ONE) style="color:green" @endif style="color:red" >
                {{$CommonPresenter->getEnabledOrDisabled($v['temp_tags_status'])}}</span>
        </td>
        <td>{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>
		<td>
		<p style="margin:4px 0;">
            <span class="oa_operate op_tbl btn-dialog" data-area="['60%', '55%']" data-url="{{URL::asset('/templatecenter/tags/form')}}?id={{$v['temp_tags_id']}}" data-title = "编辑">编辑</span>
            <span class="btn-del oa_operate op_tbl" data-url="{{URL::asset('/templatecenter/tags/del/'.$v['temp_tags_id'])}}" data-title="删除记录" data-text="您确认要删除此记录吗?" style="">删除</span>
        </p>
		</td>
    </tr>
    @empty
    <tr>
        <td colspan=7>暂无记录</td>
    </tr>
@endforelse
