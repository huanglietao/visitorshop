<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/templatecenter/tags/save" onsubmit="return false;" autocomplete="off">
    <input type=hidden value="{{$row['temp_tags_id']}}" name="id" id="id">

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 名称：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="temp_tages_name" class="form-control col-sm-7" name="temp_tages_name" type="text" value="{{$row['temp_tages_name']}}" placeholder="" data-rule="名称:required;length(~20)">
                    <span class="col-sm-5 color-6A6969"> 支持中英文，长度不超过20字符</span>
                </div>
                <span class="msg-box" style="position:static;" for="temp_tages_name"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">标识：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="temp_tags_sign" class="form-control col-sm-7" name="temp_tags_sign" type="text" value="{{$row['temp_tags_sign']}}" placeholder="" data-rule="length(~15)">
                    <span class="col-sm-5 color-6A6969"> 支持中英文，长度不超过15字符</span>
                </div>
                <span class="msg-box" style="position:static;" for="temp_tags_sign"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2 pgc-font">描述：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <textarea class="form-control col-sm-7" name="temp_tags_desc" id="" cols="30" rows="5" style="resize: none;">{{$row['temp_tags_desc']}}</textarea>
                    <span class="col-sm-5 color-6A6969"> 最多输入60个汉字。</span>
                </div>
                <span class="msg-box" style="position:static;" for="temp_tags_desc"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">状态：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row radios">
                    <div style="display: inline-block;padding-top: 5px;padding-left: 0" class="col-sm-6">
                        @component('component/radio',['radio'=>[1=>'启用',0=>'禁用'],'name'=>'temp_tags_status','default_key'=>$row['temp_tags_status']??ONE])
                        @endcomponent
                    </div>
                    <span class="col-sm-6 color-6A6969"> 启用：正常使用；禁用：不能使用。</span>
                </div>
                <span class="msg-box" style="position:static;" for="temp_tags_status"></span>
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

