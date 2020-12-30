<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/user/score/save" onsubmit="return false;" autocomplete="off">
    <input type=hidden value="{{$row['score_rule_id']}}" name="score_rule_id" id="score_rule_id">
    <input type=hidden value="{{$row['mch_id']}}" name="mch_id" id="mch_id">

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 名称：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="score_rule_name" class="form-control col-sm-5" name="score_rule_name" type="text" value="{{$row['score_rule_name']}}" placeholder="" data-rule="规则名称:required">
                    <span class="col-sm-7 color-6A6969"> 为该规则填写一个名称。</span>
                </div>
                <span class="msg-box" style="position:static;" for="score_rule_name"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 途径：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div class="col-sm-5">
                        @component('component/radio',['radio'=>$scoreRule,'name'=>'score_rule_way','default_key'=>$row['score_rule_way']??'1'])
                        @endcomponent
                    </div>
                    <span class="col-sm-7 color-6A6969"> 当会员通过此途径时可获得积分。</span>
                </div>
                <span class="msg-box" style="position:static;" for="score_rule_way"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 积分数：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="score_rule_score" class="form-control col-sm-5" name="score_rule_score" type="text" value="{{$row['score_rule_score']}}" placeholder="" data-rule="积分数;通过途径可获得的积分数:required">
                    <span class="col-sm-7 color-6A6969"> 会员登录或者签到一次获得的积分。每消费一元可获得的积分</span>
                </div>
                <span class="msg-box" style="position:static;" for="score_rule_score"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 状态：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div class="col-sm-5">
                        @component('component/radio',['radio'=>['1'=>'启用','0'=>'禁用'],'name'=>'score_rule_status','default_key'=>$row['score_rule_status']??'1'])
                        @endcomponent
                    </div>
                    <span class="col-sm-7 color-6A6969"> 启用：开启，禁用：不开启。</span>
                </div>
                <span class="msg-box" style="position:static;" for="score_rule_status"></span>
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

