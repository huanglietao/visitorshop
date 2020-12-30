<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/advertisement/adlist/save" onsubmit="return false;" autocomplete="off">

        <input type=hidden value="{{$row['ad_id']}}" name="id" id="id">
        <input id="c-channel" class="form-control" name="channel" type="hidden" value="{{$channel}}">
        @if($channel!='all')
            <input id="c-channel" class="form-control" name="channel_id" type="hidden" value="{{$channel}}">
        @endif

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 标题：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="ad_title" class="form-control col-sm-6" name="ad_title" type="text" value="{{$row['ad_title']}}" placeholder="" data-rule="标题:required">
                    <span class="col-sm-6 color-6A6969"> 支持中英文长度不超过30个字符</span>
                </div>
                <span class="msg-box" style="position:static;" for="ad_title"></span>
            </div>
        </div>

        @if($channel=='all')
            <div class="form-group row form-item">
                <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                    <span style="color:red">*</span> 渠道平台：</label>
                <div class="col-xs-12 col-sm-10">
                    <div class="row">
                        <select  class="form-control col-sm-6" id="channel_id" name="channel_id" data-rule="渠道平台:required">
                            @foreach($channelArr as $k=>$v)
                                <option value={{$k}} @if($k == $row['channel_id']) selected @endif >{!! $v !!}</option>
                            @endforeach
                        </select>
                        <span class="col-sm-6 color-6A6969">选择该广告在哪个渠道使用</span>
                    </div>
                    <span class="msg-box" style="position:static;" for="channel_id"></span>
                </div>
            </div>
        @endif

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 位置说明：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <select  class="form-control col-sm-5" id="ad_position" name="ad_position" data-rule="位置说明:required">
                        @if($row['ad_id'] || $channel!='all')
                            <option value="">请选择</option>
                            @foreach($adPosList as $k=>$v)
                                <option value={{$k}} @if($k == $row['ad_position']) selected @endif >{!! $v !!}</option>
                            @endforeach
                        @else
                            <option value="">请选择</option>
                        @endif
                    </select>
                    <a target="_blank" id="adthumb" type="button" data-area="['40%', '40%']" href="/advertisement/adlist/posthumb?id=@if(!$row['ad_id']){{ZERO}}@else{{$row['ad_position']}}@endif" class="  btn-3F51B5 btn" style="margin-left: 5px;color: #fff">查看示图</a>
                    <span class="col-sm-5 color-6A6969" >该广告在具体投放的位置</span>
                </div>
                <span class="msg-box" style="position:static;" for="ad_position"></span>
            </div>
        </div>
        <div class="form-group row form-item dis_style" style="display: none">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">图片风格：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row radios">
                    <div style="display: inline-block;padding-top: 5px;padding-left: 0" class="col-sm-6">
                        @component('component/radio',['radio'=>[1=>'跟随容器',2=>'固定宽度'],'name'=>'display_type','default_key'=>$row['display_type']??ONE])
                        @endcomponent
                    </div>
                    <span class="col-sm-6 color-6A6969"> 跟随容器：横铺整个浏览器的宽度；固定宽度：图片宽度比较窄，固定在某个位置中间那种</span>
                </div>

                <span class="msg-box" style="position:static;" for="display_type"></span>
            </div>
        </div>

       {{-- <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 广告标识：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="ad_flag" class="form-control col-sm-6" name="ad_flag" type="text" value="{{$row['ad_flag']}}" placeholder="" data-rule="广告标识:required">
                    <span class="col-sm-6 color-6A6969"> 中英文格式，广告标识不能重复</span>
                </div>
                <span class="msg-box" style="position:static;" for="ad_flag"></span>
            </div>
        </div>--}}
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">排序：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="ad_sort" class="form-control col-sm-6" name="ad_sort" type="text" value="{{$row['ad_sort']}}" placeholder="" data-rule="integer(+0);length(~5)">
                    <span class="col-sm-6 color-6A6969"> 正整数如：1</span>
                </div>
                <span class="msg-box" style="position:static;" for="ad_sort"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">跳转链接：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="ad_url" class="form-control col-sm-6" name="ad_url" type="text" value="{{$row['ad_url']}}" placeholder="" data-rule="">
                    <span class="col-sm-6 color-6A6969">若需要指定的跳转网址，填写完整的网址链接如：https://www.baidu.com</span>
                </div>
                <span class="msg-box" style="position:static;" for="ad_url"></span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">广告类型：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row radios">
                    <div style="display: inline-block;padding-top: 5px;padding-left: 0" class="col-sm-6">
                        @component('component/radio',['radio'=>[1=>'单图',2=>'多图'],'name'=>'ad_type','default_key'=>$row['ad_type']??ONE])
                        @endcomponent
                    </div>
                    <span class="col-sm-6 color-6A6969"> 单图：只能传一张图；轮播：可以传多张图。</span>
                </div>

                <span class="msg-box" style="position:static;" for="ad_type"></span>
            </div>
        </div>

        <div class="form-group row form-item" style="height: 12%;">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span>广告图：</label>
            <div class="col-xs-12 col-sm-10">
               {{-- <div class="row pos-special" style="display: none">
                    <div style="padding-left: 0" class="col-sm-6">
                        @component('component/image_upload',['name'=>'ad_images','uploader'=>'uploader','direction'=>1,'browse_btn'=>'form-adthumb','content_class'=>'adthumb','img_format'=>'gif,jpg,jpeg,png','num'=>1,'value'=>$row['ad_images']])
                        @endcomponent
                    </div>
                    <span class="col-sm-6 color-6A6969">格式：gif / jpg / jpeg / png。</span>
                </div>--}}
                <div class="row pos-general">
                    <div style="padding-left: 0" class="col-sm-6">
                        @component('component/image_upload',['name'=>'ad_images','rule'=>'广告图:required','uploader'=>'uploader','direction'=>1,'browse_btn'=>'form-adthumb','content_class'=>'adthumb','img_format'=>'gif,jpg,jpeg,png','num'=>10,'value'=>$row['ad_images']])
                        @endcomponent
                    </div>
                    <span class="col-sm-6 color-6A6969" style="color: red">选择单图且只能传单张，轮播可以传多张。格式：gif / jpg / jpeg / png。</span>
                </div>
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

