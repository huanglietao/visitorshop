<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/marketing/couponNumber/save" onsubmit="return false;" autocomplete="off">
    <input type=hidden value="{{$row['cou_num_id']}}" name="id" id="id">
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 自增id：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="cou_num_id" class="form-control col-sm-5" name="cou_num_id" type="text" value="{{$row['cou_num_id']}}" placeholder="" data-rule="自增id:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="cou_num_id"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 优惠券id：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="cou_id" class="form-control col-sm-5" name="cou_id" type="text" value="{{$row['cou_id']}}" placeholder="" data-rule="优惠券id:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="cou_id"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 优惠码：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="cou_num_code" class="form-control col-sm-5" name="cou_num_code" type="text" value="{{$row['cou_num_code']}}" placeholder="" data-rule="优惠码:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="cou_num_code"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 面值：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="cou_num_money" class="form-control col-sm-5" name="cou_num_money" type="text" value="{{$row['cou_num_money']}}" placeholder="" data-rule="面值:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="cou_num_money"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 是否使用;1:未使用,2:已使用：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="cou_num_is_used" class="form-control col-sm-5" name="cou_num_is_used" type="text" value="{{$row['cou_num_is_used']}}" placeholder="" data-rule="是否使用;1:未使用,2:已使用:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="cou_num_is_used"></span>
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

