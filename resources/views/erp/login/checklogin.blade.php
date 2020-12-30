
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>长荣云印刷OMS系统 - 用户登录</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="renderer" content="webkit">

    <link rel="Shortcut Icon" href="/images/erp/erp_davicon.ico" />
    <meta name="_token" content="{{ csrf_token() }}"/>
    <link rel="stylesheet" href="{{ URL::asset('css/adminlte/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{URL::asset('css/erp/login/base.css')}}">
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

<div id="main" role="main"  style="padding: 0;">
    <div class="banner-test"></div>
    <div class="tab-content tab-addtabs">
        <div id="content">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                    <div class="content">
                        <section class="login-section" >
                            <div class="login-form" >


                                    <div class="middle-valibox">
                                        <div class="middle-valibox-form">
                                            <p id="phone-note">动态验证码已发送到手机号：{{$userinfo['partner_mobile']}}</p>
                                            <p id="code-note">动态验证码5分钟内有效，如无收到验证码请致电 020-26881958</p>
                                            <div class="m-form-group">
                                                <div class="m-input-group m-password-group ">
                                                    <input type="hidden" value="{{$count}}" class="sms_time">
                                                    <input type="hidden" value="{{$userinfo['partner_mobile']}}" class="user_mobile">
                                                    <img src="../images/erp/micode.png" class="img_icon"/>
                                                    <input type="text"  id="pd-form-floatcode" name="floatcode"  class="m-input m-input-large password-input" autocomplete="off"   placeholder="请输入动态验证码">

                                                    <span class="send_time" style="display: @if ($is_send != 1)inline-block;@else none;@endif"><span class="waiting_time">{{$count}}</span><span>s</span></span>
                                                    <img src="../images/erp/refresh.png" style="display: @if ($is_send != 1)none;@else inline-block;@endif" class="img_refresh" alt="">
                                                </div>
                                            </div>
                                            <p class="notice_err"></p>
                                            <div class="m-form-group m-form-group-small">
                                                <button class="m-btn m-btn-primary m-btn-large   m-btn-submit ready" type="button" style="background: #ea4335;width: 160px">登&nbsp;&nbsp;录</button>
                                            </div>
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


    @media (max-width: 450px) {

        footer.footer .address {
            line-height: normal;
        }
    }
</style>

<script src="{{ URL::asset('assets/jquery/jquery.min.js')}}"></script>
<script src="{{ URL::asset('js/erp/gt.js')}}"></script>
<script src="{{ URL::asset('assets/layer/src/layer.js')}}"></script>
<script>
    var InterValObj1; //timer变量，控制时间
    var curCount1;//当前剩余秒数

    $(document).ready(function () {
        var count = $(".sms_time").val();
        if (count<=60)
        {
            sendMessage1(count)
        }
    })


    function sendMessage1(count) {
        curCount1 = count;
        //设置button效果，开始计时
        $(".waiting_time").html(curCount1);
        InterValObj1 = window.setInterval(SetRemainTime1, 1000); //启动计时器，1秒执行一次
        //向后台发送处理数据

    }
    function SetRemainTime1() {
        if (curCount1 == 0) {
            window.clearInterval(InterValObj1);//停止计时器
            $(".send_time").css("display","none");
            $(".img_refresh").css("display","inline-block");
        }
        else {
            curCount1--;
            $(".waiting_time").html(curCount1);
        }
    }

    //发送验证码
    $(".img_refresh").click(function () {
        $(".send_time").css("display","inline-block");
        $(".img_refresh").css("display","none");

        layer.msg("验证码已发送，请注意查收");
        sendMessage1(60);
        //获取用户手机号
        var mob = $(".user_mobile").val();


        $.ajax({
            url:'/login/sendsms', // 加随机数防止缓存
            type: "POST",
            dataType : 'JSON',
            data:{mob:mob},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function (data) {
                console.log(data);

            }
        });

    })

    $(".ready").click(function () {
        var code = $("input[name = 'floatcode']").val();
        if (code=="")
        {
            $(".notice_err").html("请填写验证码");
            return;
        }
        $.ajax({
            url:'/login/validatelogin',
            type: "POST",
            dataType : 'JSON',
            data:{code:code},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function (data) {
                if (data.status == 1){
                    //验证通过，页面跳转
                    parent.window.location.href="/";
                } else{
                    //验证码错误，提示错误信息
                    $(".notice_err").html(data.msg);

                }
            }
        });
    });
</script>
