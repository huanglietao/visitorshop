<nav class="main-header navbar navbar-expand navbar-light navbar-F1F2F6  border-bottom">
    <!-- Left navbar links -->
    <ul class="navbar-nav telescopic" style="position:relative;top:15px;left: -17px;" >
        <li class="nav-item">
            <a class="nav-link next-btn header-next-btn" data-widget="pushmenu" href="#" data-action="hide"   style="display: none;" >
                <i class="fa fa-angle-left right fa-lg" style="position: relative; left: -10px;width: 12px;" ></i>

            </a>
        </li>
    </ul>

    <!-- SEARCH FORM -->
   <!-- <form class="form-inline ml-3">
        <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </form>-->

    @if ($systemName=='oms')
        <div class="oms-mode-list">
            @php $count = count($paltList);$i=1; @endphp
           @foreach($paltList as $k => $v)
               <li class="oms_mode_tab @if($k == $current_flag) nav_status_current @endif" style="@if($i==1) border-radius:5px 0 0; @elseif($i == $count) border-right:1px solid rgb(220, 223, 230);border-radius:0 5px 0 0; @endif" data-val="{{$k}}">
                        {{$v}}
               </li>
               @php $i++; @endphp
            @endforeach
        </div>

    @endif


    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto head-one">
        <!-- Messages Dropdown Menu -->
        <!--<li class="nav-item dropdown">
            @if (!isset($is_erp) )
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fa fa-home fa-lg"></i>
                {{--<span class="badge badge-warning navbar-badge">3</span>--}}
            </a>
            @endif

        </li>-->

        @if ($systemName=='oms' || $systemName=='dms')
        <li class="nav-item">
            <a class="nav-link"  href="javascript:;" data-url="/#/news" id="news-bell">
                <i class="fa fa-bell fa-lg" ></i>
                @if (isset($artList)&&!empty($artList))
                <img src="/images/news-tip.png" style="position: absolute; left: 18px;top: -2px;" />
                @endif
            </a>

        </li>
        @endif
        @if ($systemName=='dms')
            <li class="nav-item">
                <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="javascript:;"  data-url="/#/orders/cart" id="shop-cart">
                    <i class="fa fa-cart-arrow-down fa-lg" ></i>
                    <span class="badge badge-danger navbar-badge" style="top: 6px;right: 1px">@if(isset($cartNum)){{$cartNum}}@endif</span>
                </a>
            </li>
        @else
            <li class="nav-item">
                <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#">
                    <i class="fa" ></i>
                </a>
            </li>
        @endif

        {{--@if (true )--}}
        <!-- Notifications Dropdown Menu -->
       {{-- <li class="nav-item dropdown" onmouseover="showlist()" onmouseout="hidelist()">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fa fa-bell fa-lg"></i>
                <span class="badge badge-danger navbar-badge" style="top: 6px;right: 1px">18</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right notice" onmouseover="showlist()" onmouseout="hidelist()">
                <span class="dropdown-item dropdown-header">18条消息</span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fa fa-star mr-2"></i> 优惠活动
                    <span class="float-right text-muted text-sm">3 分钟前</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fa fa-star mr-2"></i> 订单已发货
                    <span class="float-right text-muted text-sm">1 小时前</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fa fa-star mr-2"></i> 关注商品降价
                    <span class="float-right text-muted text-sm">2 天前</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">查看所有消息</a>
            </div>
        </li>
        <li class="head-nine">
            <img class="head-ten one" src="/images/icon1.png">
        </li>
        <li class="head-nine">
            <img class="head-ten two" src="/images/icon2.png">
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#">
                <i class="fa fa-cart-arrow-down fa-lg" ></i></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#">
                <i class="fa fa-rocket fa-lg" ></i></a>
        </li>--}}

      {{--  @else
            <li style="margin-top: 1px;margin-left: 4px">
                <img style="width: 100%;height: 100%;" src="/images/bk_head.png">
                </li>
            @endif--}}
    </ul>

    {{--user--}}
    @if (!isset($is_erp) )
    <div class="head-two">
        <img class="head-three" src="@if(empty($userInfo[$systemName.'_adm_avatar']))images/defaultHead.png @else{{$userInfo[$systemName.'_adm_avatar']}}@endif" />
        <div class="head-four">
            <span class="head-five">
                {{$userInfo[$systemName.'_adm_username']}}
            </span>
            <span class="head-five">管理员</span>
        </div>
    </div>
    @endif
    <div class="menu-hide"></div>
    <div class="menu-info">
        @if($systemName!='scm')
            <div class="menu-li ml-div-one"><i class="fa fa-user-o"></i><a href="@if($systemName=='oms' || $systemName=='dms')/#/auth/admin @else/#/auth/cmsadmin @endif" id="person">个人中心</a></div>
        @endif
        @if($systemName=='cms' || $systemName=='oms')
            <div class="menu-li ml-div-two"><i class="fa fa-gear"></i><a href="/#/system/basics" id="setting">系统设置</a></div>
        @endif
        <div class="menu-li ml-div-three logout"><i class="fa fa-sign-out"></i><a href="#">退出登录</a></div>
    </div>

    {{--sign out button--}}
    <div id="open">
        <div class="head-six">
            <i class="fa fa-power-off fa-2x head-seven"></i>
            <span class="head-eight">安全退出</span>
        </div>
    </div>
