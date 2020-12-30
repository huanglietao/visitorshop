<!-- table列表数据显示  -->
@forelse  ($list as $k=>$v)
    @inject('CommonPresenter','App\Presenters\CommonPresenter')
    <tr>
        <td>{{$v['recharge_no']}}</td>
        <td>{{$v['amount']}}</td>
        <td>
            @if($v['capital_change_status']==ZERO)未入账
            @else 已入账
            @endif
        </td>
        <td>
            @if($v['pay_type']==ONE)支付宝
            @else 微信
            @endif
        </td>
        <td>{{$v['trade_no']}}</td>
        <td>{{$CommonPresenter->exchangeTime($v['created_at'])}}</td>
        <td>{{$CommonPresenter->exchangeTime($v['finishtime'])}}</td>
        <td>
            <p>
                <span class="oa_operate op_tbl btn-dialog" data-area="['70%', '70%']" data-url="{{URL::asset('/finance/accountrecharge/info')}}?id={{$v['finance_doc_id']}}" data-title = "详情">详情</span>
                {{--<span class="btn-del oa_operate op_tbl" data-url="{{URL::asset('/agent/account/del/'.$v['finance_doc_id'])}}" data-title="删除记录" data-text="您确认要删除此记录吗?" style="">删除</span>--}}
            </p>
        </td>
    </tr>
@empty
    <tr>
        <td colspan=8>暂无记录</td>
    </tr>
@endforelse



{{--<!-- table列表数据显示  -->--}}
{{--<tr>--}}
    {{--<td>01190126090921402</td>--}}
    {{--<td>1000.00</td>--}}
    {{--<td>未确认，未入账</td>--}}
    {{--<td>线下入账</td>--}}
    {{--<td>2019080522001157420598394810</td>--}}
    {{--<td>2019-08-19 01:11:56</td>--}}
    {{--<td>2019-08-19 01:11:56</td>--}}
    {{--<td>2019-08-19 01:11:56</td>--}}
    {{--<td>N/A</td>--}}
    {{--<td class="account-recharge-four">--}}
        {{--<div style="text-align: left;width: 100%;">--}}
            <a href="#" class="btn-dialog" data-url="{{URL::asset('/finance/accountrecharge/info')}}" data-title="详情">详情</a>
<a href="#" class="btn-dialog" data-url="{{URL::asset('/finance/accountrecharge/info')}}" data-title="编辑">编辑</a>
<a href="#" class="btn-del" data-url="{{URL::asset('/finance/accountrecharge/cancel')}}" data-title="温馨提示" data-text="取消后该条记录将被删除，确认取消吗？">取消</a>
        {{--</div>--}}
    {{--</td>--}}
{{--</tr>--}}
{{--<tr>--}}
    {{--<td>01190126090921402</td>--}}
    {{--<td>1000.00</td>--}}
    {{--<td>已确认，未到账</td>--}}
    {{--<td>线下入账</td>--}}
    {{--<td>2019080522001157420598394810</td>--}}
    {{--<td>2019-08-19 01:11:56</td>--}}
    {{--<td>2019-08-19 01:11:56</td>--}}
    {{--<td>2019-08-19 01:11:56</td>--}}
    {{--<td>Frila</td>--}}
    {{--<td class="account-recharge-four">--}}
        {{--<div style="text-align: left;width: 100%;">--}}
            {{--<a href="#" class="btn-dialog" data-url="{{URL::asset('/finance/accountrecharge/info')}}" data-title="详情">详情</a><a href="#" class="btn-dialog" data-url="{{URL::asset('/finance/accountrecharge/info')}}" data-title="编辑">编辑</a>--}}
        {{--</div>--}}
    {{--</td>--}}
{{--</tr>--}}
{{--<tr>--}}
    {{--<td>01190126090921402</td>--}}
    {{--<td>1000.00</td>--}}
    {{--<td>已确认，已入账</td>--}}
    {{--<td>线下入账</td>--}}
    {{--<td>2019080522001157420598394810</td>--}}
    {{--<td>2019-08-19 01:11:56</td>--}}
    {{--<td>2019-08-19 01:11:56</td>--}}
    {{--<td>2019-08-19 01:11:56</td>--}}
    {{--<td>Frila</td>--}}
    {{--<td class="account-recharge-four">--}}
        {{--<div style="text-align: left;width: 100%;">--}}
            {{--<a href="#" class="btn-dialog" data-url="{{URL::asset('/finance/accountrecharge/info')}}" data-title="详情">详情</a>--}}
        {{--</div>--}}
    {{--</td>--}}
{{--</tr>--}}
{{--<tr>--}}
    {{--<td>01190126090921402</td>--}}
    {{--<td>1000.00</td>--}}
    {{--<td>已确认，已入账</td>--}}
    {{--<td>线下入账</td>--}}
    {{--<td>2019080522001157420598394810</td>--}}
    {{--<td>2019-08-19 01:11:56</td>--}}
    {{--<td>2019-08-19 01:11:56</td>--}}
    {{--<td>2019-08-19 01:11:56</td>--}}
    {{--<td>SYSTEM</td>--}}
    {{--<td class="account-recharge-four">--}}
        {{--<div style="text-align: left;width: 100%;">--}}
            {{--<a href="#" class="btn-dialog" data-url="{{URL::asset('/finance/accountrecharge/info')}}" data-title="详情">详情</a><a href="#" class="btn-del" data-url="{{URL::asset('/finance/accountrecharge/cancel')}}" data-title="温馨提示" data-text="取消后该条记录将被删除，确认取消吗？">取消</a>--}}
        {{--</div>--}}
    {{--</td>--}}
{{--</tr>--}}
{{--<tr>--}}
    {{--<td>01190126090921402</td>--}}
    {{--<td>1000.00</td>--}}
    {{--<td>已取消</td>--}}
    {{--<td>线下入账</td>--}}
    {{--<td>2019080522001157420598394810</td>--}}
    {{--<td>2019-08-19 01:11:56</td>--}}
    {{--<td>2019-08-19 01:11:56</td>--}}
    {{--<td>2019-08-19 01:11:56</td>--}}
    {{--<td>Frila</td>--}}
    {{--<td class="account-recharge-four">--}}
        {{--<div style="text-align: left;width: 100%;">--}}
            {{--<a href="#" class="btn-dialog" data-url="{{URL::asset('/finance/accountrecharge/info')}}" data-title="详情">详情</a>--}}
        {{--</div>--}}
    {{--</td>--}}
{{--</tr>--}}



