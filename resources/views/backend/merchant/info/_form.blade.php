<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/merchant/info/save" onsubmit="return false;" autocomplete="off">
        <input type=hidden value="{{$row['mch_id']}}" name="id" id="id">
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 商家名称：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="mch_name" class="form-control col-sm-7" name="mch_name" type="text" value="{{$row['mch_name']}}" placeholder="" data-rule="商家名称:required">
                </div>
                <span class="msg-box" style="position:static;" for="mch_name"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 联系人：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="mch_link_name" class="form-control col-sm-7" name="mch_link_name" type="text" value="{{$row['mch_link_name']}}" placeholder="" data-rule="联系人:required">
                </div>
                <span class="msg-box" style="position:static;" for="mch_link_name"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 手机号：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="mch_mobile" class="form-control col-sm-7" name="mch_mobile" type="text" value="{{$row['mch_mobile']}}" placeholder="" data-rule="手机号:required">
                </div>
                <span class="msg-box" style="position:static;" for="mch_mobile"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                座机：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="mch_telphone" class="form-control col-sm-7" name="mch_telphone" type="text" value="{{$row['mch_telphone']}}" placeholder="">
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                电子邮箱：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="mch_email" class="form-control col-sm-7" name="mch_email" type="text" value="{{$row['mch_email']}}" placeholder="">
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                微信号：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="mch_wechat" class="form-control col-sm-7" name="mch_wechat" type="text" value="{{$row['mch_wechat']}}" placeholder="">
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">地区：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    @component('component.areas',['province_value'=>$row['province'],'city_value'=>$row['city'],'areas_value'=>$row['district']])@endcomponent
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal;line-height: 80px;" for="c-mch_name" class="control-label col-xs-12 col-sm-2">详细地址：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <textarea class="form-control col-sm-7" style="resize: none;height: 100px;" name="address">{{$row['address']}}</textarea>
                </div>
                <span class="msg-box" style="position:static;" for="address"></span>

            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                公司注册时间：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="mch_cmp_regdate" class="form-control col-sm-7" name="mch_cmp_regdate" type="text" value="{{$row['mch_cmp_regdate']}}" placeholder="">
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                公司经营范围：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="mch_cmp_scope" class="form-control col-sm-7" name="mch_cmp_scope" type="text" value="{{$row['mch_cmp_scope']}}" placeholder="">
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                公司注册资本：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input class="form-control col-sm-6" style="width: 90%;margin-bottom: 5px;" name="mch_cmp_regmoney" value="{{$row['mch_cmp_regmoney']}}"/>
                    <span class="col-sm-1" style="height:30px;line-height:30px;border: 1px solid #ced4da;text-align: center;border-left: 1px solid white">万元</span>
                </div>
                <span class="msg-box" style="position:static;" for="mch_cmp_regmoney"></span>

            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal;line-height: 80px" for="c-mch_name" class="control-label col-xs-12 col-sm-2">公司详细地址：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <textarea class="form-control col-sm-7" style="resize: none;height: 100px;" name="mch_cmp_address">{{$row['mch_cmp_address']}}</textarea>
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal;line-height: 85px;" for="c-mch_name" class="control-label col-xs-12 col-sm-2">公司logo：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div style="padding-left: 0;height: 108px" class="col-sm-7">
                        @component('component/image_upload',['name'=>'mch_cmp_logo','direction'=>1,'browse_btn'=>'form-avatar','content_class'=>'background','img_format'=>'jpg,jpeg,png','uploader'=>'uploader','num'=>1,'value'=>$row['mch_cmp_logo']])
                        @endcomponent
                    </div>
                    <span class="col-sm-5 color-6A6969"  style="margin-top: 30px">建议尺寸:120*120，大小：10M。格式： jpg / jpeg / png</span>
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal;line-height: 85px;" for="c-mch_name" class="control-label col-xs-12 col-sm-2">资质证书图片：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div style="padding-left: 0;height: 108px" class="col-sm-7">
                        @component('component/image_upload',['name'=>'mch_cmp_certificate','direction'=>1,'browse_btn'=>'form-avatar1','content_class'=>'background1','img_format'=>'jpg,jpeg,png','uploader'=>'uploader1','num'=>1,'value'=>$row['mch_cmp_certificate']])
                        @endcomponent
                    </div>
                    <span class="col-sm-5 color-6A6969"  style="margin-top: 30px">建议尺寸:120*120，大小：10M。格式： jpg / jpeg / png</span>
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal;line-height: 85px;" for="c-mch_name" class="control-label col-xs-12 col-sm-2">实景图：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div style="padding-left: 0;height: 108px" class="col-sm-7">
                        @component('component/image_upload',['name'=>'mch_cmp_img','direction'=>1,'browse_btn'=>'form-avatar2','content_class'=>'background2','img_format'=>'jpg,jpeg,png','uploader'=>'uploader2','num'=>1,'value'=>$row['mch_cmp_img']])
                        @endcomponent
                    </div>
                    <span class="col-sm-5 color-6A6969"  style="margin-top: 30px">建议尺寸:120*120，大小：10M。格式： jpg / jpeg / png</span>
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal;line-height: 80px" for="c-mch_name" class="control-label col-xs-12 col-sm-2">公司简介：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <textarea class="form-control col-sm-7" style="resize: none;height: 100px;" name="mch_cmp_desc">{{$row['mch_cmp_desc']}}</textarea>
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

