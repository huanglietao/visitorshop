<!DOCTYPE html>
<html>
<head>
    <title>模板市场</title>
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
<body style="padding: 0;margin: 0;">
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
    .searh_box i{position:relative;left: 100px;top:-23px;cursor:pointer;}
    .nav-pills > li.active > #imgsort{background-color: #f7f7f7;}
    .nav > li > a:focus{background: #fff;}
    .tabs-wrapper .tabs-group ul{margin: 0 0 0 69px;}
    .nav-pills > li > a {
        border-radius: 0;
        border-top: 0px;}

    .tabs-wrapper .tabs-group {padding: 0 30px;}

</style>
@if($msg!="")
    <div class="no-data" style="display: block;font-size: 16px">{{$msg}}</div>
@else
<nav  role="navigation" style="height: 50px;background-color: black;color: white">
    <div class="container">
        <div  style="line-height: 50px;font-size: 21px">
            <span  style="padding:0">{{$product_info['agent_name']}}</span>
        </div>
    </div>
</nav>
<div class="addon-section">
    <div class="container">
        <div class="tabs-wrapper">
            <!-- S 模板分类列表 -->
            <div class="tabs-group">
                <input hidden type="text" id="prod_id" value="{{$product_info['prod_id']}}"/>
                <input hidden type="text" id="sku_id" value="{{$product_info['sku_id']}}"/>
                <input hidden type="text" id="page_num" value="{{$product_info['prod_p_num']}}"/>
                <input hidden type="text" id="mid" value="{{$product_info['mid']}}"/>
                <input hidden type="text" id="aid" value="{{$product_info['aid']}}"/>
                <input hidden type="text" id="order_no" value="{{$product_info['order_no']}}"/>
                <span style="display: inline-block; margin-left: 10px; margin-top: 10px;font-size: 16px;font-weight: bold;" class="subtitle">{{$product_info['prod_name']}}</span>
                <hr>
                <div class="title" style="width: 69px">模板分类：</div>
                <ul class="list-unstyled list-inline" id="templatelist" style="width: 100%">
                    <li class="active temp_cate temlateLi" data-value="all"><a class="nav-link" href="#">全部模版</a></li>
                    @foreach($category as $key=>$value)
                        {{--@if($key<7)--}}
                            <li class="temp_cate temp temlateLi" data-value="{{$value[0]}}" ><a class="nav-link" href="#">{{$value[1]}}({{$value[2]}})</a></li>
                        {{--@else--}}
                            {{--<li class="temp_cate temp showup temlateLi" data-value="{{$value[0]}}" data_title="h"  style="display: none"><a class="nav-link " href="#">{{$value[1]}}({{$value[2]}})</a></li>--}}
                        {{--@endif--}}
                    @endforeach
                </ul>
                {{--@if(count($category)>7)--}}
                    {{--<div class="show_up">--}}
                        {{--<a class="hit_show_up" href="#" style="color: #5c6269"><span>展示更多</span></a>--}}
                    {{--</div>--}}
                {{--@endif--}}
            </div>
            <!-- E 模板标签分类列表 -->
            <div class="tabs-group" style="display: none;">
                <div class="title" style="width: 69px">标签分类：</div>
                <ul class="list-unstyled list-inline" id="templatetags" >
                    <li class="active maintemp_tags temlateLi" data-value="alltags"><a class="nav-link" href="#">全部标签</a></li>
                    @foreach($tempTags as $tkey=>$tvalue)
                        <li class="temlateLi maintemp_tags" data-value="{{$tvalue['temp_tags_id']}}" ><a class="nav-link" href="#">{{$tvalue['temp_tages_name']}}</a></li>
                    @endforeach
                </ul>
            </div>

        </div>
        <div class="addon-filter" style="padding-bottom: 10px">
            <div class="nav nav-pills tab" style="display: block">
                <ul class="nav nav-pills tab" style="display: block;float: left">
                    {{--<li class="active" style="margin-top: 5px" ><a class="nav-link " href="#" id="imgsort" data-value="0" data-searh="sort_decs"><img src="/assets/img/home/sort.png"/></a></li>--}}
                    <li class="" data-value="" data-searh="" style="margin-top: 5px">
                        <select class="selector" name="selector" style="width:120px;height: 30px">
                            <option value="use_times">按热度</option>
                            <option value="createtime">按上架时间</option>
                        </select>
                    </li>
                </ul>
                <div class="searh_box" style="margin-top: 8px;width:11%;float:right"><input name="temp_name" value=""  class="temp_name" style="width:120px;height: 30px " placeholder=请输入模板名称><i class="fa fa-search"></i></div>
            </div>

        </div>
        <div class="addon-list">
            @if(!$template)
                <div class="no-data" style="display: block">该商品暂无模板数据</div>
            @else
            <div class="no-data" style="display: none">数据加载中....</div>
            <div class="row foreach-con">
                <div id="table">
                    <table class="no-border-table">
                        <tbody class="tbl-content">
                        @foreach($template as $key=>$value)
                            <div class="col-lg-3 row-item foreach-data" data-type="free">
                                <div class="addon-item">
                                    @if ($value['main_temp_use_times'] >= 100)
                                        <div class="tags tags-recommend"></div>
                                    @endif
                                    {{--<div class="tags tags-recommend"></div>--}}
                                    {{--<div class="tags tags-recommend new-tags"></div>--}}
                                    <div class="addon-img">
                                        <a href="{{$value['url']}}" title="{{$value['main_temp_name']}}" target="_blank"> <img style="height: 253px;" src="{{$value['main_temp_thumb']}}" alt="{{$value['main_temp_name']}}" onerror="this.src='{{URL::asset('images/home/moren.jpg')}}'" class="img-responsive"></a>
                                    </div>
                                    <div class="addon-info">
                                        <div class="title">
                                            <a href="{{$value['url']}}" target="_blank" title="{{$value['main_temp_name']}}">{{$value['main_temp_name']}}</a>
                                        </div>
                                        @if(empty($value['min_photo']))
                                            <div class="title" style="min-height: 18px"></div>
                                        @else
                                            <div class="title">
                                                推荐照片数：
                                                <span>{{$value['min_photo']}}张 - </span><span>{{$value['max_photo']}}张</span>
                                            </div>
                                        @endif
                                        <div class="metas clearfix" style="border-top:1px dotted #ddd;margin-top: 7px;">
                                            <a target="_blank" href="{{$value['url']}}" style="text-decoration: none">
                                                <span id="zz" style="cursor:pointer;background: #CBCBCB;padding:6px 15px;font-size:14px;color:#fff;border-radius: 5px;float: left">预览模板</span>
                                            </a>
                                            <a target="_blank" href="{{$value['url']}}"  style="text-decoration: none">
                                                <span id="yl" style="cursor:pointer;background: #4DCE61;padding:6px 15px;font-size:14px;color:#fff;border-radius: 5px;float: right">开始制作</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
            <input hidden id="page" type="text" value="{{$total}}"/>
            @include('component.paginate',['limit'=>16,'pagesList'=>[16,24,32,40]])
            @endif
        </div>
        <div class="panel panel-default" style="padding:0;border:none">
            <div class="fixed-table-pagination" id="fixed-table-pagination" style="box-sizing:none ; text-align: center"></div>
        </div>
    </div>
    </div>
</div>
@endif
<script src="{{ URL::asset('assets/jquery/jquery.min.js')}}"></script>
<script src="{{ URL::asset('js/agent/goods/template.js')}}"></script>
<script type="text/javascript">

</script>
</body>
</html>