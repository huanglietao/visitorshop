<!DOCTYPE html>
<link rel="stylesheet" href="{{ URL::asset('css/backend/template/tempmain.css') }}">

@extends('layout.iframe')
@section("main-content")
<!-- form表单视图 -->
<!-- 面包屑组件start  -->
@component('component/crumb',['icon' => 'fa-dashboard', 'title' => '模板中心/主模板库' ])
@endcomponent
<!--  提示组件 start -->
<div id="main">
@component('component/tips')
    <p>封面模板是供设计人员选择生成主模板，设计人员通过添加背景、素材及相应布局组成封面模板</p>
@endcomponent

<!-- form表单视图 -->
<div style="margin-top:30px" class="templayui-layer">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/templatecenter/face/save" onsubmit="return false;" autocomplete="off">

        <input type=hidden value="{{$row['cover_temp_id']}}" name="id" id="id">
        <input type=hidden value="{{$tgl}}" name="tgl" id="tgl" disabled="disabled">
        <input type=hidden value="face" name="face_temp" id="face_temp" disabled="disabled">
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 模板名称：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="cover_temp_name" class="form-control col-sm-7" name="cover_temp_name" type="text" value="{{$row['cover_temp_name']}}" data-rule="模板名称:required;length(~30)">
                    <span class="col-sm-5 color-6A6969"> 支持中英文，长度不超过30字符</span>
                </div>
                <span class="msg-box" style="position:static;" for="cover_temp_name"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span>商品分类：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <select  class="form-control col-sm-7" id="goods_type_id" name="goods_type_id" @if(!$row['cover_temp_id']) data-rule="商品分类:required" @endif  @if($row['cover_temp_id']) disabled @endif >
                        @foreach($goodsCateType as $k=>$v)
                            <option data-flag="{{$goodsFlag[$k]}}" value={{$k}} @if($k == $row['goods_type_id']) selected @endif >{!! $v !!}</option>
                        @endforeach
                    </select>
                    @if($row['cover_temp_id'])
                        <input type=hidden value="{{$row['goods_type_id']}}" name="goods_type_id" >
                    @endif
                    <span class="col-sm-5 color-6A6969"> 选择所属商品分类</span>
                </div>
                <span class="msg-box" style="position:static;" for="goods_type_id"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 产品规格：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <select  class="form-control col-sm-6" id="specifications_id" name="specifications_id"  @if(!$row['cover_temp_id'])data-rule="产品规格:required" @endif @if($row['cover_temp_id']) disabled @endif >
                        @foreach($specLink as $k=>$v)
                            <option value={{$k}} @if($k == $row['specifications_id']) selected @endif >{!! $v !!}</option>
                        @endforeach
                    </select>
                    @if($row['cover_temp_id'])
                        <input type=hidden value="{{$row['specifications_id']}}" name="specifications_id" >
                    @endif
                    <a id="spec-params" class="btn btn-default btn-dialog" data-title = "查看规格参数" data-area="['45%', '65%']" data-url="@if(!$row['cover_temp_id']){{URL::asset('/templatecenter/face/specdetail')}}?id={{$default_spec[0]}} @else{{URL::asset('/templatecenter/face/specdetail')}}?id={{$row['specifications_id']}}@endif" title="规格参数"  type="button"  >规格参数</a>
                    <span class="col-sm-5 color-6A6969" style="color: red">查看规格参数是否已设置，未设置则无法制作模板</span>
                </div>
                <span class="msg-box" style="position:static;" for="specifications_id"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 模板分类：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <select  class="form-control col-sm-7" id="cover_temp_theme_id" name="cover_temp_theme_id" data-rule="所属分类:required">
                        <option value= "">请选择</option>
                        @foreach($tempThemeList as $k=>$v)
                            <option value={{$k}} @if($k == $row['cover_temp_theme_id']) selected @endif >{!! $v !!}</option>
                        @endforeach
                    </select>
                    <span class="col-sm-5 color-6A6969"> 选择模板分类</span>
                </div>
                <span class="msg-box" style="position:static;" for="cover_temp_theme_id"></span>
            </div>
        </div>
        <div class="form-group row form-item" id="calendar-year" style="@if($cateFlag==$tgl) display:flex @else display:none @endif">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">起始年份：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <select  class="form-control col-sm-7" id="cover_temp_start_year" name="cover_temp_start_year" >
                        <option value= "">请选择</option>
                        @foreach($yearList as $k=>$v)
                            <option  value={{$k}} @if($k == $row['cover_temp_start_year']) selected @endif >{!! $v !!}</option>
                        @endforeach
                    </select>
                    <span class="col-sm-5 color-6A6969"> 选择起始年份</span>
                </div>
                <span class="msg-box" style="position:static;" for="cover_temp_start_year"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">排序：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="cover_temp_sort" class="form-control col-sm-7" name="cover_temp_sort" type="text" value="{{$row['cover_temp_sort']}}" placeholder="" data-rule="integer(+0);length(~5)">
                    <span class="col-sm-5 color-6A6969"> 填写正整数，如：1</span>
                </div>
                <span class="msg-box" style="position:static;" for="cover_temp_sort"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">简介：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="cover_temp_desc" class="form-control col-sm-7" name="cover_temp_desc" type="text" value="{{$row['cover_temp_desc']}}" data-rule="length(~100)">
                    <span class="col-sm-5 color-6A6969"> 支持中英文</span>
                </div>
                <span class="msg-box" style="position:static;" for="cover_temp_desc"></span>
            </div>
        </div>

        <div class="form-group row form-item" >
            <label style=" font-weight: normal;" for="c-mch_name" class="control-label col-xs-12 col-sm-2">示意图：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    @component('component/imgUpload',['plid'=>"thumbImg",'parms'=>'thumb','name'=>'cover_temp_thumb','uploader'=>'thumbUpoad','direction'=>1,'browse_btn'=>'btn-thumb','content_class'=>'faceThumb','img_format'=>'gif,jpg,jpeg,png','num'=>1,'value'=>$row['cover_temp_thumb']])
                    @endcomponent
                    <span class="col-sm-5 color-6A6969"  style="margin-top: 5px">建议尺寸:300*300，大小：300K。格式： jpg / jpeg / png</span>
                </div>
            </div>
            <div class="plupload-images-box bgoundimg-box" style="width: 100%;padding-top: 10px;display: flex">
                <label style=" font-weight: normal;line-height: 85px;" for="c-mch_name" class="control-label col-xs-12 col-sm-2"></label>
                <div class="col-xs-12 col-sm-10 images-preview" style="padding-left: 8px"></div>
            </div>
        </div>


        <div class="form-group row form-item" >
            <label style=" font-weight: normal;" for="c-mch_name" class="control-label col-xs-12 col-sm-2">模板背景：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    @component('component/imgUpload',['plid'=>"backgroundImg",'parms'=>'{"type":"background","page_type":"1","uniqid":"'.$uniqid.'","m_type":"1"}','plurl'=>$apiurl.'/material/upload','name'=>'background','uploader'=>'bgoundUpload','direction'=>1,'browse_btn'=>'btn-bground','content_class'=>'faceBackground','img_format'=>'gif,jpg,jpeg,png','num'=>100,'value'=>$row['background']])
                    @endcomponent
                        <span id="have" style="color:red;margin-top: 5px">
                             @if($backPx['width']==ZERO)
                                <span class="width-p" style="display: none">0</span><span>请选择先添加规格参数</span>
                            @else
                                &nbsp; (不含书脊尺寸:<span class="width-p"> {{$backPx['width']}}</span>px*<span class="height-p">{{$backPx['height']}}</span>px)
                            @endif
                        </span>
                        <span id="back-tips" style="color:red;margin-top: 5px"></span>
                </div>
            </div>
            <div class="plupload-images-box bgoundimg-box" style="width: 100%;padding-top: 10px;display: flex">
                <label style=" font-weight: normal;line-height: 85px;" for="c-mch_name" class="control-label col-xs-12 col-sm-2"></label>
                <div class="col-xs-12 col-sm-10 images-preview" style="padding-left: 8px"></div>
            </div>
        </div>
        <div class="form-group row form-item" >
            <label style=" font-weight: normal;" for="c-mch_name" class="control-label col-xs-12 col-sm-2">模板装饰：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    @component('component/imgUpload',['plid'=>"decorateImg",'parms'=>'{"type":"decorate","page_type":"1","uniqid":"'.$uniqid.'","m_type":"2"}','plurl'=>$apiurl.'/material/upload','name'=>'decorate','uploader'=>'decorateUpload','direction'=>1,'browse_btn'=>'btn-decorate','content_class'=>'faceDecorate','img_format'=>'gif,jpg,jpeg,png','num'=>100,'value'=>$row['decorate']])
                    @endcomponent
                    <span class="col-sm-5 color-6A6969"  style="margin-top: 5px">格式： jpg / jpeg / png</span>
                </div>
            </div>
            <div class="plupload-images-box bgoundimg-box" style="width: 100%;padding-top: 10px;display: flex">
                <label style=" font-weight: normal;line-height: 85px;" for="c-mch_name" class="control-label col-xs-12 col-sm-2"></label>
                <div class="col-xs-12 col-sm-10 images-preview" style="padding-left: 8px"></div>
            </div>
        </div>
        <div class="form-group row form-item" >
            <label style=" font-weight: normal;" for="c-mch_name" class="control-label col-xs-12 col-sm-2">模板画框：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    @component('component/imgUpload',['plid'=>"frameImg",'parms'=>'{"type":"frame","page_type":"1","uniqid":"'.$uniqid.'","m_type":"3"}','plurl'=>$apiurl.'/material/upload','name'=>'frame','uploader'=>'frameUpload','direction'=>1,'browse_btn'=>'btn-frame','content_class'=>'faceFrame','img_format'=>'gif,jpg,jpeg,png','num'=>100,'value'=>$row['frame']])
                    @endcomponent
                    <span class="col-sm-5 color-6A6969"  style="margin-top: 5px;color: red">画框上传时需成对上传，命名规则为1.png和1_mask.png算为一组</span>
                </div>
            </div>
            <div class="plupload-images-box bgoundimg-box" style="width: 100%;padding-top: 10px;display: flex">
                <label style=" font-weight: normal;line-height: 85px;" for="c-mch_name" class="control-label col-xs-12 col-sm-2"></label>
                <div class="col-xs-12 col-sm-10 images-preview" style="padding-left: 8px"></div>
            </div>
        </div>


    </form>
    <div class="form-group  temp-layer-footer" style="text-align: center; margin-top: 5%">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button type="submit" class="btn btn-primary btn-3F51B5 btn-sure btn-temp-submit">确定</button>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <button type="reset" class="btn btn-write btn-reset">重置</button>
        </div>
    </div>
</div>
</div>
@endsection
@section("js-file")
    <script src="{{ URL::asset('js/backend/tempcenter/facetemp.js')}}"></script>
@endsection