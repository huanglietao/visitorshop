@extends('layout.agent_official',['title'=>'登录'])

@section("content")
    <div id="main" role="main"  style="padding: 0">
        <div class="banner-test"></div>
        <div class="tab-content tab-addtabs">
            <div id="content">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 0">

                        <div class="content">
                            <section class="login-section" >
                                <div class="login-form" >
                                    <div class="carousel_container" style="display: none">
                                        <div class="m-carousel" style="height: auto">
                                            <div class="sild" style="background: none">
                                                <!--inlcude-->
                                                <!--Regular list-->
                                                <div class="item"><img src="../images/erp/backg.png"></div>
                                            </div>
                                            <!--Regular if29-->
                                        </div>
                                    </div>
                                    <div class="m-login-container" style="">
                                        <input type="hidden" value="11" name="test-num" class="test-num">
                                        <div class="m-login-form" >
                                            <form id="login-form" class="form-horizontal" method="POST" action="" autocomplete="off" onsubmit="return false;">
                                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                                <h2 class="title" style="color: #ea4335;font-weight: 1000">分销管理平台</h2>
                                                <p style="text-align: center;font-size: 16px">DISTRIBUTION MANAGEMENT SYSTEM</p>
                                                <div class="m-form-group">
                                                    <div class="m-input-group">
                                                        <img src="../images/user.jpg" class="img_icon"/>
                                                        <input type="text" name="username" class="m-input m-input-large account-input"    placeholder="手机号码" >
                                                    </div>
                                                </div>
                                                <div class="m-form-group">
                                                    <div class="m-input-group m-password-group ">
                                                        <img src="../images/suo.jpg" class="img_icon"/>
                                                        <input type="password"  id="pd-form-password" name="password" class="m-input m-input-large password-input"    placeholder="登录密码">
                                                    </div>

                                                </div>

                                                <div id="embed-captcha">

                                                </div>
                                                <input type="hidden" name="captcha" class="form-control" data-msg-required="请完成验证码验证" data-target="#errtips">
                                                <p id="wait" class="show">正在加载验证码......</p>
                                                <p id="notice" class="hide">请先完成验证</p>
                                                <p id="esm" style="text-align: center;color: red"></p>
                                                <div class="m-form-group" style="text-align: right;">
                                                    <a href="{{URL::asset('/index/forget/')}}" target="_blank" style="color: #4477d0;font-size: 12px;text-decoration: none">忘记密码?</a>
                                                </div>
                                                <div class="m-form-group m-form-group-small">
                                                    <!--inlcude-->
                                                    <button class="m-btn m-btn-primary m-btn-large   m-btn-submit ready" type="text" style="background: #ea4335"><!--inlcude-->登&nbsp;&nbsp;录</button>

                                                    <!--Regular if24-->
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
<!--  加载js -->
@section("js-file")
    <script src="{{ URL::asset('js/gt.js')}}"></script>
@endsection
@section("pages-js")
    var obj;
    //加载极验证码
    var handlerEmbed = function (captchaObj) {
    obj = captchaObj;
    // 将验证码加到id为captcha的元素里，同时会有三个input的值：geetest_challenge, geetest_validate, geetest_seccode
    captchaObj.appendTo("#embed-captcha");
    captchaObj.onReady(function () {
        $("#wait")[0].className = "hide";
    });
    };

    //请求验证码数据
    var num = (new Date()).getTime();
    console.log(num);
    var url = "/start/pc/"+num; console.log(url);
    $.ajax({
        url:url, // 加随机数防止缓存
        type: "get",
        dataType : 'JSON',
        headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        success: function (data) {
            console.log(22);
            console.log(data.gt);
            // 使用initGeetest接口
            // 参数1：配置参数
            // 参数2：回调，回调的第一个参数验证码对象，之后可以使用它做appendTo之类的事件
            initGeetest({
                gt: data.gt,
                challenge: data.challenge,
                new_captcha: data.new_captcha,
                product: "embed", // 产品形式，包括：float，embed，popup。注意只对PC版验证码有效
                offline: !data.success // 表示用户后台检测极验服务器是否宕机，一般不需要关注
                }, handlerEmbed);
            }
    });

    // 提交登录并请求接口返回处理
    $(".m-btn-submit").click(function (e) {
        var uname = $("input[name='username']").val();
        var pwt = $("input[name='password']").val();

        if(uname == ''){
            $('#esm').html('请填写登录账号');
            setTimeout(function () {
                $('#esm').html('') ;
            }, 2000);
            return false;
        }
        if(pwt == ''){
            $('#esm').html('请填写登录密码');
            setTimeout(function () {
                $('#esm').html('') ;
            }, 2000);
            return false;
        }

        var validate = obj.getValidate();
        if (!validate) {
            $("#notice")[0].className = "show";
            setTimeout(function () {
                $("#notice")[0].className = "hide";
            }, 2000);
            e.preventDefault();
            return false;
        }
        //判断极验证码正确性
        var g_challenge =$("input[name='geetest_challenge']").val();
        var g_validate =$("input[name='geetest_validate']").val();
        var g_seccode =$("input[name='geetest_seccode']").val();
        if(g_challenge && g_validate && g_seccode){
            $("input[name='captcha']").val('ok');
        }
        var data = {
            // _token    : page,
            username :uname,
            password :pwt,
            captcha: 'ok',
            geetest_challenge :g_challenge,
            geetest_validate:g_validate,
            geetest_seccode:g_seccode,
        };
        //发送登录请求
        $.ajax({
            url:"/login/savelogin",
            type:"POST",
            dataType:"JSON",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            data:data,
            success:function (data) {
                console.log(data.code);
                if(data.code==1){
                //验证通过，页面跳转
                parent.window.location.href="/";

                }else {  //处理登录信息错误时并从新加载极验证码
                    $('#esm').html(data.message);
                    $("#embed-captcha").empty();
                    setTimeout(function () {
                        $('#esm').html('') ;
                    }, 2000);

                    $.ajax({
                        url:url, // 加随机数防止缓存
                        type: "get",
                        dataType : 'JSON',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        },
                        success: function (data) {
                            console.log(data.gt);

                            initGeetest({
                                gt: data.gt,
                                challenge: data.challenge,
                                new_captcha: data.new_captcha,
                                product: "embed", // 产品形式，包括：float，embed，popup。注意只对PC版验证码有效
                                offline: !data.success // 表示用户后台检测极验服务器是否宕机，一般不需要关注
                            }, handlerEmbed);
                        }
                    });
                //return false;
                }
            }
        })
    });

    $("body").find(".main-im").css("display","none");
