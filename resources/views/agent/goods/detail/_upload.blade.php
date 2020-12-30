<!DOCTYPE html>
<html>
<head>
    <title>作品文件上传</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta charset="UTF-8" />
    <meta name="data-spm" content="a1zaa" />
    <meta name="_token" content="{{ csrf_token() }}"/>
    <meta http-equiv="Expires" content="0" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Cache-control" content="no-cache" />
    <meta http-equiv="Cache" content="no-cache" />
    <meta name="viewport" content="width=device-width" />
    <link rel="Shortcut Icon" href="/images/erp/erp_davicon.ico" />
</head>
<link rel="stylesheet" href="{{URL::asset('css/agent/goods/template.css')}}">
<link rel="stylesheet" href="{{ URL::asset('assets/font-awesome/css/font-awesome.min.css') }}">
<link rel="stylesheet" href="{{URL::asset('assets/bootstrap/css/bootstrap.min.css')}}" />
<link rel="stylesheet" href="{{URL::asset('css/backend.css')}}" />
<style>
    .fixed-table-pagination .pagination a {
        padding: 4px 12px;
        line-height: 1.428571429;}
    .pagination > .active > a{background-color:#3ad04d;border-color:#ccc;}
    .pagination > .active > a:hover{background-color:#3ad04d;border-color:#ccc;}
    .searh_box i{position:relative;left: 100px;top:-21px;cursor:pointer;}
    .nav-pills > li.active > #imgsort{background-color: #f7f7f7;}
    .nav > li > a:focus{background: #fff;}
    .tabs-wrapper .tabs-group ul{margin: 0 0 0 69px;}
    .content {
        padding: 0 0 0 0;
        margin-left: 0;
        margin-right: 0;
        min-height: 100vh;
        background: #F5F5F5;
    }
    .mainone {
        width: 1200px;
        margin-left: auto;
        margin-right: auto;
        margin-top: 98px;
        min-height:500px;
        border: 1px solid #F5F5F5;
        background:#fff;
    }
    .title{text-align: center;font-weight:bold;font-size:20px;margin:20px}
    .tips{width:80%;margin-left:10%;border:1px dotted gray;padding:8px}
    .xz{color:red}
    ul li{margin-top:8px;}
    #upload {width:90%;margin-left:2%}
    .up-tbl{width:50%;margin-top:20px}
    .tit{font-weight:bold;text-align: right;padding-right:10px;width:40%;}
</style>
<body style="padding: 0;margin: 0;">
<!--头部html-->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="/" style="padding:6px 15px;"><h3 style="margin: 7px;color: white">{{$data['agent_name']}}</h3></a>
        </div>
    </div>
</nav>

<div id="main" style="background-color: #F5F5F5">
    <div class="tab-content tab-addtabs">
        <div id="content">
            <div class="content">
                <div class="mainone">
                    <p class="title">作品文件上传</p>
                    <div class="tips">
                        <p class="xz"> <i style="font-size:16px" class="fa fa-question-circle-o" aria-hidden="true"></i>上传需知</p>
                        <ul style="font-size:12px;padding:0px;margin-left:25px">
                            <li>请确保上传稿件的p数与所选商品p数一致，否则会导致无法生产</li>
                            {{--<li>对于jpg图片，必须打成一个zip包，图片命名规则为0.jpg,1.jgp,2.jpg....这种规律递增，其中--}}
                                {{--0.jpg表示封面(如无封面，则从1.jpg开始)，否则会导致无法生产</li>--}}
                            <li>由于附件较大，上程可能时间比较久，在上传过程中请勿关闭当前网页，否则再次进入后需要
                                重新上传。若中途上传出错，点击重试会进行续传，否则会导致无法生产</li>
                            <li>如果有多个作品需要合在一个订单提交的，需要填写作品名称作为标识方便查找一起提
                                交订单</li>
                            <li>点击提交作品以后，请耐心等待，切勿重复点击提交</li>
                        </ul>
                    </div>

                    <div style="display:flex;padding-bottom:20px;" id="upload">
                        <form style="width: 60%;" class="form-horizontal common-form"  method="post"  onsubmit="return false;" autocomplete="off">
                            <input type="hidden" name="save_data" value='{{$save_data}}'>
                            <input type="hidden" id="page" value='{{$page}}'>
                            <div style="margin-right:50px;font-size: 14px">
                                <table style="flex-direction: row;width:500px" class="up-tbl">
                                    <tr>
                                        <td class="tit">商品名称：</td>
                                        <td style="padding: 5px 0">{{$data['prod_name']}}</td>
                                    </tr>
                                    <tr>
                                        <td class="tit">商品属性：</td>
                                        <td style="padding: 5px 0">{{$data['attr_value']}}</td>
                                    </tr>
                                    <tr>
                                        <td class="tit"><span style="color: red;margin-right: 5px">*</span>作品名称：</td>
                                        <td style="padding: 5px 0"><input id="works_name" name="works_name"/></td>
                                    </tr>
                                    @foreach($sizeType as $stk => $stv)
                                        @include('agent.goods.detail._fileUpload',['size_name'=>$stv['size_name'],'file_path'=>'file_path'.$stv['size_type'],'type'=>$stv['size_type'],'uploader'=>'upload'.$stv['size_type'],'cross'=>$stv['size_is_cross']])
                                        {{--<tr>--}}
                                            {{--<td class="tit"><span style="color: red;margin-right: 5px">*</span>{{$stv['size_name']}}上传：</td>--}}
                                            {{--<td style="padding: 5px 0">--}}
                                                {{--<span style="position:relative;">--}}
                                                    {{--<button type="button" id="file-button" class="btn btn-default" style="position: relative;"><i class="fa fa-upload"></i> 上传</button>--}}
                                                {{--</span>--}}
                                                {{--<span style="color:gray;font-size:12px">注：请按规则上传PDF文件</span>--}}
                                                {{--<input type="hidden" name="file_path" id="file_path"/>--}}
                                            {{--</td>--}}
                                        {{--</tr>--}}
                                        {{--<tr>--}}
                                            {{--<td class="tit"></td>--}}
                                            {{--<td>--}}
                                                {{--<div id="filelist"></div>--}}
                                            {{--</td>--}}
                                        {{--</tr>--}}
                                    @endforeach
                                    <tr >
                                        <td></td>
                                        <td>
                                            <input type="hidden" name="isLogin" value="{{$isLogin}}" />
                                            @if(empty($isLogin))
                                                <button id="comfirm" style="margin-top: 30px" class="btn btn-success">提交作品</button>
                                            @else
                                                <button id="addCart" style="margin-top: 30px;background-color: red;color: white;margin-right: 10px" class="btn btn-add-cart">加入购物车</button>
                                                <button id="orderSave" style="margin-top: 30px" class="btn btn-success">立即订购</button>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </form>

                        <div>
                            @foreach($prod_size_data as $key => $value)
                                <div style="display: inline-block;flex-direction: row;border-left:1px solid #f5f5f5;margin:30px 30px 0 20px;" >
                                    <div id="fm" style="width:{{$value['display_width']}}px;height:{{$data['fixed_height']}}px;border:1px solid black;box-sizing: border-box;padding-top:{{$value['real_up']}}px;padding-left:{{$value['real_left']}}px;padding-right:{{$value['real_right']}}px;padding-bottom:{{$value['real_down']}}px;position:relative">
                                        <div style="border:1px solid black;width:100%;height:100%;position:relative">
                                            @if($value['size_is_cross']==1 && ($value['size_type']==1 || $value['size_type']==2))
                                                <div style="border-left:1px solid black;height:100%;position:absolute;top:0px;left:{{$value['sj']-5}}px"></div>
                                                <div style="border-left:1px solid black;height:100%;position:absolute;top:0px;left:{{$value['sj']+5}}px"></div>
                                            @elseif($value['size_is_cross']==1 && $value['size_type']==3)
                                                <div style="border-left:1px dashed black;height:100%;position:absolute;top:0px;left:{{$value['display_width']/2-5}}px"></div>
                                            @endif
                                        </div>
                                        <!--  注释线右方 -->
                                        <div style="color:red;position:absolute;width:25px;top:0px;height:120px;border-top:1px solid red;border-bottom:1px solid red;left:{{$value['display_width']+10}}px">
                                            <div style="border-left:1px solid red;height:50px;margin-left:12px;"></div>
                                            <div style="height:20px;">{{$value['real_height']}}mm</div>
                                            <div style="border-left:1px solid red;height:50px;margin-left:12px;"></div>
                                        </div>
                                    </div>
                                    <!-- 下方注释线  -->
                                    <div style="width:{{$value['display_width']}}px;margin-top:5px;color:red">
                                        <div style="height:25px;border-left:1px solid red; display: inline-block;float: left"></div>
                                        <div style="width:{{$value['display_width']/2-30}}px;border-top:1px solid red;position:relative;top:12px;float: left"></div>
                                        <div style="float: left;position:relative;top:4px;margin-left:10px;">{{$value['real_width']}}mm</div>
                                        <div style="height:25px;border-left:1px solid red;display: inline-block;float: right"></div>
                                        <div style="width:{{$value['display_width']/2-30}}px;border-top:1px solid red;position:relative;top:12px;float: right"></div>
                                    </div>
                                    <div style="clear: both;width:{{$value['display_width']}}px;text-align: center;padding-top:5px;font-size:14px">
                                        @if($value['size_type']==1)封面
                                        @elseif($value['size_type']==2)封面/封底
                                        @elseif($value['size_type']==3)内页
                                        @elseif($value['size_type']==4)封底
                                        @else 特殊页
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>


    </div>
</div>
</div>
<footer class="footer" style="clear:both">

</footer>


</body>
</html>

<script src="{{ URL::asset('assets/layer/src/layer.js')}}"></script>
<script src="{{ URL::asset('js/agent/goods/fileUpload.js')}}"></script>



