<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>供货商管理平台 - 用户登录</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="renderer" content="webkit">
    <link rel="stylesheet" href="{{ URL::asset('css/adminlte/adminlte.min.css') }}">
    <link rel="Shortcut Icon" href="/images/erp/erp_davicon.ico" />
    <link rel="stylesheet" href="{{URL::asset('css/erp/login/base.css')}}">
    <meta name="_token" content="{{ csrf_token() }}"/>
</head>
<body class="inside-header inside-aside" style="background: #f5f5f5">
<!--头部html-->
<link rel="stylesheet" href="{{URL::asset('css/erp/login/login.css')}}">
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header " style="background-color: #fff">
            <a class="navbar-brand"  style="padding:0"><img src="../images/erp/crlogo.png" ></a>
        </div>

    </div>
</nav>

<div id="main" role="main"  style="padding: 0">
    <div class="banner-test"></div>
    <div class="tab-content tab-addtabs">
        <div id="content">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 0">

                    <div class="content">
                        <section class="login-section" >
                            <div class="login-form" >
                                <div class="carousel_container" style="">
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
                                            <h2 class="title" style="color: #ea4335;font-weight: 1000">供货商管理平台</h2>
                                            <p style="text-align: center;font-size: 16px">Supply Chain Management</p>
                                            <div class="m-form-group">
                                                <div class="m-input-group">
                                                    <img src="../images/user.jpg" class="img_icon"/>
                                                    <input type="text" name="username" class="m-input m-input-large account-input"    placeholder="登录账号" >
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
<footer class="footer" style="clear:both">

    <p class="address">
        ©2019 天津长荣云印刷科技有限公司版权所有 . 备案号：津ICP备08101169号-1  联系方式: 022-26881958  地址：天津市北辰高端装备产业园永兴道102号
    </p>

</footer>


</body>
</html>
<style>
    .footer{position: fixed;background-color: #fff !important;bottom: 0 }
    .navbar-inverse, .address{background-color: #fff !important;}
    .geetest_holder{width: 310px !important;}

    #embed-captcha {
        width: 310px;
        margin: 10px 0 30px;

    }
    .show {
        display: block;
        text-align: center;
    }
    .hide {
        display: none;
        text-align: center;
    }
    #notice {
        color: red;
    }
    @media (max-width: 450px) {
        .footer{
            position: relative;
        }
        footer.footer .address {
            line-height: normal;
        }
    }

</style>
<script src="{{ URL::asset('assets/jquery/jquery.min.js')}}"></script>
<script src="{{ URL::asset('js/gt.js')}}"></script>
<script>

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
</script>