@endsection
<style>
    .content{min-height:480px !important;}
    .login-form{margin-top:115px !important;}
    .login-form{
        display: table;
        width: 1200px;
        height: 600px;
        margin-left: auto;
        margin-right: auto;
        margin-top: 40px;
        padding-top: 0px;

    }

    @media (max-width: 1200px) {
        .login-form{
            width: 100%;
        }
    }

    .carousel_container{
        margin-top: 35px;
        margin-left:8%;
    }
    .carousel_container{
        margin-top: 35px;
    }

    .carousel_container {
        float: left;
        width: 35%;
        height: 420px;
        margin-top: 130px;
        margin-left: 5%;
    }

    .m-carousel {
        box-sizing: border-box;
        width: 100%;
        height: 100%;
        padding-bottom: 30px;
        overflow: hidden;
    }
    .m-carousel .sild {
        position: relative;
        width: 100%;
        height: 100%;
        background: white;
    }

    .item img{
        width: 100%;
        height: 100%;
    }
    .m-login-container {
        width: 100% !important;
        box-sizing: border-box;
        float: right;
        height: 500px;
        padding-top: 30px;
        margin-right: 5%;
        background: #fff;
        margin-top:95px;
        border-radius:4%;
        margin-right:0px !important;
    }

    .m-login-form{
        width: 310px;
        margin: 0 auto;
        padding: 0;
        line-height: 1;
    }

    @media screen and (max-width: 1600px) { /*当屏幕尺寸小于600px时，应用下面的CSS样式*/
        .m-login-container {
            padding-top: 0;margin-top: 0;height: 485px;
        }
        .carousel_container{
            margin-top: 35px;
        }
      /*  .navbar-brand img{width: 55%}*/
        .navbar{min-height:85px}
        .login-form{margin-top:65px}
    }
    @media screen and (max-width: 1280px) { /*当屏幕尺寸小于600px时，应用下面的CSS样式*/
        .m-login-container {
            padding-top: 0;margin-top: 0;height: 480px;
        }
        .carousel_container{
            margin-top: 35px;
            margin-left:8%;
        }
        /*.navbar-brand img{width: 55%}*/
        .navbar{min-height:85px}
        .login-form{margin-top:115px}
    }
    @media screen and (max-width: 1024px) { /*当屏幕尺寸小于600px时，应用下面的CSS样式*/
        .m-login-container {
            padding-top: 0;
            margin-top: 0;
            height: 450px;
            width:400px;
            margin-right:7%
        }
        .carousel_container{
            margin-top: 60px;
            margin-left:130px;
        }
        /*.navbar-brand img{width: 45%}*/
        .item img{width: 90%}
        .navbar{min-height:65px}
        .login-form{margin-top:15px;height: 425px;}
        .footer .address{line-height: 50px;font-size: 10px !important;}
    }

    input {
        display: block;
        width: 290px;
        line-height: 40px;
        margin: 10px 0 !important;
        padding: 0 10px;
        outline: none;
        border:1px solid #c8cccf;
        border-radius: 4px;
        color:#6a6f77;
    }

    .title{
        margin: 0 0 20px 0;
        padding: 10px 0 0;
        text-align: center;
        color: #222;
        font-size: 20px;
    }
    h2 {
        line-height: 26px;
    }
    .m-input{
        color: #333;
        padding: 0 2em 0 1em;
        border: 1px solid #d9d9d9;
        border-radius: 2px;
        box-sizing: border-box;
        transition: border 200ms;
        -webkit-appearance: none;
    }
    .m-input-large {
        width: 310px;
        height: 40px;
        font-size: 14px;
        line-height: 20px;
        padding-top: 15px;
        padding-bottom: 15px;
    }
    .m-login-form p{
        font-size: 14px;
    }
    .m-input-group{
        position: relative;
        display: inline-block;
        vertical-align: middle;
    }
    .img_icon{
        width: 8%;
        position: absolute;
        top: 17px;
        left: 5px;
    }
    .account-input{
        padding-right: 32px;
        padding-left: 40px;
    }
    .password-input {
        padding-right: 66px;
        padding-left: 40px;
    }
    #embed-captcha {
        width: 310px;
        margin: 10px 0 30px;
    }
    .m-form-group-small {
        margin: 14px 0;
        text-align: center;
    }
    .m-btn-submit {
        display: block;
        margin: 0 auto;
    }
    .m-btn-primary {
        color: #fff;
        background: #387ee8;
    }
    .m-btn-large {
        width: 200px;
        height: 50px;
        font-size: 16px;
        line-height: 50px;
    }
    .m-btn {
        display: inline-block;
        border: 0;
        border-radius: 2px;
        box-sizing: border-box;
        color: #fff;
        background: #5f99f1;
    }

</style>