</nav>

{{--页面的遮罩层--}}
<div id="cover"></div>
{{--页面的弹出框--}}
<div id="modal">
    <div class="mask_first">
        <div class="mask_first_left">
            <img src="/images/tips-warn.png" class="mask_first_left_img">
        </div>
        <div class="mask_first_right">
            <div class="mask_first_right_title">安全退出</div>
            <div class="mask_first_right_text">您确定要退出登录吗?</div>
        </div>
    </div>
    <div class="mask_second">
        <div id="logout" class="mask_second_btn_confirm">确定</div>
        <div id="close" class="mask_second_btn_cancel">取消</div>
    </div>
</div>

@if ($systemName=='dms')
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

@endif

<script type="text/javascript" src="/js/jquery-3.3.1.min.js"></script>
<script>
    $(".one").mouseover(function () {
        $(this).attr("src","/images/icon1-1.png")
    });

    $(".one").mouseleave(function () {
        $(this).attr("src","/images/icon1.png")
    });

    $(".two").mouseover(function () {
        $(this).attr("src","/images/icon2-1.png")
    });

    $(".two").mouseleave(function () {
        $(this).attr("src","/images/icon2.png")
    });

    $(".telescopic").click(function () {
        if($(this).find(".flag").length > 0){
            $(".telescopic-icon").attr("class","fa fa-indent telescopic-icon  fa-lg")
        }else {
            $(".telescopic-icon").attr("class","fa fa-dedent telescopic-icon  fa-lg flag")
        }

    });

    function showlist() {
        $(".notice").addClass("show")
    }

    function hidelist() {
        $(".notice").removeClass("show")
    }

    $("#open").click(function() {
        cover.style.display = "block";   //显示遮罩层
        modal.style.display = "block";   //显示弹出层
    });

    $("#close").click(function() {
        cover.style.display = "none";   //隐藏遮罩层
        modal.style.display = "none";   //隐藏弹出层
    });

    $("#logout").click(function () {
        window.location.href = 'dashboard/logout'
    });

    //消息通知
    $("body").delegate("#news-bell","click",function () {
      var url = $('#news-bell').attr('data-url');
      window.location.href = url;
      window.location.reload();
    })

    //购物车
    $("body").delegate("#shop-cart","click",function () {
      var url = $(this).attr('data-url');
      window.location.href = url;
      window.location.reload();
    })

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

    $('.menu-info').hide();

    //头像栏鼠标移入
    $('.head-four').mouseenter(function () {
        $('.menu-info').show(500);
    })

    //头像栏鼠标移出
    $('.menu-info').mouseleave(function () {
        $(this).hide(500);
    })

    $('#open').mouseenter(function () {
        $('.menu-info').hide(500);
    })

    $('.menu-hide').mouseenter(function () {
        $('.menu-info').hide(500);
    })

    //退出登录
    $('.logout').click(function () {
        $('#open').click()
    })

    //个人中心
    $("body").delegate("#person","click",function () {
        var url = $(this).attr('href');
        window.location.href = url;
        window.location.reload();
    })

    //系统设置
    $("body").delegate("#setting","click",function () {
        var url = $(this).attr('href');
        window.location.href = url;
        window.location.reload();
    })

