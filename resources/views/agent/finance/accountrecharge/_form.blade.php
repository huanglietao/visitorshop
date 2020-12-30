<!-- form表单视图 -->
@inject('CommonPresenter','App\Presenters\CommonPresenter')
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/demo/save" onsubmit="return false;" autocomplete="off">

        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                 充值订单号：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 5px">
                <div class="row">
                <span>{{$row['recharge_no']}}</span>
                </div>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                充值金额：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 5px">
                <span>￥ {{$row['amount']}}</span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                参与活动：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 5px">
                <span>{{$row['rule_name']}}</span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                支付类型：</label>
            <div class="col-xs-12 col-sm-10 account-recharge-five" style="padding-top: 5px">
                <span>
                    @if($row['pay_type']==ONE)支付宝
                    @else 微信
                    @endif
                </span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                状态：</label>
            <div class="col-xs-12 col-sm-10 account-recharge-five" style="padding-top: 5px">
                <span>
                    @if($row['capital_change_status']==ZERO)未入账
                    @else 已入账
                    @endif
                </span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                关联支付流水号：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 5px">
                <span>{{$row['trade_no']}}</span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                充值备注：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 5px">
                <span>{{$row['note']}}</span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                创建时间：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 5px">
                <span>{{$CommonPresenter->exchangeTime($row['created_at'])}}</span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                到账时间：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 5px">
                <span>{{$CommonPresenter->exchangeTime($row['finishtime'])}}</span>
            </div>
        </div>

    </form>
    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button class="btn-sure" id="del-cancel">确定</button>
        </div>
    </div>
</div>

