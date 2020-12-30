<div id="pwdInfo" style="margin-top:30px;">
    <form class="form-horizontal" id="form-pwd" method="post" action="/admin/save" autocomplete="off">
        @csrf
        <div class="form-group row form-item">
            <label style=" font-weight: normal;font-size: 14px" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                系统登录密码
            </label>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">旧登录密码：
            </label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input id="old_pwd" onkeyup="value=value.replace(/[\u4e00-\u9fa5]/ig,'')" class="form-control col-sm-5" name="old_pwd" type="text" value="" placeholder="">
                    <span class="col-sm-7" style="color: rgba(0, 0, 0, 0.45);margin-top: 3px">旧密码必须输入。</span>
                </div>
                <span class="msg-box" style="position:static;" for="old_pwd"></span>
            </div>

        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">新登录密码：
            </label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input id="new_pwd" onkeyup="value=value.replace(/[\u4e00-\u9fa5]/ig,'')" class="form-control col-sm-5" name="new_pwd" type="text" value="" placeholder="">
                    <span class="col-sm-7" style="color: rgba(0, 0, 0, 0.45);margin-top: 3px">0~9A~Z和特殊符号组合而成，区分大小写，不支持任意连续的3个字符。长度6~32位</span>
                </div>
                <span class="msg-box" style="position:static;" for="new_pwd"></span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">重复密码：
            </label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input id="rep_pwd" onkeyup="value=value.replace(/[\u4e00-\u9fa5]/ig,'')" class="form-control col-sm-5" type="text" value="" placeholder="">
                    <span class="col-sm-7" style="color: rgba(0, 0, 0, 0.45);margin-top: 3px">重复输入上面的密码</span>
                </div>
                <span class="msg-box" style="position:static;" for="rep_pwd"></span>
            </div>
        </div>

        @if($isMain==1)
            <input type="hidden" id="first_open" value="{{$info['payword']}}"/>
            <input type="hidden" id="open_status" value="{{$info['is_open_pay']}}"/>
            <div class="form-group row form-item">
                <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                    <span class="control-span">*</span> 支付密码：</label>
                <div class="col-xs-12 col-sm-10">
                    <div class="row">
                        <div class="check col-sm-5" style="margin-top: 7px;padding-left: 0">
                            @if($info['is_open_pay']==1)
                                @component('component/radio',['radio'=>['0'=>'关闭','2'=>'开启','1'=>'已开启'],'name'=>'payword','default_key'=>$info['is_open_pay']])
                                @endcomponent
                            @else
                                @component('component/radio',['radio'=>['0'=>'关闭','2'=>'开启'],'name'=>'payword','default_key'=>0])
                                @endcomponent
                            @endif
                            {{--@component('component/checkbox',['checkbox'=>['1'=>'是否启用支付密码'],'name'=>['payword'],'checked'=>$info['is_open_pay']])--}}
                            {{--@endcomponent--}}
                        </div>
                        <span class="col-sm-7" style="color: rgba(0, 0, 0, 0.45);margin-top: 3px"> 启用支付密码后，系统所有使用余额方式支付的环节均需要支付密码验证。</span>
                    </div>
                </div>
            </div>

            <div>
                <div class="form-group row form-item pay_isopen" id="old_payword" style="display: none">
                    <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                        <span class="control-span">*</span>旧支付密码：
                    </label>
                    <div class="col-xs-12 col-sm-10">
                        <div class="row">
                            <input id="old_pay_pwd" onkeyup="value=value.replace(/[\u4e00-\u9fa5]/ig,'')" class="form-control col-sm-5" name="old_pay_pwd" type="text" value="" placeholder="">
                            <span class="col-sm-7" style="color: rgba(0, 0, 0, 0.45);margin-top: 3px">旧密码必须输入。首次开启无需输入</span>
                        </div>
                        <span class="msg-box" style="position:static;" for="old_pay_pwd"></span>
                    </div>
                </div>

                <div class="form-group row form-item pay_isopen" style="display: none">
                    <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                        <span class="control-span">*</span>新支付密码：
                    </label>
                    <div class="col-xs-12 col-sm-10">
                        <div class="row">
                            <input id="new_pay_pwd" onkeyup="value=value.replace(/[\u4e00-\u9fa5]/ig,'')" class="form-control col-sm-5" name="new_pay_pwd" type="text" value="" placeholder="">
                            <span class="col-sm-7" style="color: rgba(0, 0, 0, 0.45);margin-top: 3px">0~9A~Z和特殊符号组合而成，区分大小写，不支持任意连续的3个字符。长度6~32位</span>
                        </div>
                        <span class="msg-box" style="position:static;" for="new_pay_pwd"></span>
                    </div>
                </div>

                <div class="form-group row form-item pay_isopen" style="display: none">
                    <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                        <span class="control-span">*</span>重复密码：
                    </label>
                    <div class="col-xs-12 col-sm-10">
                        <div class="row">
                            <input id="new_rep_pwd" onkeyup="value=value.replace(/[\u4e00-\u9fa5]/ig,'')" class="form-control col-sm-5" type="text" value="" placeholder="">
                            <span class="col-sm-7" style="color: rgba(0, 0, 0, 0.45);margin-top: 3px">重复输入上面的密码</span>
                        </div>
                        <span class="msg-box" style="position:static;" for="new_rep_pwd"></span>
                    </div>
                </div>
            </div>
        @endif

        <div class="form-group row form-item" style="margin-top: 50px">
            <label class="control-label col-xs-12 col-sm-2"></label>
            <div class="col-xs-12 col-sm-8">
                <button type="submit" id="btn-pwd" class="btn btn-primary btn-3F51B5 btn-sure" onclick="return false;">确定</button>
            </div>
        </div>

    </form>
</div>

<div id="checkeds" style="display: none;">
    <div style="display: flex;flex-direction: column;justify-content: center;padding: 20px 80px">
        <input type="hidden" value="false" id="yanzheng"/>
        <input type="hidden" value="" id="sms_code"/>
        <input type="hidden" value="" id="sms_time"/>
        <div style="text-align: left;font-size: 14px;">
            <span style="">手机号：</span>
            <div style="display: inline-block;">
                <span class="col-sm-7 color-6A6969 label-content">{{$info['mobile']}}</span>
                <input type="hidden" id="mobile" value="{{$info['mobile']}}"/>
            </div>
        </div>
        <div style="text-align: left;margin: 20px 0;">
            <input type="number" id="chCode" value="" style="height: 30px" placeholder="请输入验证码"/>
            <div id="catch_code" style="display: inline-block;height: 30px;width: 80px;background-color: #ea4335;color: white;text-align: center;line-height: 30px;border-radius: 2px;cursor: pointer">
                获取验证码
            </div>
        </div>

        <div style="text-align: center">
            <button type="button" id="check_sure" class="btn btn-primary btn-3F51B5 btn-sure">确定</button>
            <button type="button" id="check_cancle" class="btn btn-primary btn-3F51B5 btn-sure">取消</button>
        </div>

    </div>
</div>