{{--@extends('layout.erp_iframe')--}}
        <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>物流打单</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">

    <meta name="renderer" content="webkit">
    <link rel="stylesheet" href="{{ URL::asset('css/adminlte/adminlte.min.css') }}">
    <link rel="Shortcut Icon" href="/images/erp/erp_davicon.ico" />
    <link rel="stylesheet" href="{{URL::asset('css/erp/login/base.css')}}">
    <link rel="stylesheet" href="{{URL::asset('css/erp/weui.css')}}">
    <meta name="_token" content="{{ csrf_token() }}"/>
</head>
<body class="inside-header inside-aside" style="background: #f5f5f5">
<!--头部html-->

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
                        <input type="button" value="再打一单(新单号)" onclick="doPrint(0,'SF')"  style="display: none;" id="reprint_SF">
                        <input type="button" value="再打一单(新单号)" onclick="doPrint(0,'YTO')" style="display: none;" id="reprint_YTO">
                        <input type="button" value="再打一单(新单号)" onclick="doPrint(0,'YUNDA')" style="display: none;" id="reprint_YUNDA">
                        <input type="button" value="再打一单(新单号)" onclick="doPrint(0,'STO')" style="display: none;" id="reprint_STO">
                        <input type="button" value="再打一单(新单号)" onclick="doPrint(0,'EYB')" style="display: none;" id="reprint_EYB">
                        <input type="button" value="再打一单(新单号)" onclick="doPrint(0,'HTKY')" style="display: none;" id="reprint_HTKY">
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
    .navbar-brand img{width: 64%}
    .item img{width: 100%}
    .navbar{min-height:100px}
    /*.content{min-height:885px;}*/
    .footer{position: fixed;background-color: #fff !important;bottom: 0 }
    .navbar-inverse, .address{background-color: #fff !important;}
    @media (max-width: 450px) {
        .footer{
            position: relative;
        }
        footer.footer .address {
            line-height: normal;
        }
    }
    #head_wrapper {
        height: 38.2%;
        margin: 0 auto;
        min-height: 293px;
        position: relative;
        margin-top: 100px;
    }
    .s_form {
        height: 100%;
        margin: 0 auto 80px auto;
        min-height: 293px;
        text-align: left;
        width: 641px;
        z-index: 100;
    }
    .s_form_wrapper {
        height: 100%;
    }
    #head_wrapper .s-p-top {
        height: 61.8%;
        min-height: 181px;
        position: relative;
        text-align: center;
        z-index: 0;
    }
    .soutu-env-newindex .quickdelete-wrap {
        position: relative;
    }
    #head_wrapper.s-down .s_btn_wr, .s_ipt_wr, .s_btn_wr {
        display: inline-block;
        vertical-align: top;
    }
    .soutu-env-newindex.soutu-env-nomac #kw {
        width: 521px;
    }
    #kw {
        margin: 0;
    }
    #head_wrapper.s-down #kw, #kw {
        -moz-border-bottom-colors: none;
        -moz-border-left-colors: none;
        -moz-border-right-colors: none;
        -moz-border-top-colors: none;
        border-color: #b8b8b8 currentcolor #ccc #b8b8b8;
        border-image: none;
        border-style: solid none solid solid;
        border-width: 1px 0 1px 1px;
        box-shadow: none;
        font: 16px arial;
        height: 38px;
        outline: medium none;
        padding: 9px 7px;
        vertical-align: top;
        width: 521px;
    }
    button, input, select, textarea {
        font-size: 100%;
    }
    #head_wrapper.s-down .s_btn_wr, .s_btn_wr {
        -moz-border-bottom-colors: none;
        -moz-border-left-colors: none;
        -moz-border-right-colors: none;
        -moz-border-top-colors: none;
        background-color: #38f;
        border-color: #38f #38f #2e7ae5;
        border-image: none;
        border-style: solid;
        border-width: 1px;
        height: 38px;
        width: 102px;
    }
    #head_wrapper.s-down .s_btn_wr, .s_ipt_wr, .s_btn_wr {
        display: inline-block;
        vertical-align: top;
    }
    #head_wrapper.s-down .btn, .s-title-img .btn {
        background: #38f none repeat scroll 0 0;
        border: 0 none;
        box-shadow: none;
        color: white;
        cursor: pointer;
        font-size: 16px;
        font-weight: normal;
        height: 38px;
        line-height: 38px;
        padding: 0;
        width: 102px;
    }
    .btn {
        background-color: #38f;
        border: 0 none;
        color: white;
        font-size: 16px;
        height: 38px;
        width: 102px;
    }
    #content {
        margin-top: 50px;
    }
    #content .load {
        margin: 0 auto;
        width: 100px;
        display: none;
    }
    #content .error {
        text-align: center;
        color: red;
        display: none;
    }
    .info_table {
        background-color: #fff;
        border-collapse: collapse;
        font-size: 12px;
        text-align: center;
        width: 100%;
    }
    .info_table {
        border: 1px solid #ddd;
    }
    .info_table tr td.header-row {
        background-color: #f6faff;
        text-align: center;
    }
    .info_table tr th, .info_table tr td {
        border: 1px solid #ddd;
        padding: 10px;
    }
    h4 {
        text-align: right;
    }
    .warning {
        color: red;
        display:none;
        font-size: 16px;
        margin-top: 10px;
    }
    .succ {
        color: #090;
        display:none;
        font-size: 16px;
        margin-top: 10px;
    }

