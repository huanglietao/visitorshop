<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/agent/strategy/rechargerule/save" onsubmit="return false;" autocomplete="off">
    <input type=hidden value="{{$row['rec_rule_id']}}" name="id" id="id">
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 优惠名称：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="rec_rule_name" class="form-control col-sm-5" name="rec_rule_name" type="text" value="{{$row['rec_rule_name']}}" placeholder="" data-rule="优惠名称:required">
                    <span class="col-sm-7 color-6A6969"> 请填写优惠名称。</span>
                </div>
                <span class="msg-box" style="position:static;" for="rec_rule_name"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 充值金额：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="recharge_fee" class="form-control col-sm-5" name="recharge_fee" type="number" value="{{$row['recharge_fee']}}" placeholder="" data-rule="充值金额:required">
                    <span class="col-sm-7 color-6A6969"> 请输入充值金额。</span>
                </div>
                <span class="msg-box" style="position:static;" for="recharge_fee"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 奖励金额：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="present_fee" class="form-control col-sm-5" name="present_fee" type="number" value="{{$row['present_fee']}}" placeholder="" data-rule="优惠金额:required">
                    <span class="col-sm-7 color-6A6969"> 请输入奖励金额。</span>
                </div>
                <span class="msg-box" style="position:static;" for="present_fee"></span>
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

