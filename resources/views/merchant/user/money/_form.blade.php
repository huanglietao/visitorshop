<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/user/money/save" onsubmit="return false;" autocomplete="off">
    <input type=hidden value="{{$row['user_money_id']}}" name="id" id="id">
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 自增id：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="user_money_id" class="form-control col-sm-5" name="user_money_id" type="text" value="{{$row['user_money_id']}}" placeholder="" data-rule="自增id:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="user_money_id"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 会员id,与saas_user表关联：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="user_id" class="form-control col-sm-5" name="user_id" type="text" value="{{$row['user_id']}}" placeholder="" data-rule="会员id,与saas_user表关联:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="user_id"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 商家id：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="mch_id" class="form-control col-sm-5" name="mch_id" type="text" value="{{$row['mch_id']}}" placeholder="" data-rule="商家id:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="mch_id"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 交易流水号：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="recharge_no" class="form-control col-sm-5" name="recharge_no" type="text" value="{{$row['recharge_no']}}" placeholder="" data-rule="交易流水号:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="recharge_no"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 第三方交易流水号：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="trade_no" class="form-control col-sm-5" name="trade_no" type="text" value="{{$row['trade_no']}}" placeholder="" data-rule="第三方交易流水号:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="trade_no"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 交易类型;1:消费,2:充值：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="money_type" class="form-control col-sm-5" name="money_type" type="text" value="{{$row['money_type']}}" placeholder="" data-rule="交易类型;1:消费,2:充值:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="money_type"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 交易金额：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="amount" class="form-control col-sm-5" name="amount" type="text" value="{{$row['amount']}}" placeholder="" data-rule="交易金额:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="amount"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 账户余额：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="balance" class="form-control col-sm-5" name="balance" type="text" value="{{$row['balance']}}" placeholder="" data-rule="账户余额:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="balance"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 操作人：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="operator" class="form-control col-sm-5" name="operator" type="text" value="{{$row['operator']}}" placeholder="" data-rule="操作人:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="operator"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 备注：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="note" class="form-control col-sm-5" name="note" type="text" value="{{$row['note']}}" placeholder="" data-rule="备注:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="note"></span>
            </div>
        </div>

    </form>
    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button type="submit" class="btn btn-primary btn-3F51B5 btn-sure btn-submit">确定</button>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <button type="reset" class="btn btn-write btn-reset">重置</button>
        </div>
    </div>
</div>

