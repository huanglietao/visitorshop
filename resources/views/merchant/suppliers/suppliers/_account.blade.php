<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/suppliers/suppliers/account_save" onsubmit="return false;" autocomplete="off">
    <input type=hidden value="{{$row['scm_adm_id']}}" name="id" id="scm_adm_id">
    <input type=hidden value="{{$sp_id}}" name="sp_id" id="sp_id">

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 账号：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="scm_adm_username" class="form-control col-sm-5" name="scm_adm_username" type="text" value="{{$row['scm_adm_username']}}" placeholder="" data-rule="账号:required">
                    <span class="col-sm-7 color-6A6969"> 数字或字母组合,不区分大小写,不支持中文,长度3~15位。</span>
                </div>
                <span class="msg-box" style="position:static;" for="scm_adm_username"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 密码：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="scm_adm_password" class="form-control col-sm-5" name="scm_adm_password" type="text" value="" placeholder="" data-rule="@if (!isset($row['scm_adm_id']))密码:required;password @endif">
                    <span class="col-sm-7 color-6A6969"> 供货商户登录使用密码@if ($row['scm_adm_id']) ,不修改密码请留空。  @endif</span>
                </div>
                <span class="msg-box" style="position:static;" for="scm_adm_password"></span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">昵称：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="scm_adm_nickname" class="form-control col-sm-5" name="scm_adm_nickname" type="text" value="{{$row['scm_adm_nickname']}}" placeholder="" >
                    <span class="col-sm-7 color-6A6969">支持中英文,区分大小写,长度2~15位。</span>
                </div>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal;line-height: 85px;" for="c-mch_name" class="control-label col-xs-12 col-sm-2">头像：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div style="padding-left: 0;height: 108px" class="col-sm-5">
                        @component('component/image_upload',['name'=>'scm_adm_avatar','direction'=>1,'browse_btn'=>'form-avatar','content_class'=>'background','img_format'=>'jpg,jpeg,png','uploader'=>'uploader','num'=>1,'value'=>$row['scm_adm_avatar']])
                        @endcomponent
                    </div>
                    <span class="col-sm-7 color-6A6969"  style="margin-top: 30px">建议尺寸:90*90，格式：JPG/JPEG/PNG格式， 文件大小：100Kb</span>
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">手机号码：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="scm_adm_mobile" class="form-control col-sm-5" name="scm_adm_mobile" type="text" value="{{$row['scm_adm_mobile']}}" placeholder="" data-rule="手机号:scm_adm_mobile" data-rule-scm_adm_mobile="[/^[1][3,4,5,7,8,9][0-9]{9}$/, '请输入正确的手机号']">
                    <span class="col-sm-7 color-6A6969">请填写正确的手机号,长度11位。</span>
                </div>
                <span class="msg-box" style="position:static;" for="scm_adm_mobile"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">邮箱：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="scm_adm_email" class="form-control col-sm-5" name="scm_adm_email" type="text" value="{{$row['scm_adm_email']}}" placeholder="" >
                    <span class="col-sm-7 color-6A6969">请填写正确邮箱地址,用于接收邮件或其他联系。</span>
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">状态：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 6px;">
                <div class="row">
                    @component('component/radio',['radio'=>['1'=>'启用','0'=>'禁用'],'name'=>'scm_adm_status','default_key'=>$row['scm_adm_status']??'1'])
                    @endcomponent
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

