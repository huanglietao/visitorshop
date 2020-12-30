<!-- form表单视图 -->
@inject('CommonPresenter','App\Presenters\CommonPresenter');
<div style="margin-top:30px;margin-bottom: 30px">
    <form class="form-horizontal" id="form-save" method="post" action="/demo/save" autocomplete="off">
        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                 客户编号：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 7px">
                <span>{{$row['partner_code']}}</span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                客户简称：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 7px">
                <span>{{$row['patner_real_name']}}</span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                客户全称：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 7px">
                <span>{{$row['partner_name']}}</span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                充值订单号：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 7px">
                <span>{{$row['recharge_no']}}</span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                充值方式：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 7px">
                <span>{{$CommonPresenter->getAlipayOrWechat($row['pay_type'])}}</span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                关联支付流水号：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 7px">
                <span>{{$row['trade_no']}}</span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                金额：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 7px">
                <span>{{$row['amount']}}</span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                手续费：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 7px">
                <span>{{$row['handling_fee']}}</span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                充值备注：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 7px">
                <span>{{$row['note']}}</span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                充值时间：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 7px">
                <span>{{$row['createtime']}}</span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                支付时间：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 7px">
                <span>{{$row['finishtime']}}</span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                状态：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 7px">
                <span>@if($row['capital_change_status'] == 0)失败（请电话联系022-26881958）@else成功@endif</span>
            </div>
        </div>

    </form>
</div>