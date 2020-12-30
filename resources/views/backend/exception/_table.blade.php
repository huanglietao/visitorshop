<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
    <tr>
        <td>{{$v['ecode']}}</td>
        <td>{{$v['evalue']}}</td>


    </tr>
    @empty
    <tr>
        <td colspan=2>暂无记录</td>
    </tr>
@endforelse
