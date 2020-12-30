<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
    @inject('CommonPresenter','App\Presenters\CommonPresenter')
    <tr>
        <td>{{$v['order_id']}}</td>
        <td>{{$v['order_push_status']}}</td>
        <td>{{$v['err_msg']}}</td>
        <td>{{$CommonPresenter->exchangeTime($v['start_time'])}}</td>
        <td>{{$CommonPresenter->exchangeTime($v['end_time'])}}</td>
        <td>
            <p>
                <span class="oa_operate op_tbl"  onclick="alert('此功能等待实现')">重新推送</span>
            </p>
        </td>
    </tr>
@empty
    <tr>
        <td colspan=6>暂无记录</td>
    </tr>
@endforelse