</style>
<script src="{{ URL::asset('assets/jquery/jquery.min.js')}}"></script>
<script src="{{ URL::asset('assets/layer/src/layer.js')}}"></script>
<script type="text/javascript" src="http://127.0.0.1:9999/CLodopfuncs.js?priority=1"></script>
<script type="text/javascript">
    $(function(){
        $('#kw').focus();
        doConnect();
        $('#kw').bind('input propertychange', function() {
            check($(this).val());
        });
    });
    var socket;
    var printers;
    var defaultPrinter;
    var printTaskId;
    var taskers;
    var waybillPrintStatus;
    var waybillNO = '';
    var printData;
    function doPrint(is_old,type='YTO')
    {
        var key = $('#kw').val();
        var ctype = type;

        $.ajax({
            type: 'POST',
            url: '/printscom/get-print-data',

            data: {key:key,ctype:ctype,is_old:is_old},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            beforeSend: function() {
                $('#content .error').hide();
                layer.load(2);
            },
            error:function(){
                layer.load(2);
            },
            complete:function() {
                layer.closeAll('loading');
            },
            success: function(data) {
                var res = $.parseJSON(data);
                if(res.status == '0'){
                    socket.send(JSON.stringify(res.content));
                }else{
                    $('#content .error').show().html(res.msg);
                }
                layer.closeAll('loading');
            },
        });
    }
    function doConnect()
    {
        socket = new WebSocket('ws://127.0.0.1:13528');
        socket.onopen = function(event)
        {
//                 alert("Websocket准备就绪,连接到客户端成功");
        };
        // 监听消息
        socket.onmessage = function(event)
        {
            console.log('Client received a message',event);
            var data = JSON.parse(event.data);
            if ("getPrinters" == data.cmd) {
                alert('打印机列表:' + JSON.stringify(data.printers));

                defaultPrinter = data.defaultPrinter;
                printData.task.printer = defaultPrinter;
                alert('默认打印机为:' + defaultPrinter);
            } else if("notifyPrintResult" == data.cmd){
                if("printed" == data.taskStatus){
                    layer.close(index);
                    layer.msg('打印成功', {icon: 1});
                    delivery(data.taskID);
                }
            }else if("print" == data.cmd){
                var index = layer.msg('打印中', {
                    icon: 16,shade: 0.1, time: 40000
                });
            }else{
                console.log("返回数据:" + JSON.stringify(data));
            }
        };

        // 监听Socket的关闭
        socket.onclose = function(event)
        {
            console.log('Client notified socket has closed',event);
        };

        socket.onerror = function(event) {
            alert('无法连接到:' + printer_address);
        };
    }
    function submit(){
        var key = $('#kw').val();
        $('#list').html('');
        check(key);
    }

    function delivery(taskid){
        $.ajax({
            type: 'POST',
            url: '/printscom/delivery',
            data: {taskid:taskid},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            beforeSend: function() {
            },
            error:function(){
            },
            complete:function() {
            },
            success: function(data) {
                var res = $.parseJSON(data);
                $('#kw').val("").focus();
//     	    	submit();
//     	    	layer.msg(res.msg);
            }
        });
    }


    function printLabel(content) {

        var count = LODOP.GET_PRINTER_COUNT();
        //获取打印机名称
        var index = -1;
        for(var i=0; i<count; i++) {
            console.log(LODOP.GET_PRINTER_NAME(i));
            if(LODOP.GET_PRINTER_NAME(i) == 'NPI22580B (HP LaserJet Professional M1216nfh MFP)') {
                index = i;
            }
        }

        if(index != -1) {
            LODOP.SET_PRINTER_INDEX(index);

            LODOP.SET_PRINT_PAGESIZE(1, 0, "", "A4"); //设置纸张
            LODOP.SET_PRINT_MODE("POS_BASEON_PAPER", true); //设置以纸张边缘为基点
            LODOP.SET_PRINT_STYLE("FontSize", 13); //设置字体
            LODOP.SET_PRINT_STYLEA(0,"Horient",2);
            LODOP.SET_PRINT_STYLEA(0,"Vorient",2);

            LODOP.ADD_PRINT_HTM(40, '20%', "100%", "BottomMargin:3mm", content);
            //LODOP.PREVIEW();//打印预览
            LODOP.PRINT();
        }

    }

    function check(key,stocked = 0){

        var username = $('#username').val();
        var pattern = /^[\d]{13}$/;

        if(pattern.test(key)){
            $('#reprint_SF').hide();
            $('#reprint_YTO').hide();
            $('#reprint_YUNDA').hide();
            $('#reprint1').hide();
            $('.succ').hide();
            $('.warning').hide();
            $("#order_id").val(key);
            $.ajax({
                type: 'POST',
                url: '/print/check',
                data: {key:key,stocked:stocked,username:username},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                beforeSend: function() {
                    $('#content .error').hide();
                    layer.load(2);
                },
                error:function(){
                    layer.load(2);
                },
                complete:function() {
                    layer.closeAll('loading');
                },
                success: function(data) {
                    var res = $.parseJSON(data);
                    if(res.status == '0'){
                        $('#list').html(res.content.list);

                        if(res.content.print == '1' && res.content.has_print =='0'){
                            if(res.content.can_print == '1'){doPrint(0,res.content.type);$('#reprint_'+res.content.type).show();}else{$('#kw').val("").focus();}
                            $('.succ').show();
                            $('.warning').hide();
                        }
                        if(res.content.print == '0' && res.content.has_print =='1'){
                            if(res.content.can_print == '1'){$('#reprint_'+res.content.type).show();}else{$('#kw').val("").focus();}
                            $('.succ').show();
                            $('.warning').hide();
                        }
                        if(res.content.print == '0' && res.content.has_print =='0'){
                            $('.warning').show();
                            $('.succ').hide();
                            $('#kw').val("").focus();
                        }

                        if(res.content.works_tags != '') {
                            setTimeout("printLabel('"+res.content.works_tags+"')","500");
                        }
                    }else if(res.status == '2'){
                        layer.confirm('当前订单需要备货'+ res.msg+'件', {
                            btn: ['确认已备货','取消'] //按钮
                        }, function(){
                            layer.closeAll();
                            check(key,1);
                        }, function(){
                            $('#kw').val("").focus();
                        });
                    }else{
                        $('#content .error').show().html(res.msg);
                        $('#list').html('');
                        $('#kw').val("").focus();
                    }

                    layer.closeAll('loading');

                }
            });
        }else{
            $('#content .error').show().html('请输入正确订单号');
            $('#reprint_SF').hide();
            $('#reprint_YTO').hide();
            $('#reprint_YUNDA').hide();
            $('#reprint1').hide();
            $('.succ').hide();
            $('.warning').hide();


        }
    }
</script>


