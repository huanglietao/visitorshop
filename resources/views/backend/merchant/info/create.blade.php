<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/merchant/account/save" onsubmit="return false;" autocomplete="off">
        <input type="hidden" value="{{$mch_id}}" name="mch_id">
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 商户所属角色：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <select  class="form-control col-sm-7" id="oms_adm_group_id" name="oms_adm_group_id" class="form-control" data-rule="商户角色组:required">
                        @foreach($groupList as $k=>$v)
                            <option value={{$k}}>{{ $v}}</option>
                        @endforeach
                    </select>
                    <span class="col-sm-5 color-6A6969">角色组为一组权限的组合,可单独为管理员设置权限,也可赋予角色权限。</span>
                </div>
                <span class="msg-box" style="position:static;" for="oms_adm_group_id"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 商户账号：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="oms_adm_username" class="form-control col-sm-7" name="oms_adm_username" type="text" placeholder="" data-rule="商户账号:required">
                    <span class="col-sm-5 color-6A6969">数字或字母组合,不区分大小写,不支持中文,长度3~15位。</span>
                </div>
                <span class="msg-box" style="position:static;" for="oms_adm_username"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 密码：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="oms_adm_password" class="form-control col-sm-7" name="oms_adm_password" type="password" value="" placeholder="" data-rule="@if (!isset($row['oms_adm_id']))密码:required;password @endif">
                    <span class="col-sm-5 color-6A6969">商户登录使用密码</span>
                </div>
                <span class="msg-box" style="position:static;" for="oms_adm_password"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">昵称：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="oms_adm_nickname" class="form-control col-sm-7" name="oms_adm_nickname" type="text" placeholder="" >
                    <span class="col-sm-5 color-6A6969">支持中英文,区分大小写,长度2~15位。</span>
                </div>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal;line-height: 85px;" for="c-mch_name" class="control-label col-xs-12 col-sm-2">头像：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div style="padding-left: 0;height: 108px" class="col-sm-7">
                        @component('component/image_upload',['name'=>'oms_adm_avatar','direction'=>1,'browse_btn'=>'form-avatar','content_class'=>'background','img_format'=>'jpg,jpeg,png','uploader'=>'uploader','num'=>1,'value'=>''])
                        @endcomponent
                    </div>
                    <span class="col-sm-5 color-6A6969"  style="margin-top: 30px">建议尺寸:90*90，格式：JPG/JPEG/PNG格式， 文件大小：10M</span>
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">状态：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 6px;">
                <div class="row">
                    @component('component/radio',['radio'=>[PUBLIC_ENABLE=>'启用',PUBLIC_DISABLE=>'禁用'],'name'=>'oms_adm_status','default_key'=>PUBLIC_ENABLE])
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

