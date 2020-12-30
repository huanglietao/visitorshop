<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/advertisement/adposition/save" onsubmit="return false;" autocomplete="off">

        <input type=hidden value="{{$row['ad_pos_id']}}" name="id" id="id">
        <input id="c-channel" class="form-control" name="channel" type="hidden" value="{{$channel}}">
        @if($channel!='all')
            <input id="c-channel" class="form-control" name="channel_id" type="hidden" value="{{$channel}}">
        @endif
        @if($channel=='all')
            <div class="form-group row form-item">
                <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                    <span style="color:red">*</span> 渠道平台：</label>
                <div class="col-xs-12 col-sm-10">
                    <div class="row">
                        <select  class="form-control col-sm-6" id="channel_id" name="channel_id" data-rule="渠道平台:required">
                            <option value="">请选择</option>
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
                <span style="color:red">*</span> 广告位置：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="ad_position" class="form-control col-sm-6" name="ad_position" type="text" value="{{$row['ad_position']}}" placeholder="" data-rule="广告位置:required;length(~30)">
                    <span class="col-sm-6 color-6A6969"> 填写广告投放在哪个位置的名称：如分销首页轮播</span>
                </div>
                <span class="msg-box" style="position:static;" for="ad_position"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 广告标识：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="pos_flag" class="form-control col-sm-6" name="pos_flag" type="text" value="{{$row['pos_flag']}}" data-rule="广告标识:required;length(~30)" @if($row['ad_pos_id']) disabled @endif>
                    <span class="col-sm-6 color-6A6969"> 填写标识，如sy表示首页轮播广告</span>
                </div>
                <span class="msg-box" style="position:static;" for="pos_flag"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">状态：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row radios">
                    <div style="display: inline-block;padding-top: 5px;padding-left: 0" class="col-sm-6">
                        @component('component/radio',['radio'=>[1=>'启用',0=>'禁用'],'name'=>'ad_status','default_key'=>$row['ad_status']??ONE])
                        @endcomponent
                    </div>
                    <span class="col-sm-6 color-6A6969"> 启用：正常使用；禁用：隐藏账号不能使用。</span>
                </div>

                <span class="msg-box" style="position:static;" for="ad_status"></span>
            </div>
        </div>
        <div class="form-group row form-item" style="height: 12%;">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">示意图：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div style="padding-left: 0" class="col-sm-6">
                        @component('component/image_upload',['name'=>'ad_thumb','uploader'=>'uploader','direction'=>1,'browse_btn'=>'form-adthumb','content_class'=>'adthumb','img_format'=>'gif,jpg,jpeg,png','num'=>1,'value'=>$row['ad_thumb']])
                        @endcomponent
                    </div>
                    <span class="col-sm-6 color-6A6969">具体展示的示意图 。格式：gif / jpg / jpeg / png。</span>
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

