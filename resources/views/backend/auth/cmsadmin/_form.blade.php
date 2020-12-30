<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/auth/cmsadmin/save" onsubmit="return false;" autocomplete="off">
    <input type=hidden value="{{$row['cms_adm_id']}}" name="id" id="id">
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 所属角色：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <select  class="form-control col-sm-5" id="cms_adm_group_id" name="cms_adm_group_id" data-rule="角色组:required">
                        @foreach($groupList as $k=>$v)
                            <option value={{$k}} @if($k == $row['cms_adm_group_id']) selected @endif >{!! $v !!}</option>
                        @endforeach
                    </select>
                    <span class="col-sm-7 color-6A6969"> 角色组为一组权限的组合,可单独为管理员设置权限,也可赋予角色权限。</span>
                </div>
                <span class="msg-box" style="position:static;" for="cms_adm_group_id"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2"><span style="color:red">*</span> 用户名：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="cms_adm_username" class="form-control col-sm-5" name="cms_adm_username" type="text" value="{{$row['cms_adm_username']}}" placeholder="" data-rule="用户名:required">
                    <span class="col-sm-7 color-6A6969"> 数字或字母组合,不区分大小写,不支持中文,长度3~15位。</span>
                </div>
                <span class="msg-box" style="position:static;" for="cms_adm_username"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">昵称：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="cms_adm_nickname" class="form-control col-sm-5" name="cms_adm_nickname" type="text" value="{{$row['cms_adm_nickname']}}" placeholder="" data-rule="length(~20)">
                    <span class="col-sm-7 color-6A6969"> 支持中英文,区分大小写,长度2~15位。</span>
                </div>
                <span class="msg-box" style="position:static;" for="cms_adm_nickname"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2"><span style="color:red">*</span> 密码：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="cms_adm_password" class="form-control col-sm-5" name="cms_adm_password" type="password" value="" placeholder="" data-rule="@if (!isset($row['cms_adm_id']))密码:required;password @endif 密码:password">
                    <span class="col-sm-7 color-6A6969">0~9和A~Z组合而成,区分大小写,长度6~16位@if ($row['cms_adm_id']) 不修改密码请留空 @endif</span>
                </div>
                <span class="msg-box" style="position:static;" for="cms_adm_password"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">Email：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="cms_adm_email" class="form-control col-sm-5" name="cms_adm_email" type="text" value="{{$row['cms_adm_email']}}" placeholder="" data-rule="邮箱:email">
                    <span class="col-sm-7 color-6A6969"> 请填写正确邮箱地址,用于接收邮件或其他联系。</span>
                </div>
                <span class="msg-box" style="position:static;" for="cms_adm_email"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">手机号：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="cms_adm_mobile" class="form-control col-sm-5" name="cms_adm_mobile" type="number" value="{{$row['cms_adm_mobile']}}" placeholder="" data-rule="手机号:cms_adm_mobile" data-rule-cms_adm_mobile="[/^[1][3,4,5,7,8,9][0-9]{9}$/, '请输入正确的手机号']" >
                    <span class="col-sm-7 color-6A6969"> 请填写正确的手机号,长度11位。</span>
                </div>
                <span class="msg-box" style="position:static;" for="cms_adm_mobile"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">状态：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row radios">
                    <div style="display: inline-block;padding-top: 5px;padding-left: 0" class="col-sm-5">
                        @component('component/radio',['radio'=>[1=>'启用',0=>'禁用'],'name'=>'cms_adm_status','default_key'=>$row['cms_adm_status']??1])
                        @endcomponent
                    </div>
                    <span class="col-sm-7 color-6A6969"> 启用：正常使用；禁用：隐藏账号不能使用。</span>
                </div>

                <span class="msg-box" style="position:static;" for="cms_adm_status"></span>
            </div>
        </div>
        <div class="form-group row form-item" style="height: 12%;">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">头像：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div style="padding-left: 0" class="col-sm-5">
                        @component('component/image_upload',['name'=>'cms_adm_avatar','uploader'=>'uploader','direction'=>1,'browse_btn'=>'form-avatar','content_class'=>'background','img_format'=>'gif,jpg,jpeg,png','img_size'=>'100kb','num'=>1,'value'=>$row['cms_adm_avatar']])
                        @endcomponent
                    </div>
                    <span class="col-sm-7 color-6A6969"> 推荐：80 * 80 PX,大小：100K。格式：gif / jpg / jpeg / png。</span>
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

