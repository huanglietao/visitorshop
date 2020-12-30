<!DOCTYPE html>
<html lang="en">
<head></head>
<body class="inside-header inside-aside" style="background: #f5f5f5">
{{--@extends('layout.erp_iframe')--}}
<meta charset="utf-8" />
<title>物流打单</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
<meta name="renderer" content="webkit" />
<link rel="stylesheet" href="{{ URL::asset('css/adminlte/adminlte.min.css') }}" />
<link rel="Shortcut Icon" href="/images/erp/erp_davicon.ico" />
<link rel="stylesheet" href="{{URL::asset('css/erp/login/base.css')}}" />
<link rel="stylesheet" href="{{URL::asset('css/erp/weui.css')}}" />
<meta name="_token" content="{{ csrf_token() }}" />
<!--头部html-->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation" style="position: fixed;top: 0;width: 100%;z-index:1030">
    <div class="container" style="position: absolute;left: 80px;top: 18px">
        <div class="navbar-header " style="background-color: #fff">
            <a class="navbar-brand" style="padding:0"><img src="../images/erp/crlogo.png" /></a>
        </div>
    </div>
</nav>
@inject('CommonPresenter','App\Presenters\CommonPresenter');

<div style="background-color: #f5f5f5;height: 2px;width: 100%;margin-top: 88px;"></div>

<div class="main-table" style="width: 100%;background-color: white;">

    <div style="background-color: white;width:85%;margin: 0 auto;padding-top: 20px">
        <div style="margin:0">
            @component('component.navOperateTab',['navlist'=>config('printer.tab_list'),'extendClass'=>"s_analy_tab",'defaut_key'=>$product_name])@endcomponent
        </div>
        <div style="width: 100%;border-left: 1px solid #dee2e6; border-right: 1px solid #dee2e6;padding: 10px;">
            <button class="btn btn-3F51B5 btn-primary btn-all" style="cursor:pointer;">全选</button>
            <button class="btn btn-3F51B5 btn-primary btn-print-all" style="cursor:pointer;">批量打单</button>
            <button class="btn btn-3F51B5 btn-primary btn-clear" style="cursor:pointer;">清除异常订单</button>
            <button id="excel_btn" class="btn  btn-3F51B5" style="color: white;float: right"><i class="fa fa-cloud-upload"></i>&nbsp;导入订单</button>
            <input id="excel_upload" hidden style='width:140px;display: inline-block;padding-left: 0' type='file' accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel" class="col-sm-5" onchange="upload(this)">
        </div>
        <table id="table" class="table table-bordered" width="100%">
            <tbody data-listidx="0">
            <tr class="nav_color">
                <th class="center" style="width: 10%">客户单号</th>
                <th class="center" style="width: 10%">是否加急</th>
                <th class="center" style="width: 10%">产品名称</th>
                <th class="center" style="width: 10%">快递方式</th>
                <th class="center" style="width: 25%">收件人信息</th>
                <th class="center" style="width: 10%">客户订单时间</th>
                <th class="center" style="width: 10%;">操作</th>
            </tr>
            <tr>
                <td colspan="7"></td>
            </tr>

            @foreach ($data as $item)

                <!--第一行-->
                <tr class="color">
                    <td colspan="7" style="vertical-align: middle;">
                        <input type="checkbox" data-value="{{$item['order_no']}}">
                        @if($item['error_msg'] != '')
                            <span style="float: right;color:red;">打印出错：{{$item['error_msg']}}</span>
                        @endif
                    </td>
                </tr>
                <!--第二行开始详情-->
                <tr class="pass">
                    <td class="center">{{$item['order_no']}}</td>
                    <td class="center">否</td>
                    <td class="center">{{$item['goods_name']}}</td>
                    <td class="center">圆通快递</td>
                    <td class="center" rowspan="1">
                        <div class="center-block">
                            <p><img style="width: 16px;height: 16px;margin-right: 5px;" src="/images/27.png"><span>{{$item['receiver_info']['accept_name']}}</span></p>
                            <p><img style="width: 16px;height: 16px;margin-right: 5px;" src="/images/28.png"><span>{{$item['receiver_info']['mobile']}}</span></p>
                            <p><img style="width: 16px;height: 16px;margin-right: 5px;" src="/images/30.png">
                                <span style="display: block;width: 99%">
                                    {{$item['receiver_info']['p']}}{{$item['receiver_info']['c']}}{{$item['receiver_info']['a']}}{{$item['receiver_info']['d']}}
                                </span>
                            </p>
                        </div>
                    </td>
                    <td class="center">{{date('Y-m-d H:i:s',$item['order_time'])}}</td>
                    <td class="center" rowspan="1">
                        <p>
                            <button class="btn btn-primary btn-print" data-value="{{$item['order_no']}}">打印面单</button>
                        </p>
                    </td>
                </tr>
            @endforeach

            @empty($data)
                <tr class="pass">
                    <td colspan="7" class="center">暂无数据</td>
                </tr>
            @endempty
            </tbody>
        </table>

        <div class="fixed-table-pagination">
            <div class="pull-left pagination-detail">
            </div>
        </div>

    </div>

