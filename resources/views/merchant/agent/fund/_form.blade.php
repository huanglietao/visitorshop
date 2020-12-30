<!-- form表单视图 -->
@inject('CommonPresenter','App\Presenters\CommonPresenter');

<div style="margin-top:30px;margin-bottom: 30px">
    <form class="form-horizontal" id="form-save" method="post" action="/demo/save" autocomplete="off">
        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                关联分销商：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 5px">
                <span>{{$row['agent_name']}}</span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                业务单号：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 5px">
                <span>{{$row['cus_balance_business_no']}}</span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                支付方式：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 5px">
                <span>{{$row['pay_name']}}</span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                关联支付流水号：</label>
            <div class="col-xs-12 col-sm-10 account-recharge-five" style="padding-top: 5px">
                <span>@if(empty($row['cus_balance_trade_no']))-@else{{$row['cus_balance_trade_no']}}@endif</span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                变动金额：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 5px">
                <span>￥ {{$row['cus_balance_change']}}</span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                余额：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 5px">
                <span>￥ {{$row['cus_balance']}}</span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                交易类型：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 5px">
                <span>{{$CommonPresenter->fundTypeExchange($row['cus_balance_type_detail'])}}</span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                资金类型：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 5px">
                <span>{{$CommonPresenter->fundChangeExchange($row['cus_balance_type'])}}</span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                操作人：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 5px">
                <span>{{$row['operater_name']}}</span>
            </div>
        </div>


        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                发生时间：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 5px">
                <span>{{$CommonPresenter->exchangeTime($row['created_at'])}}</span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                描述：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 5px">
                <span>{{$row['remark']}}</span>
            </div>
        </div>

    </form>
    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button type="button" class="btn btn-primary btn-3F51B5 btn-sure btn-confirm-close" style="position: static">确定</button>
        </div>
    </div>
</div>

