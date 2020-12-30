<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/auth/kfusers/save" onsubmit="return false;" autocomplete="off">
        <input type=hidden value="{{$row['id']}}" name="id" id="id">
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 角色组：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <select  class="form-control col-sm-5">
                        <option value="1">系统默认组</option>
                    </select>
                    <span class="col-sm-7 color-6A6969"> 角色组为一组权限的组合,可单独为管理员设置权限,也可赋予角色权限。</span>
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 用户名：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="username" class="form-control col-sm-5" name="username" type="text" value="{{$row['username']}}" placeholder="" data-rule="用户名:required;username" data-rule-username="[/^[\w\d]{3,15}$/,'数字或字母组合,不区分大小写,不支持中文,长度3-15位']">
                    <span class="col-sm-7 color-6A6969"> 数字或字母组合,不区分大小写,不支持中文,长度3~15位。</span>
                </div>
                <span class="msg-box" style="position:static;" for="username"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">昵称：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="nickname" class="form-control col-sm-5" name="nickname" type="text" value="{{$row['nickname']}}" placeholder="">
                    <span class="col-sm-7 color-6A6969"> 支持中英文,长度2~15位。</span>
                </div>
                <span class="msg-box" style="position:static;" for="nickname"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 密码：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="password" class="form-control col-sm-5" name="password" type="password" value="" placeholder="" data-rule="@if (!isset($row['id']))密码:required;password @endif 密码:password">
                    <span class="col-sm-7 color-6A6969"> 0~9和A~Z组合而成,区分大小写,长度6~16位@if ($row['id']) 不修改密码请留空 @endif</span>
                </div>
                <span class="msg-box" style="position:static;" for="password"></span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">邮箱：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="email" class="form-control col-sm-5" name="email" type="text" value="{{$row['email']}}" placeholder="" data-rule="邮箱:email">
                    <span class="col-sm-7 color-6A6969"> 请填写正确邮箱地址,用于接收邮件或其他联系。</span>
                </div>
                <span class="msg-box" style="position:static;" for="email"></span>
            </div>
        </div>






        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2"><span style="color:red">*</span>状态：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row radios">
                    <div style="display: inline-block;padding-top: 5px;padding-left: 0" class="col-sm-5">
                        @component('component/radio',['radio'=>['normal'=>'启用','hidden'=>'禁用'],'name'=>'status','default_key'=>$row['status']])
                        @endcomponent
                    </div>
                    <span class="col-sm-7 color-6A6969"> 启用：正常使用；禁用：隐藏账号不能使用。</span>
                </div>
            </div>
        </div>
        <div class="form-group row form-item" style="height: 12%;">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">头像：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div style="padding-left: 0" class="col-sm-5">
                        @component('component/image_upload',['name'=>'avatar','direction'=>1,'browse_btn'=>'form-avatar','content_class'=>'background','img_format'=>'gif,jpg,jpeg,png','img_size'=>'100kb'])
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

