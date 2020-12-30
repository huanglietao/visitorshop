<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/templatelayout/type/save" onsubmit="return false;" autocomplete="off">
    <input type=hidden value="{{$row['temp_layout_type_id']}}" name="id" id="id">

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 版式名称：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="temp_layout_type_name" class="form-control col-sm-5" name="temp_layout_type_name" type="text" value="{{$row['temp_layout_type_name']}}" placeholder="" data-rule="版式名称:required">
                    <span class="col-sm-7 color-6A6969"> 中英文，长度不超过20字符。</span>
                </div>
                <span class="msg-box" style="position:static;" for="temp_layout_type_name"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">版式简述：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="temp_layout_type_intro" class="form-control col-sm-5" name="temp_layout_type_intro" type="text" value="{{$row['temp_layout_type_intro']}}" placeholder="" data-rule="length(~100)">
                    <span class="col-sm-7 color-6A6969"> 中英文，长度不超过100字符。</span>
                </div>
                <span class="msg-box" style="position:static;" for="temp_layout_type_intro"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">状态：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row radios">
                    <div style="display: inline-block;padding-top: 5px;padding-left: 0" class="col-sm-5">
                        @component('component/radio',['radio'=>[1=>'启用',0=>'禁用'],'name'=>'temp_layout_type_status','default_key'=>$row['temp_layout_type_status']??1])
                        @endcomponent
                    </div>
                    <span class="col-sm-7 color-6A6969"> 启用：正常使用；禁用：隐藏不能使用。</span>
                </div>

                <span class="msg-box" style="position:static;" for="cms_adm_status"></span>
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

