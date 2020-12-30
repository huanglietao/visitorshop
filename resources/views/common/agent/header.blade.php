<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header " style="">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#header-navbar" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand"  style="padding:0">
                @if(isset($data['agent_name']))
                    <span  style="color: black;line-height: 50px;font-size: 20px;font-weight: 500;">{{$data['agent_name']}}</span>
                @else
                <img src="../images/erp/crlogo.png" style="height: 60px;margin-top: -3px">
                @endif

            </a>
        </div>
        @if(!isset($flag))
            <div class="navbar-collapse collapse" id="header-navbar" aria-expanded="false" style="height: 1px;">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="/">首页</a></li>
                    <li> <a href="/">产品</a></li>
                    <li><a href="/">模版</a></li>
                    <li><a href="/">系统</a></li>
                    <li>
                        <a href="{{URL::asset('/login')}}" style="display: inline-block;padding: 16.5px 2px 16.5px 15px">登录</a>
                        <span class="nav-line">|</span>
                    </li>
                    <li><a href="{{URL::asset('/index/register')}}" class="nav-res" style="display: inline-block;padding: 16.5px 15px 16.5px 5px">注册</a></li>
                    <!--<li><a href="#">活动</a></li>
                    <li><a href="#">关于</a></li>-->
                </ul>
            </div>
        @endif
    </div>
</nav>

<div class="main-im">
    <div id="open_im" class="open-im" style="display: block;">&nbsp;</div>
    <div class="im_main" id="im_main" style="display: none;">
        <div id="close_im" class="close-im"><a href="javascript:void(0);" title="点击关闭">&nbsp;</a></div>
        <div class="im-qq qq-a">
            <div class="weixin"><img class="img-qq" src="{{$deployInfo['deploy_qr_code']}}"></div>
            <span>客服在线咨询</span>
        </div>
        <div class="im-tel">
            <div>工作时间</div>
            <div class="tel-num">09:00-21:00</div>
            <div>售后咨询指南</div>
            <div class="tel-num">
                <a href="/articles/detail?id=1" target="_blank" style="color:#E96D15; ">售后服务条款</a>
            </div>
        </div>
    </div>

</div>

<script>
    // 咨询
    $("body").delegate("#open_im","click",function () {
        $("#im_main").show();
        $(this).hide();
    })
    // 咨询
    $("body").delegate("#close_im","click",function () {
        $("#open_im").show();
        $("#im_main").hide();
    })
</script>
