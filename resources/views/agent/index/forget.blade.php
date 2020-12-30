@extends('layout.agent_official',['title'=>'找回密码'])

@section("content")
        <div class="row" style="width: 800px;height: 400px; background: white;margin: 80px auto 0 auto;padding: 65px 0 80px 0">
            <form class="form-horizontal common-form">

                <div class="forget">
                    <div class="form-group row form-item">
                        <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-4 col-sm-4"></label>
                        <div class="col-xs-4 col-sm-4">
                            <div class="row">
                                <input  id="agent_name" class="form-control col-sm-5" name="agent_name" type="text"  placeholder="分销平台账号" data-rule="分销平台账号:required" style="height: 40px;">
                            </div>
                            <span class="msg-box" style="position:static;" for="agent_name"></span>
                        </div>
                    </div>

                    <div class="form-group row form-item" style="margin-top: 20px;">
                        <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-4 col-sm-4"></label>
                        <div class="col-xs-4 col-sm-4">
                            <div class="row">
                                <div style="text-align: right;">已有账号?<a href="{{URL::asset('/login/')}}" style="color: #4477d0;text-decoration: none;">直接登录</a></div>
                                <div class="first_next" style="text-align: center;background-color: #ea4335;font-size: 16px;color: white;height: 50px;line-height: 50px;cursor: pointer;border-radius: 2px;">下一步</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="reset" style="display: none">
                    <div class="form-group row form-item">
                        <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-4 col-sm-4"></label>
                        <div class="col-xs-5 col-sm-5">
                            <div class="row" style="text-align: left;">
                                <div>手机号码：
                                    <span class="mobile"></span>
                                    <span style="color: grey;">[手机不可用?点此帮助]</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row form-item">
                        <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-4 col-sm-4"></label>
                        <div class="col-xs-5 col-sm-5">
                            <div class="row" style="position: relative">
                                <div class="code">获取验证码</div>
                                <input  id="verification" class="form-control col-sm-5" name="verification" type="text"  placeholder="请输入验证码" data-rule="验证码:required" style="height: 40px;">
                            </div>
                            <span class="msg-box" style="position:static;" for="verification"></span>
                        </div>
                    </div>

                    <div class="form-group row form-item">
                        <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-4 col-sm-4"></label>
                        <div class="col-xs-5 col-sm-5">
                            <div class="row">
                                <input  id="password" class="form-control col-sm-5" name="password" type="password"  placeholder="请输入新密码" data-rule="密码:required" style="height: 40px;">
                            </div>
                            <span class="msg-box" style="position:static;" for="password"></span>
                        </div>
                    </div>

                    <div class="form-group row form-item">
                        <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-4 col-sm-4"></label>
                        <div class="col-xs-5 col-sm-5">
                            <div class="row">
                                <input  id="confirm" class="form-control col-sm-5" name="confirm" type="password"  placeholder="请再次输入新密码" data-rule="密码:required" style="height: 40px;">
                            </div>
                            <span class="msg-box" style="position:static;" for="confirm"></span>
                        </div>
                    </div>

                    <div class="form-group row form-item" style="margin-top: 20px;">
                        <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-4 col-sm-4"></label>
                        <div class="col-xs-5 col-sm-5">
                            <div class="row">
                                <div style="text-align: right;">已有账号?<a href="{{URL::asset('/login/')}}" style="color: #4477d0;text-decoration: none;">直接登录</a></div>
                                <div class="second_next" style="text-align: center;background-color: #ea4335;font-size: 16px;color: white;height: 50px;line-height: 50px;cursor: pointer;border-radius: 2px;">下一步</div>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
@endsection
<style>
    .footer{
        position: fixed !important;
        bottom: 0 !important;
    }
    .code{
        width: 85px;
        height: 30px;
        background-color: #ea4335;
        color: white;
        border-radius: 2px;
        position: absolute;
        right: 5px;
        top: 5px;
        z-index: 1;
        text-align: center;
        line-height: 30px;
        cursor: pointer;
    }
    #main{
        height: 800px !important;
    }
</style>
<!--  加载js -->
@section("js-file")
    <script src="{{ URL::asset('js/agent/index/forget.js')}}"></script>
@endsection
@section("pages-js")

@endsection
