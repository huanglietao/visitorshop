<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
    <tr>
        <td>{{$v['sys']}}</td>
        <td>{{$v['modules']}}</td>
        <td>{{$v['code']}}</td>
        <td>{{$v['message']}}</td>
        <td>{{$v['line']}}</td>
        <td>
            <span class="oa_operate op_tbl btn-dialog" data-area="['65%', '70%']" data-url="{{URL::asset('systemerror/index/form/'.$v['_id'])}}" data-title = "详情">详情</span>
            <span class="btn-del oa_operate" data-url="{{URL::asset('systemerror/index/del/'.$v['_id'])}}" data-title="删除记录" data-text="您确定要删除该条错误吗?" style="cursor: pointer;margin-right: 10px;color: rgba(63, 81, 181, 1)">删除</span>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan=12>暂无记录</td>
    </tr>
@endforelse
