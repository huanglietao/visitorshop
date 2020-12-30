<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
    @inject('CommonPresenter','App\Presenters\CommonPresenter');
    <tr>
		<td>{{$v['id']}}</td>
        <td>{{$v['log_file']}}</td>
        <td>
            <a href="javascript:;" data-flag="{{$v['is_solved']}}" data-id="{{$v['id']}}" class="btn-change addtabsit btn btn-xs @if($v['is_solved']==ZERO)btn-default @else btn-info @endif" title="">{{$yn[$v['is_solved']]}}</a>

        </td>
		<td style="word-break: break-all">{{$v['title']}}</td>
        <td style="word-break: break-all">{{$v['file']}}</td>
        <td>{{$v['line']}}</td>
		<td>{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>
    </tr>
    @empty
    <tr>
        <td colspan=7>暂无记录</td>
    </tr>
@endforelse
