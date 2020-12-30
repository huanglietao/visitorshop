<!-- 资金变动form视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/agent/info/capital_save" onsubmit="return false;" autocomplete="off">
        <input type=hidden value="{{$agent_info['agent_info_id']}}" name="agent_info_id" id="agent_info_id">
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">账户余额：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <span class="col-sm-5" style="padding-top: 0.5%;font-size: 16px">￥ {{$agent_info['agent_balance']}}</span>
                    <input  id="agent_balance" hidden class="form-control col-sm-5" name="agent_balance" type="text" value="{{$agent_info['agent_balance']}}" placeholder="">
                    <span class="col-sm-7 color-6A6969"> 当前账户余额。</span>
                </div>
                <span class="msg-box" style="position:static;" for="agent_balance"></span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">类型：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div style="display: inline-block;padding-top: 5px;padding-left: 0" class="col-sm-5">
                        @component('component/radio',['radio'=>['1'=>'充值','2'=>'扣款'],'name'=>'balance_type','default_key'=>1])
                        @endcomponent
                    </div>
                    <span class="col-sm-7 color-6A6969" style="padding-top: 1%"> 充值到余额，从余额中扣款</span>
                </div>
                <span class="msg-box" style="position:static;" for="balance_type"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 金额：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="new_balance" class="form-control col-sm-5" name="new_balance" type="number" value="" placeholder="" data-rule="金额:required">
                    <span class="col-sm-7 color-6A6969"> 对此次操作的金额。</span>
                </div>
                <span class="msg-box" style="position:static;" for="new_balance"></span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 转账凭证：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div style="padding-left: 0;height: 108px" class="col-sm-5">
                        @component('component/image_upload',['name'=>'images','uploader'=>'uploader1','num'=>1,'value'=>"",'rule'=>"转账凭证:required",'direction'=>1,'browse_btn'=>'form-avatar','content_class'=>'background','img_format'=>'jpg,jpeg,png'])
                        @endcomponent
                    </div>
                    <span class="col-sm-7 color-6A6969"> 转账记录的截图。</span>
                </div>
                <span class="msg-box" style="position:static;" for="images"></span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 描述：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <textarea id="remark" rows="5" class="form-control col-sm-5" name="remark" type="text"  placeholder="" data-rule="描述:required"></textarea>
                    <span class="col-sm-7 color-6A6969"> 对此次操作的描述。</span>
                </div>
                <span class="msg-box" style="position:static;" for="remark"></span>
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

<style>

    .areas-one{
        width: 100%;
        display: flex;
        justify-content: space-between;
    }
    .areas-province,.areas-city,.areas-area{
        height: 30px !important;
        width: 32%;
    }

</style>