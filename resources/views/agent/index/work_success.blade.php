@extends('layout.agent_official',['title'=>'提交成功','flag'=>'work'])

@section("content")
    @if($msg!="")
        <div class="no-data" style="display: block;font-size: 16px;text-align: center">{{$msg}}</div>
    @else
<div class="system-message success">

    <div class="image">
        <img src="/images/success.svg" class="suc-image" alt="">
    </div>
    <div class="suc-content">
        <p class="first"> <span style="font-weight:bold;"><h2>您的作品已经提交成功！</h2> </span><br/><br/>
            <span style="padding-bottom: 10px;color: #444;font-size: 14px">
                <span style="margin-right: 10px;">作品编号:</span>
                <span id="copy-text">{{$data['project_no']}}</span>
                <button style="background-color: #dc5252;width:30%;border: none; height: 30px;  margin-left: 20px; color: white" id="copy" data-clipboard-action="copy" data-clipboard-target="#copy-text" >复 制</button>
            </span>
            <br/><br/>
            <span style="padding-bottom: 10px;color: #444;font-size: 14px">订单支付后，生产期限需要4-7个工作日!(不包含周末节假日和快递时间)</span>
            <br/><br/>
        </p>
        <div style="min-height: 100px">
            <span style="padding-bottom: 10px;color: #444;font-size: 14px;float: left">分享作品：</span>
        <div id="qrcode" style="margin-bottom: 25px"></div>
        <input type="hidden" value="{{$data['qr_code']}}" id="qrcode_url">
        </div>
        <div id="notice" style="margin-top: 20px">
            <a href="http://{{$data['url']}}" class="preview_work" target="_blank" style="text-decoration: none"><span style="color: #FFF;">预览作品</span></a>
            <a href="http://{{$data['make_url']}}" class="continue_make" target="_blank" style="text-decoration: none"><span style="color: #7E7E7E;">继续制作</span></a>
        </div>
    </div>
    <h1></h1>
</div>
   @endif
<footer class="footer" style="position:fixed;bottom: 0;">

    <p class="address" style="background-color: #fff;">
        <span>{{$deployInfo['deploy_remarks']}}</span>
        <span style="margin-left: 20px;margin-right: 20px;">@if(!empty($deployInfo['deploy_seat_number']))联系方式: {{$deployInfo['deploy_seat_number']}}@endif</span>
        <span>@if(!empty($deployInfo['deploy_address']))地址：{{$deployInfo['deploy_address']}}@endif</span>&nbsp;
    </p>

</footer>
@endsection
@section("js-file")
    <script src="{{ URL::asset('assets/clipboard/clipboard.min.js')}}"></script>
    <script src="{{ URL::asset('assets/jeromeetienne-jquery/jquery.qrcode.min.js')}}"></script>
@endsection
@section("pages-js")
    var clipboard = new Clipboard('#copy')
    clipboard.on('success', function (e) {
        layer.confirm('作品编号已复制到剪贴板', {
            btn: ['确定'] //按钮
        });
    });

    clipboard.on('error', function (e) {
        layer.confirm('作品编号复制失败，请重试一次', {
            btn: ['确定'] //按钮
        });
    });

    jQuery('#qrcode').qrcode($('#qrcode_url').val());

@endsection
<style type="text/css">
    html, body {
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0;
    }

    body {
        display: flex;
        align-items: center; /*定义body的元素垂直居中*/
        justify-content: center; /*定义body的里的元素水平居中*/
    }

    #main{
        padding: 0 !important;
    }

    .system-message {
        min-height: 600px;
        margin: 20px 5%;
        padding: 40px 20px;
        background: #fff;
        box-shadow: 1px 1px 1px hsla(0,0%,39%,.1);
    }

    .system-message h1 {
        margin: 0;
        margin-bottom: 9pt;
        color: #444;
        font-weight: 400;
        font-size: 40px
    }

    .system-message .jump a {
        color: #333
    }

    .image {
        width: 45%;
        min-height: 500px;
        float: left;
        text-align: center
    }

    .suc-image {
        width: 300px;
        margin-top: 10%
    }

    .suc-content {
        width: 30%;
        min-height: 500px;
        float: left;
        text-align: left
    }

    .first {
        display: inline-block;
        position: relative;
        font-size: 15px;
        margin-top: 70px;
        /*font-family: cursive;font-weight: bold*/
    }

    .first span:first-child {
        position: absolute;
        left: 0;
    }

    #qrcode canvas {
        width: 150px;
        height: 150px
    }

    .suc-re {
        margin-top: 100px;
    }

    .m-btn-primary {
        color: #fff;
        background: #387ee8;
    }

    .m-btn-large {
        width: 200px;
        height: 50px;
        font-size: 16px;
        line-height: 50px;
    }

    .m-btn {
        display: inline-block;
        border: 0;
        border-radius: 2px;
        box-sizing: border-box;
        color: #fff;
        background: #5f99f1;
    }

    .ready {
        color: #fff;
        width: 200px;
        height: 50px;
        font-size: 16px;
        line-height: 50px;
        display: inline-block;
        border: 0;
        border-radius: 2px;
        box-sizing: border-box;
    ;background: #5f99f1;
    }

    .ready:hover {
        background-color: #5f99f1;
        color: white;
        border-color: #5f99f1;
        outline-offset: 0;
    }

    .ready:focus {
        background-color: #5f99f1;
        color: white;
        border-color: #5f99f1;
        outline-offset: 0;
    }

    .link {
        color: white;
        text-align: center
    }

    .link:hover {
        color: white
    }

    @media (max-width:950px) {
        .suc-image {
            width: 250px;
        }
    }

    @media (max-width:880px) {
        #qrcode canvas {
            width: 120px;
            height: 120px
        }
    }

    @media (max-width:768px) {
        .image {
            display: none
        }

        .suc-content {
            width: 100%
        }

        #qrcode canvas {
            width: 135px;
            height: 135px
        }
    }

    @media (max-width:480px) {
        .system-message h1 {
            font-size: 30px;
        }
    }

    .preview_work{
        background-color: #7BC6C2;
        display:inline-block;
        width:44%;
        border: none;
        height: 37px;
        text-align: center;
        line-height: 37px;
        text-decoration: none
    }

    .continue_make{
        background-color: #EFEFEF;
        display:inline-block;
        width:44%;
        border: solid 1px slategrey;
        height: 37px;
        text-align: center;
        line-height: 37px;
        text-decoration: none
    }
</style>



