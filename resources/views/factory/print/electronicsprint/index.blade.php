<!DOCTYPE html>
<html>
<head>
    <title>电子打单</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">

    <meta name="renderer" content="webkit">
    <link rel="stylesheet" href="{{ URL::asset('css/adminlte/adminlte.min.css') }}">
    <link rel="Shortcut Icon" href="/images/erp/erp_davicon.ico" />
    <link rel="stylesheet" href="{{URL::asset('css/erp/login/base.css')}}">
    <link rel="stylesheet" href="{{URL::asset('css/factory/electronisprint/index.css')}}">
    <link rel="stylesheet" href="{{URL::asset('css/erp/weui.css')}}">
    <meta name="_token" content="{{ csrf_token() }}"/>
</head>
<body class="inside-header inside-aside" style="background: #f5f5f5">

<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header " style="background-color: #fff">
            <a class="navbar-brand"  style="padding:0"><img src="../images/erp/crlogo.png" ></a>
        </div>

    </div>
</nav>

<div id="head_wrapper" class="head_wrapper s-title-img ">
    <div id="s_fm" class="s_form">
        <h1 style="text-align: center;">{{$company}}</h1>
        <div class="s_form_wrapper soutu-env-nomac soutu-env-newindex" id="s_form_wrapper"> <span id="s_kw_wrap" class="bg s_ipt_wr quickdelete-wrap">
      <input class="s_ipt" name="wd" id="kw" maxlength="100" autocomplete="off" placeholder="请输入订单号" type="text">
                <input  name="username" id="username" value="{{$username}}" type="hidden">
                <input  name="sp_id" id="sp_id" value="{{$sp_id}}" type="hidden">
     <input  name="username" id="username" value="" type="hidden">
      </span><span class="btn_wr s_btn_wr bg" id="s_btn_wr">
      <input value="确认" id="su" class="btn self-btn bg s_btn" type="button" onclick="submit()">
      </span>
            <div id="content">
                <div class="load"><img src="/images/loading.gif" ></div>
                <div class="error"></div>
                <div id="list">

                </div>
                <div id="print">
                    <h4 class="succ">集货已完成
                        <select id="printerListSelect" style="display: none;">
                            <option value="YTO">圆通快递</option>
                            <option value="YUNDA">韵达快递</option>
                            <option value="SF">顺丰速运</option>
                            <option value="STO">申通快递</option>
                        </select>
                        @if($is_new_code)
                        <input type="button" value="再打一单(新单号)" onclick="doPrint(0,'SF')"  style="display: none;" id="reprint_SF">
                        <input type="button" value="再打一单(新单号)" onclick="doPrint(0,'YTO')" style="display: none;" id="reprint_YTO">
                        <input type="button" value="再打一单(新单号)" onclick="doPrint(0,'YUNDA')" style="display: none;" id="reprint_YUNDA">
                        <input type="button" value="再打一单(新单号)" onclick="doPrint(0,'STO')" style="display: none;" id="reprint_STO">
                        <input type="button" value="再打一单(新单号)" onclick="doPrint(0,'EYB')" style="display: none;" id="reprint_EYB">
                        <input type="button" value="再打一单(新单号)" onclick="doPrint(0,'HTKY')" style="display: none;" id="reprint_HTKY">
                        @endif
                        <input type="hidden" value="" id="order_id">
                    </h4>
                    <h4 class="warning">集货未完成，暂时无法打印物流单</h4>
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
<script src="{{ URL::asset('assets/jquery/jquery.min.js')}}"></script>
<script src="{{ URL::asset('assets/layer/src/layer.js')}}"></script>
<script src="{{ URL::asset('js/factory/electronicsprint/print.js')}}"></script>
<script type="text/javascript" src="http://127.0.0.1:9999/CLodopfuncs.js?priority=1"></script>
</body>
</html>