</div>
<style>
    body{
        background-color: #fff !important;
    }
    .footer {
        background-color: #fff !important;
        margin-top: 2px;
    }

    .nav_operate_tab{
        height: 44px;
        border-bottom: 1px solid #dee2e6;
        font-size: 14px;
    }
    .nav_status_btn{
        float: left;
        /*width: 7%;*/
        padding: 0 16px;
        line-height: 43px;
        text-align: center;
        border-top: 1px solid #dee2e6;
        border-left: 1px solid #dee2e6;
        border-bottom: 1px solid #dee2e6;
        background-color:#f1f1f1;
        cursor: pointer;
    }
    .nav_status_current{
    background-color: #fff;
    color: #E83A36 ;
    }
    .nav_custom_button{
        float: right;
        text-align: center;
        line-height: 45px;
    }


    .navbar-inverse, .address {
        background-color: #fff !important;
    }

    .geetest_holder {
        width: 310px !important;
    }

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
    .navbar-brand img {
        width: 64%
    }

    .item img {
        width: 100%
    }

    .navbar {
        min-height: 80px;
        margin-bottom: 2px;
    }
    .navbar-inverse, .address {
        background-color: #fff !important;
    }
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
        background-color: #E83A36 !important;
        border-color: #E83A36 !important;
        padding: 3px 10px !important;
        font-size: 12px;
        display: inline-block;
        font-weight: 400;
        color: white;

    }
    .btn-primary {
        color: #fff;
        box-shadow: 0 1px 1px rgba(0, 0, 0, .075);
    }
    .btn-primary:hover {
        color: #fff !important;
        background-color: #E83A36 !important;
        border-color: #fd8c3e !important;
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


    /*表格css*/
    .table-bordered {
        border: 1px solid #f4f4f4;
    }
    .table {
        width: 100%;
        max-width: 100%;
        margin-bottom: 17px;
        background-color: transparent;
        border-collapse: collapse;
        border-spacing: 0;
        font-size: 12px;
    }

    tbody {
        display: table-row-group;
        vertical-align: middle;
        border-color: inherit;
    }

    .nav_color {
        background-color: #fff;
    }

    tr {
        display: table-row;
        vertical-align: inherit;
        border-color: inherit;
    }

    .color {
        background-color: #f1f1f1;
    }

    .center {
        text-align: center;
        vertical-align: middle;
    }

    .print-log {
        margin-left: 60px;
        float: right;
        cursor: pointer;
        margin-right: 1%;
        color: red;
    }

    .fixed-table-pagination:after, .fixed-table-toolbar:after {
        content: "";
        display: block;
        clear: both;
    }

    .fixed-table-pagination .pagination-detail, .fixed-table-pagination div.pagination {
        margin-top: 10px;
        margin-bottom: 10px;
        font-size: 12px;

    }

    .pull-left {
        float: left !important;
    }

    .fixed-table-pagination .pagination-info {
        line-height: 34px;
        margin-right: 5px;
    }

    .center-block {
        display: block;
        margin-left: auto;
        margin-right: auto;
        text-align: center;
        margin-top: auto;
        width: 60%;
    }
    .table tr td .center-block p {
        display: flex;
    }
    .center-block p {
        text-align: left;
    }
    p {
        margin: 0 0 8.5px;
    }
    th > td, .table-bordered > tbody > tr > td {
        vertical-align: middle;
    }

    .btn-disabled {
        pointer-events: none;
    }

    .controll-display{
        display:none;
    }

    .layui-layer-btn0{
        color:#fff !important;
    }
    .layui-layer-btn{
        font-size: 14px;
    }

</style>
<script src="{{ URL::asset('assets/jquery/jquery.min.js')}}"></script>
<script src="{{ URL::asset('assets/layer/src/layer.js')}}"></script>
<script type="text/javascript">
    var select_arr = [];
    var print_length = 0;
    var totla_length = 0;
    var fail_length = 0;
    var delivery_type = 'YTO';

    doConnect();

    //打印面单按钮
    $(".btn-print").click(function () {
        $(".btn-print").addClass("btn-disabled")
        $(".btn-print-all").addClass("btn-disabled")
        $(".btn-clear").addClass("btn-disabled")
        var order_no = $(this).attr("data-value");
        select_arr = []
        select_arr.push(order_no)
        print_length = select_arr.length
        totla_length = print_length

        tips(print_length,0,0,print_length)
        printFace(order_no)
    })

    //批量打单
    $(".btn-print-all").click(function () {
        if(select_arr.length < 1){
            layer.msg("请选择需要打印的订单")
        }else{
            $(this).addClass("btn-disabled")
            $(".btn-print").addClass("btn-disabled")
            $(".btn-clear").addClass("btn-disabled")

            print_length = select_arr.length
            totla_length = print_length

            tips(print_length,0,0,print_length)
            for (var index in select_arr){
                printFace(select_arr[index])
            }
        }
    })

    function printFace(order_no,ctype='YTO') {
        $.ajax({
            type: 'POST',
            url: '/print/printdata',
            dataType : 'json',
            data: {order_no:order_no,ctype:ctype,delivery_type:delivery_type},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            beforeSend: function() {
                // var index = layer.msg('打印中...');
            },
            error:function(res){
                console.log(res)
                fail_length++
                print_length--
                // console.log('total:'+totla_length+'--success:'+(totla_length-fail_length-print_length)+'--fail:'+fail_length+'--surplus'+print_length)
                tips(totla_length,(totla_length-fail_length-print_length),fail_length,print_length)
            },
            complete:function() {
                // layer.closeAll('loading');
            },
            success: function(data) {
                // console.log(data)
                // var res = $.parseJSON(data);
                // console.log(data['status'])
                if(data['status'] == '0') {
                    if(delivery_type == 'sf'){
                        //顺丰面单
                        // console.log(data)
                        // console.log(data.content)
                        requestSF(data.content.reqURL,data.content.post_json_data,data.content.taskID)
                    }else{
                        //菜鸟面单
                        socket.send(JSON.stringify(data.content));
                    }
                }else if(data['status'] == '1'){
                    //异常提醒
                    // console.log('异常提醒')
                    fail_length++
                    print_length--
                    // console.log('total:'+totla_length+'--success:'+(totla_length-fail_length-print_length)+'--fail:'+fail_length+'--surplus'+print_length)
                    tips(totla_length,(totla_length-fail_length-print_length),fail_length,print_length)
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
                // alert("Websocket准备就绪,连接到客户端成功");
        };
        // 监听消息
        socket.onmessage = function(event)
        {
            // console.log('Client received a message',event);
            var data = JSON.parse(event.data);
            if ("getPrinters" == data.cmd) {
                alert('打印机列表:' + JSON.stringify(data.printers));

                defaultPrinter = data.defaultPrinter;
                printData.task.printer = defaultPrinter;
                alert('默认打印机为:' + defaultPrinter);
            } else if("notifyPrintResult" == data.cmd){
                if("printed" == data.taskStatus){
                    // layer.close(index);
                    // layer.msg('打印成功', {icon: 1});
                    delivery(data.taskID);
                    // console.log('触发发货')
                }
            }else if("print" == data.cmd){
                // var index = layer.msg('打印中', {
                //     icon: 16,shade: 0.1, time: 40000
                // });
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

    function delivery(taskid){
        $.ajax({
            type: 'POST',
            url: '/print/delivery',
            data: {taskid:taskid},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            beforeSend: function() {
            },
            error:function(res){
                console.log(res)
                fail_length++
                print_length--
                // console.log('total:'+totla_length+'--success:'+(totla_length-fail_length-print_length)+'--fail:'+fail_length+'--surplus'+print_length)
                tips(totla_length,(totla_length-fail_length-print_length),fail_length,print_length)
            },
            complete:function() {
            },
            success: function(data) {
                var res = $.parseJSON(data);
                print_length = print_length-1;
                if(res['status'] == 1){
                    fail_length ++
                }
                tips(totla_length,(totla_length-fail_length-print_length),fail_length,print_length)
            }
        });
    }

    //全选
    var select_all = 0;
    $(".btn-all").click(function () {
        if(select_all == 0){
            $(":checkbox").prop("checked", true)
            select_all = 1;
            $("input[type='checkbox']:checked").each(function(i){
                select_arr[i] =$(this).attr("data-value");
            });
        }else{
            $(":checkbox").prop("checked", false)
            select_all = 0;
            select_arr = [];
        }
    })

    //点击checkbox
    $("input[type='checkbox']").click(function () {
        var value = $(this).attr("data-value")
        if($(this).prop("checked")){
            select_arr.push(value)
        }else{
            select_arr.splice($.inArray(value,select_arr),1)
        }
    })

    //点击tab按钮
    $(".main-table").delegate(".nav_status_btn","click",function () {
        $(this).addClass("nav_status_current").siblings(".nav_status_btn").removeClass("nav_status_current");
        //获取该产品名
        var val = $(this).attr('data-val');

        //拼接跳转链接
        var url = "/print/print_deliver?product_name="+val+"&limit_num=20&order_no=";
        window.location.href=url;


    })


    //打单tips
    function tips(total,success,fail,surplus) {
        if(surplus == 0){
            // var name = $(".del-btn").parents(".layui-layer").attr("times");
            // //先得到当前iframe层的索引
            // layer.close(name);
            layer.closeAll()
        }
        $.ajax({
            url : "/print/tips",
            type: 'POST',
            data:{
                success:success,
                total:total,
                fail:fail,
                surplus:surplus
            },
            dataType : 'JSON',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success : function(data){
                if(data['layre_flag'] != 0){
                    layer.open({
                        type:1,
                        title:false,
                        closeBtn: 0,
                        resize : false,
                        shade:0.1,
                        area:['600px','183px'],
                        skin:"success-skin",
                        content: data.html,
                        zIndex: layer.zIndex,
                        success: function(layero, index){
                            // console.log(layero)
                            // console.log(index)
                        }
                    });
                }else{
                    $(".new-text").html('(共'+data['html']['total']+'，成功'+data['html']['success']+'，失败'+data['html']['fail']+'，剩余'+data['html']['surplus']+')')
                }

            },
        });
    }

    //tips中按钮事件
    $("body").delegate("#sure-comfirm,#del-cancel","click",function () {
        // var name = $("#sure-comfirm").parents(".layui-layer").attr("times");
        // //先得到当前iframe层的索引
        layer.closeAll();
        window.location.reload();//刷新当前页面.

    });

    //清除异常订单
    $(".btn-clear").click(function () {
        layer.confirm('清除后可在异常订单栏中操作所有异常订单', {
            btn: ['确定','取消'], //按钮
            title: '提示',
        }, function(){
            //执行清除
            $.ajax({
                type: 'POST',
                url: '/printer/clear',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                success: function(data) {
                    var res = $.parseJSON(data);
                    if(res['status'] == 0){
                        layer.msg(res['msg'], {icon: 1});
                    }else{
                        layer.msg('出错了', {icon: 5});
                    }
                    window.location.reload();//刷新当前页面.
                }
            });
        }, function(){
            //取消

        });
    })


    //======================Excel导入开始 =============//
    $("#excel_btn").click(function () {
        $("#excel_upload").click();
    });

    function upload(obj){
        var files = obj.files;
        layer.alert('数据导入中，请勿关闭', {
            skin: 'layui-layer-molv' //样式类名
            ,closeBtn: 0
        });
        for(var i = 0;i<files.length;i++){
            fd = new FormData();
            var file = files[i];
            fd.append('file',file);
            fd.append('_token','{{csrf_token()}}');

            $("#excel_upload").val('');
            $.ajax({
                url: "/printer/import",
                type: "POST",
                data: fd,
                processData: false,
                contentType: false,
                success: function (ret) {
                    if(ret.status==200 && ret.success=='true'){
                        layer.msg(ret.data);
                        setTimeout(function () {
                            window.location.reload();//刷新当前页面.
                        },2000);

                    }else if(ret.status==404 && ret.success=='false'){
                        layer.msg(ret.data);
                    }
                },
                error:function () {
                  layer.msg("程序出现错误!");
                }
            });
        }
    }
    //======================Excel导入结束 =============//



</script>
</body>
</html>