</script>
<style>
    .head-one{
        margin-right: 260px
    }
    .head-two{
        height: 57px;
        width: 150px;
        position: absolute;
        right: 80px;
        top: 0;cursor:pointer;
    }
    .head-three{
        width: 40px;
        height:40px;
        border-radius: 40px;
        margin-top: 9px;
        margin-left: 20px;
    }
    .head-four{
        width:85px;
        height: 57px;
        float: right;
        padding-top: 7px;
        z-index: 1000;
        cursor: pointer;
    }
    .head-five{
        font-size: 12px;
        display: block;
    }
    .head-six{
        height: 56px;
        width: 79px;
        position: absolute;
        right: 0;
        top: 0;
        background-color: rgb(232,58,64);
        cursor:pointer;
    }
    .head-seven{

        color: white;
        margin-left: 24px;
        margin-top: 5px;
    }
    .head-eight{
        width:100%;
        height:18px;
        color: white;
        font-size: 12px;
        text-align: center;
        position: relative;
        top:-2px;
        display: block
    }
    .head-nine{
        margin-top: 1px;
        margin-left: 4px;
        cursor:pointer;
    }
    .head-ten{
        width: 100%;
        height: 100%;

    }
    .telescopic-icon{
        color: rgba(0, 0, 0, 0.7) !important;
    }
    #cover{
        position:absolute;
        left:0px;
        top:0px;
        background:rgba(0, 0, 0, 0.4);
        width:100%;  /*宽度设置为100%，这样才能使隐藏背景层覆盖原页面*/
        height:100%;
        filter:alpha(opacity=60);  /*设置透明度为60%*/
        opacity:0.6;  /*非IE浏览器下设置透明度为60%*/
        display:none;
        z-Index:999;
    }
    #modal{
        position:absolute;
        width:350px;
        height:165px;
        top:35%;
        left:50%;
        transform: translateX(-50%);
        background-color:#fff;
        display:none;
        cursor:pointer;
        z-Index:9999;
        border-radius: 5px;
    }
    .mask_first{
        display: flex;
    }
    .mask_first_left{
        width: 140px;
        height: 125px;
        text-align: center;
        padding-top: 30px;
    }
    .mask_first_left_img{
        width: 60px;
        height: 60px;
    }
    .mask_first_right{
        width: 252px;
        height: 125px;
    }
    .mask_first_right_title{
        color:#101010;
        font-size: 16px;
        font-weight: 700;
        height: 60px;
        font-style: normal;
        letter-spacing: 0px;
        text-decoration: none;
        line-height: 95px;
    }
    .mask_first_right_text{
        font-size: 12px;
        color: rgba(0, 0, 0, 0.88);
        margin-top: 5px;
    }
    .mask_second{
        height: 40px;
        background-color: #F0F2F5;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        padding-right: 10px;
        border-radius: 5px
    }
    .mask_second_btn_confirm{
        width: 80px;
        height: 30px;
        background-color: red;
        color: #ffffff;
        font-size: 12px;
        line-height: 30px;
        text-align: center;
        border-radius: 4px;
        cursor: pointer;
        margin-right: 10px;
    }
    .mask_second_btn_cancel{
        width: 80px;
        height: 30px;
        background-color: #ffffff;
        color: black;
        font-size: 12px;
        line-height: 30px;
        text-align: center;
        border-radius: 4px;
        cursor: pointer;
        border: 1px solid #91959A;
    }
    .menu-li:hover{
       background-color: #f5f5f5;
    }
    .menu-hide{
        width: 50px;
        height: 60px;
        position: absolute;
        right: 225px;
        z-index: 1000;
    }
    .menu-info{
        width: 145px;
        position: absolute;
        border: 1px solid #f0f0f0;
        font-size:12px;
        right: 79px;
        top: 62px;
        background-color: #ffffff;
        display: flex;
        flex-direction: column;
        z-index: 1000;
        border-radius: 5px;
    }
    .ml-div-one{
        padding-left: 5px;
        height: 25px;
        line-height: 25px;
    }
    .ml-div-two{
        padding-left: 5px;
        height: 25px;
        line-height: 25px;
        border-bottom: 1px solid #f0f0f0;
    }
    .ml-div-three{
        padding-left: 5px;
        height: 30px;
        line-height: 30px
    }
    .menu-li a{
        margin-left: 10px;
        color: black;
    }
</style>

