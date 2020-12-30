<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
    @inject('CommonPresenter','App\Presenters\CommonPresenter');
    <tr>
        <td>{{$v['service_order_no']}}</td>
        <td>{{$v['order_no']}}</td>
        <td>￥{{$v['order_info']['order_real_total']}}</td>
        <td>{{$v['nums']}}件</td>
        <td>{{$CommonPresenter->exchangeService($v['job_type'])}}</td>
        <td>{{$CommonPresenter->exchangeHandel($v['job_status'])}}</td>
        <td>{{$v['handler']}}</td>
        {{--<td>{{$v['job_reason']}}</td>--}}
        <td>{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>
        <td>{{$CommonPresenter->exchangeTime($v['handle_time'])}}</td>
        <td><p style="margin-top: 17px">
                <span class="oa_operate op_tbl btn-dialog" data-area="['65%', '70%']" data-url="{{URL::asset('/order/service/form')}}?id={{$v['job_id']}}" data-title="详情">详情</span>
                @if($v['job_status'] != ORDER_AFTER_STATUS_WITHDRAW)
                {{--非撤回状态下--}}
                @if($v['job_status'] == ORDER_AFTER_STATUS_UNPROCESSED)
                    <span  class="btn-del oa_operate" data-url="{{URL::asset('/order/service/withdraw/'.$v['job_id'])}}" data-title = "提示" data-text="确定要撤回该售后单吗？" style="cursor:pointer;margin-right: 10px;color: rgba(63, 81, 181, 1)">撤回</span>
                @endif
                    @if($v['job_status'] == ORDER_AFTER_STATUS_UNPROCESSED)
                        {{--未处理状态下才可删除--}}
                        <span class="btn-del oa_operate" data-url="{{URL::asset('/order/service/del/'.$v['job_id'])}}" data-title="删除售后单" data-text="" style="cursor:pointer;margin-right: 10px;color: rgba(63, 81, 181, 1)">删除</span>
                    @endif
                @endif

            </p>
        </td>
    </tr>
@empty
    <tr>
        <td colspan=10>暂无记录</td>
    </tr>
@endforelse