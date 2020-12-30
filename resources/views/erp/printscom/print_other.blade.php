<html lang="en">
<head></head>
<body class="inside-header inside-aside" style="background: #f5f5f5">
{{--@extends('layout.erp_iframe')--}}
<meta charset="utf-8" />
<title>其他物流单号补录</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
<meta name="renderer" content="webkit" />
<link rel="stylesheet" href="{{ URL::asset('css/adminlte/adminlte.min.css') }}" />
<link rel="Shortcut Icon" href="/images/erp/erp_davicon.ico" />
<link rel="stylesheet" href="{{URL::asset('css/erp/login/base.css')}}" />
<link rel="stylesheet" href="{{URL::asset('css/erp/weui.css')}}" />
<meta name="_token" content="{{ csrf_token() }}" />
<!--头部html-->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation" style="position: fixed;top: 0;width: 100%;z-index:1030">
    <div class="container">
        <div class="navbar-header " style="background-color: #fff">
            <a class="navbar-brand" style="padding:0"><img src="../images/erp/crlogo.png" /></a>
        </div>
    </div>
</nav>

<div style="background-color: #f5f5f5;height: 2px;width: 100%;margin-top: 88px;"></div>

<div class="main-table" style="width: 100%;background-color: white;">

    <div style="background-color: white;width:85%;margin: 0 auto;padding-top: 5%;font-size:12px;padding-left: 15%;">

        <form method="post" action="" onsubmit="return false">
            @csrf
            <div class="col-sm-12 col-xs-12" style="width: 100%;flex-direction: row;display: flex;margin-bottom: 15px">
                <div style="width:14%;margin-right: 20px;text-align: right;line-height: 27px;">查询方式:</div>
                <div style="width: 50%">
                    @component('component/radio',['radio'=>['0'=>'订单','1'=>'出货单'],'name'=>'order_type','default_key'=>'0'])
                    @endcomponent
                </div>
            </div>

            <div class="col-sm-12 col-xs-12 trade_order_name" style="width: 100%;flex-direction: row;display: flex;margin-bottom: 15px">
                <div style="width:14%;margin-right: 20px;text-align: right;line-height: 27px;"><span style="color: red;margin-right: 2px">*</span>贸易订单编号:</div>
                <div style="width: 50%">
                    <input class="form-control" style="width: 100%;margin-bottom: 5px;" name="trade_order_name" placeholder="请输入贸易订单编号" />
                </div>
            </div>

            <div class="col-sm-12 col-xs-12 trade_stock_move_name control-display" style="width: 100%;flex-direction: row;display: flex;margin-bottom: 15px">
                <div style="width:14%;margin-right: 20px;text-align: right;line-height: 27px;"><span style="color: red;margin-right: 2px">*</span>贸易出货单编号:</div>
                <div style="width: 50%">
                    <input class="form-control" style="width: 100%;margin-bottom: 5px;" name="trade_stock_move_name" placeholder="请输入贸易出货单编号" />
                </div>
            </div>

            <div class="col-sm-12 col-xs-12" style="width: 100%;flex-direction: row;display: flex;margin-bottom: 15px">
                <div style="width:14%;margin-right: 20px;text-align: right;line-height: 27px;"><span style="color: red;margin-right: 2px">*</span>物流方式:</div>
                <div style="width: 50%">
                    <select class="form-control" name="express_type">
                        <option value="yto">圆通快递</option>
                        <option value="sto">申通快递</option>
                        <option value="zto">中通快递</option>
                        <option value="yunda">韵达快递</option>
                        <option value="best">百世快递</option>
                        <option value="sfj">顺丰寄</option>
                        <option value="sfd">顺丰到</option>
                        <option value="ems">中国邮政快递包裹</option>
                        <option value="since">自提</option>
                        <option value="other">其他快递</option>

                    </select>
                </div>
            </div>

            <div class="col-sm-12 col-xs-12" style="width: 100%;flex-direction: row;display: flex;margin-bottom: 15px">
                <div style="width:14%;margin-right: 20px;text-align: right;line-height: 27px;"><span style="color: red;margin-right: 2px">*</span>物流单号:</div>
                <div style="width: 50%">
                    <input class="form-control" style="width: 100%;margin-bottom: 5px;" name="express_num" placeholder="请输入物流单号" />
                </div>
            </div>

            <div class="col-sm-12 col-xs-12" style="width: 100%;height: 80px;flex-direction: row;display: flex">
                <div style="width:14%;margin-right: 20px;text-align: right;line-height: 130px;"></div>
                <div style="width: 50%;margin-top: 50px;"><button type="submit" class="btn btn-3F51B5 btn-primary btn-sure btn-sub" style="cursor:pointer;">确定</button></div>
            </div>
        </form>

    </div>

