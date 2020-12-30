<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>微信充值</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit" />
    <link rel="stylesheet" href="{{ URL::asset('css/adminlte/adminlte.min.css') }}" />
    <link rel="Shortcut Icon" href="/images/erp/erp_davicon.ico" />
    <link rel="stylesheet" href="{{URL::asset('css/erp/login/base.css')}}" />
    <meta name="_token" content="{{ csrf_token() }}" />
</head>
<body class="inside-header inside-aside" style="background: #f5f5f5">
<!--头部html-->
<link rel="stylesheet" href="{{URL::asset('css/erp/login/login.css')}}" />
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header " style="background-color: #fff">
            <a class="navbar-brand" style="padding:0"><img src="{{URl::asset('/images/erp/crlogo.png')}}" /></a>
        </div>
    </div>
</nav>
<div id="main" role="main" style="padding: 0">
    <div class="banner-test"></div>
    <div class="tab-content tab-addtabs">
        <div id="content">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 0">
                    <div class="content">
                        <section class="login-section" style="background-color: white">
                            <div class="login-form" style="padding-top: 50px;">

                                <div class="tab-content show" id="tab1" style="text-align: center">
                                    <table>
                                        <tbody>
                                        <tr>
                                            <td width="30%"> </td>
                                            <td style="text-align:center;">
                                                <table>
                                                    <tbody>
                                                    <tr>
                                                        <td style="text-align:right;font-size:22px;"></td>
                                                        <td>
                                                            <div style="margin-bottom: -5px;">
                                                                <p style="color:red;font-size:14px;text-align: center;">充值金额：{{$amount}}元</p>
                                                                <p style="font-size:14px;text-align: center;">二维码15分钟内有效， <a href="javascript:location.reload()" style="text-decoration: underline; ">刷新</a> 重新获取</p>
                                                            </div> </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td> <img alt="微信扫码支付" src="http://www.meiin.com/index.php?controller=tbdiy&amp;action=get_qrcode&amp;url={{$qr_code}}" style="width:243px;height:243px;" />
                                                            {{--<img src="/assets/img/qrcode (2).png" style="width:243px;height:243px;"> </td>--}}
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td>
                                                            <img alt="微信扫码" src="{{URL::asset('/images/wxpay_code_foot4.jpg')}}" />
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table> </td>
                                            <td><img alt="微信扫码" src="{{URL::asset('/images/wxpay_code_ad.jpg')}}" /></td>
                                            <td width="20%"> </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <div class="met_clear"></div>
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
    <p class="address"> &copy;2019 天津长荣云印刷科技有限公司版权所有 . 备案号：津ICP备08101169号-1 联系方式: 022-26881958 地址：天津市北辰高端装备产业园永兴道102号 </p>
</footer>
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
    @media screen and (max-width: 1600px) {
        .login-form {
            margin-top: 0px;
        }
    }
    .login-form{
        display: table;
        width: 1200px;
        height: 466px;
        margin-left: auto;
        margin-right: auto;
        margin-top: 4px;
        padding-top: 0px;
    }
    @media screen and (max-width: 1024px){
        .login-form {
            height: 453px;
        }
    }
</style>
<script src="{{ URL::asset('assets/jquery/jquery.min.js')}}"></script>
<script>
    function check_recharge(){
        $.ajax({
            url:'/works/ajax_check_recharge',
            type: "POST",
            dataType : 'JSON',
            data:{order_no:'{{$order_no}}'},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function (data) {
                if(data.status == 200){
                    $(window).attr('location','/works/alipayreturn');
                }
            }
        });
    }
    // check_recharge();
    setInterval('check_recharge()',5000); //指定5秒刷新一次
</script>
</body>
</html>