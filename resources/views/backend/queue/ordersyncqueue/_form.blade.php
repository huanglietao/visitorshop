<!DOCTYPE html>
<link rel="stylesheet" href="{{ URL::asset('assets/umeditor/themes/default/css/umeditor.css') }}">

<!-- form表单视图 -->

<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/article/list/save" onsubmit="return false;" autocomplete="off">

        <input type=hidden value="{{$row['art_id']}}" name="id" id="id">

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 文章标题：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="art_title" class="form-control col-sm-7" name="art_title" type="text" value="{{$row['art_title']}}" placeholder="" data-rule="文章标题:required">
                    <span class="col-sm-5 color-6A6969">支持中英文，长度不超过30个字符</span>
                </div>
                <span class="msg-box" style="position:static;" for="art_title"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 所属分类：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <select  class="form-control col-sm-7" id="art_type" name="art_type" data-rule="所属分类:required">
                        <option value="">请选择</option>
                        @foreach($artTypeList as $k=>$v)
                            <option value={{$k}} @if($k == $row['art_type']) selected @endif >{!! $v !!}</option>
                        @endforeach
                    </select>
                    <span class="col-sm-5 color-6A6969">选择该文章归属哪个分类</span>
                </div>
                <span class="msg-box" style="position:static;" for="art_type"></span>
            </div>
        </div>

     {{--   <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 所属渠道：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  class="form-control col-sm-7"  type="text"  disabled id="channel_id" value="{{$row['cha_name']}}">
                    <input  id="channel_id" class="form-control col-sm-7" name="channel_id" type="hidden" value="{{$row['channel_id']}}">
                    <span class="col-sm-5 color-6A6969"> 所属渠道跟分类已关联，若要更改请去文章分类修改</span>
                </div>
                <span class="msg-box" style="position:static;" for="channel_id"></span>
            </div>
        </div>--}}
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 标识：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  class="form-control col-sm-7"  type="text"  disabled id="art_sign" value="{{$row['art_sign']}}">
                    <input  id="art_sign" class="form-control col-sm-7" name="art_sign" type="hidden" value="{{$row['art_sign']}}">
                    <span class="col-sm-5 color-6A6969">标识已在文章分类中定义后不可修改</span>
                </div>
                <span class="msg-box" style="position:static;" for="art_sign"></span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 作者：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="art_author" class="form-control col-sm-7" name="art_author" type="text" value="{{$row['art_author']}}" placeholder="" data-rule="作者:required">
                    <span class="col-sm-5 color-6A6969"> 填写发布该文章的作者</span>
                </div>
                <span class="msg-box" style="position:static;" for="art_author"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">关键字：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="art_keywords" class="form-control col-sm-7" name="art_keywords" type="text" value="{{$row['art_keywords']}}" >
                    <span class="col-sm-5 color-6A6969"> 如：重要，关键等</span>
                </div>
                <span class="msg-box" style="position:static;" for="art_keywords"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">摘要：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="art_intro" class="form-control col-sm-7" name="art_intro" type="text" value="{{$row['art_intro']}}">
                    <span class="col-sm-5 color-6A6969"> 填写文章内容的主要信息简介</span>
                </div>
                <span class="msg-box" style="position:static;" for="art_intro"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">是否发布：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row radios">
                    <div style="display: inline-block;padding-top: 5px;padding-left: 0" class="col-sm-7">
                        @component('component/radio',['radio'=>[1=>'是',0=>'否'],'name'=>'is_open','default_key'=>$row['is_open']??ONE])
                        @endcomponent
                    </div>
                    <span class="col-sm-5 color-6A6969"> 是：正常使用中；否：未发布使用。</span>
                </div>

                <span class="msg-box" style="position:static;" for="ad_status"></span>
            </div>
        </div>

        <div class="form-group row form-item" style="height: 12%;">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">缩略图：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row pos-general">
                    <div style="padding-left: 0" class="col-sm-6">
                        @component('component/image_upload',['name'=>'art_thumb','uploader'=>'uploader','direction'=>1,'browse_btn'=>'form-adthumb','content_class'=>'adthumb','img_format'=>'gif,jpg,jpeg,png','num'=>1,'value'=>$row['art_thumb']])
                        @endcomponent
                    </div>
                    <span class="col-sm-6 color-6A6969" > 推荐尺寸：150 * 150 px。格式：gif / jpg / jpeg / png。</span>
                </div>
            </div>
        </div>
        <div class="form-group row form-item" style="margin: 80px 20px 0px">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">文章详情：</label>
            <div class="col-xs-12 col-sm-10">
             <script id="art_content" name="art_content" type="text/plain" style="width:100%;height:500px;">{!! $row['art_content'] !!}</script>

              </div>
        </div>

    </form>

    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button type="submit" class="btn btn-primary btn-3F51B5 btn-sure btn-submit">确定</button>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <button type="reset" class="btn btn-write btn-reset">重置</button>
        </div>
    </div>
</div>



<script src="{{ URL::asset('assets/umeditor/umeditor.config.js')}}"></script>
<script src="{{ URL::asset('assets/umeditor/umeditor.js')}}"></script>
<script src="{{ URL::asset('assets/umeditor/lang/zh-cn/zh-cn.js')}}"></script>
<script src="{{ URL::asset('js/backend/umedit.js')}}"></script>