</div>

<footer class="footer" style="clear:both">
  <p class="address">&copy;2019 天津长荣云印刷科技有限公司版权所有 . 备案号：津ICP备08101169号-1 联系方式: 022-26881958 地址：天津市北辰高端装备产业园永兴道102号
</footer>
<style>
    /*radio样式 start*/
    .c_d_radio{
        display: inline-block;
    }

    .c_d_radio .radio+label {
        width: 20px;
        height: 20px;
        background-color: white;
        border: 2px solid #ccc;
        display: inline-block;
        vertical-align: middle;
        border-radius: 50%;
        cursor: pointer;
    }

    .c_d_radio .radio {
        display: none;
    }

    .c_d_radio .radio:checked+label {
        border-radius: 50%;
        border: 2px solid #E83A36;
        background: #E83A36;
        padding: 3px;
        background-clip: content-box;
    }
    /*radio样式 end*/
    .form-control{
        height: 30px;
        border-radius: 1px;
        font-size: 12px !important;
        color: #6A6969;
        padding: .375rem .75rem;
        font-weight: 400;
        line-height: 1.5;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        box-shadow: inset 0 0 0 transparent;
        transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    }
    body{
        background-color: #fff !important;
    }
    .footer {
        position: fixed;
        bottom: 0;
        background-color: #fff !important;
        margin-top: 2px;
        border-top: 2px #f5f5f5 solid;
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

    .control-display{
        display: none !important;
    }
</style>
<script src="{{ URL::asset('assets/jquery/jquery.min.js')}}"></script>
<script src="{{ URL::asset('assets/layer/src/layer.js')}}"></script>
<script type="text/javascript">

    $("input[name='order_type']").click(function () {
        if($(this).val() == 0){
            //订单
            $(".trade_stock_move_name").addClass("control-display")
            $("input[name='trade_stock_move_name']").val('')
            $(".trade_order_name").removeClass("control-display")

        }else{
            //出货单
            $(".trade_order_name").addClass("control-display")
            $("input[name='trade_order_name']").val('')
            $(".trade_stock_move_name").removeClass("control-display")

        }
    })


    //表单提交
    $(".btn-sub").click(function () {
        var data = {};
        var form = $('form').serializeArray();
        $.each(form, function() {
            data[this.name] = this.value;
        });

        if(data['order_type'] == 0){
            if(data['trade_order_name'] == ''){
                layer.msg('请输入贸易订单编号')
                return false
            }
        }else{
            if(data['trade_stock_move_name'] == ''){
                layer.msg('请输入贸易出货单编号')
                return false
            }
        }

        if(data['express_type'] == ''){
            layer.msg('请输入物流方式')
            return false
        }

        if(data['express_num'] == ''){
            layer.msg('请输入物流单号')
            return false
        }

        $.ajax({
            url:'/print/write_back',
            type: "POST",
            dataType : 'JSON',
            data:{data:data},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            success: function (data) {
                 if(data['success'] == 'true') {
                    layer.msg('操作成功', {icon: 1, time:2000}, function(){
                        location.href="";
                    });
                    
                   
                 } else {
                     layer.msg(data.msg)
                     return false
                 }
            }
        });
    })
</script>
</body>
</html>