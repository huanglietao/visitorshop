@forelse  ($list as $k=>$v)
    @inject('CommonPresenter','App\Presenters\CommonPresenter');
    <tr>
        <td>{{$v['partner_code']}}</td>
        <td style="padding-top: 10px;padding-bottom: 10px;"><p>【{{$v['patner_real_name']}}】</p>{{$v['partner_name']}}</td>
        <td>{{$v['recharge_no']}}</td>
        <td>{{$CommonPresenter->getAlipayOrWechat($v['pay_type'])}}</td>
        <td>{{$v['trade_no']}}</td>
        <td>{{$v['amount']}}</td>
        <td>{{$v['handling_fee']}}</td>
        <td>{{$v['createtime']}}</td>
        <td>{{$v['finishtime']}}</td>
        <td>{{$CommonPresenter->getSuccessOrFail($v['capital_change_status'])}}</td>
        <td>
            <p style="margin-bottom: 0">
                <span style="margin-right: 0" class="oa_operate op_tbl btn-dialog" data-area="['65%', '70%']" data-url="{{URL::asset('/finance/record/form')}}?id={{$v['id']}}" data-title = "详情">详情</span>
            </p>
        </td>
    </tr>
@empty
    <tr>
        <td colspan=9>暂无记录</td>
    </tr>
@endforelse




