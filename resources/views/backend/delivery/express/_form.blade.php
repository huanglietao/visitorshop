<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/delivery/express/save" onsubmit="return false;" autocomplete="off">
        <input type=hidden value="{{$row['express_id']}}" name="express_id" id="id">
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 快递名称：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="express_name" class="form-control col-sm-5" name="express_name" type="text" value="{{$row['express_name']}}" placeholder="" data-rule="快递名称:required">
                    <span class="col-sm-7 color-6A6969"> 请填写快递名称，如：顺丰快递。</span>
                </div>
                <span class="msg-box" style="position:static;" for="express_name"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 快递代号：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="express_code" class="form-control col-sm-5" name="express_code" type="text" value="{{$row['express_code']}}" placeholder="" data-rule="快递代号:required">
                    <span class="col-sm-7 color-6A6969"> 请填写快递代号，如：顺丰快递的代号为sf。</span>
                </div>
                <span class="msg-box" style="position:static;" for="express_code"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">快递类型：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row radio">
                    <div style="display: inline-block;padding-top: 5px;padding-left: 0" class="col-sm-5">
                        @component('component/radio',['radio'=>['1'=>'标准快递','2'=>'商家配送','3'=>'自取'],'name'=>'express_type','default_key'=>$row['express_type']??1])
                        @endcomponent
                    </div>
                    <span class="col-sm-7 color-6A6969"  style="margin-top: 3px"> 请选择快递类型。</span>
                </div>
                <span class="msg-box" style="position:static;" for="express_type"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 图标：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div style="padding-left: 0;height: 108px" class="col-sm-5">
                        @component('component/image_upload',['name'=>'express_logo','uploader'=>'uploader1','num'=>1,'value'=>$row['express_logo'],'direction'=>1,'browse_btn'=>'form-avatar','content_class'=>'background','img_format'=>'jpg,jpeg,png','rule'=>'图标:required'])
                        @endcomponent
                    </div>
                    <span class="col-sm-7 color-6A6969"  style="margin-top: 3px">推荐：150 * 50 PX，大小：100K。格式： jpg / jpeg / png</span>
                </div>
                <span class="msg-box" style="position:static;" for="express_logo"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">描述：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <textarea id="express_desc" class="form-control col-sm-5" name="express_desc" placeholder="" style="height: 100px;resize: none;">{{$row['express_desc']}}</textarea>
                    <span class="col-sm-7 color-6A6969"> 请不要超过200个字符。</span>
                </div>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">状态：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row radio">
                    <div style="display: inline-block;padding-top: 5px;padding-left: 0" class="col-sm-5">
                        @component('component/radio',['radio'=>['1'=>'启用','0'=>'禁用'],'name'=>'express_status','default_key'=>$row['express_status']??1])
                        @endcomponent
                    </div>
                    <span class="col-sm-7 color-6A6969"  style="margin-top: 3px"> 启用：正常；禁用：不能使用</span>
                </div>
                <span class="msg-box" style="position:static;" for="express_status"></span>
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

