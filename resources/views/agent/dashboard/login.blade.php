<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>登录 - DMS管理系统</title>

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ URL::asset('css/adminlte/login.css') }}">
    <!-- Google Font: Source Sans Pro -->
</head>

<body>
<div class="form-content">
    <div class="back-style">
        <div class="information">
            <div class="title_text">
                <span class="title">分销管理平台</span>
            </div>
            <div class="title_translate">
                <span class="translate">DISTRIBUTION MANAGEMENT SYSTEM</span>
            </div>
            {{--<form method="post" action="{{url('/regist')}}">--}}
            {{--@csrf--}}
            <div style="margin-top: 23px">
                <div class="login_information">
                    <img src="../images/user.jpg" class="icon"/>
                    <input type="text" class="message" placeholder="请输入账号"/>
                </div>
                <div class="login_information">
                    <img src="../images/suo.jpg" class="icon"/>
                    <input type="text" class="message" placeholder="请输入密码"/>
                </div>
                <div class="login_information">
                    <img src="../images/dun.png" class="icon"/>
                    <input type="text" class="message" placeholder="进行验证"/>
                </div>
            </div>
            <div class="check">
                <input type="checkbox" id="保存信息" class="checkbox"><label for="保存信息"></label><span class="save">保存信息</span>
            </div>
            <div class="click">
                <span>忘记密码?</span>
            </div>
            <button  class="submit" type="submit" onclick="location.href=''">登&nbsp;&nbsp;录</button>
            {{--</form>--}}
        </div>
    </div>
    <div class="footer"><i><img src="../images/C.png" class="copyright"/></i><span class="banquan">2009 - 2019 云印刷科技有限公司 版权所有</span></div>
</div>
</body>
</html>
