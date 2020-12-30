<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal" id="form-save" method="post" action="/auth/admin/save" autocomplete="off">
        @csrf
        <input type="hidden" name="id" value="{{$row['dms_adm_id']}}">
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span class="control-span">*</span> 管理员账号：
            </label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="username"  class="form-control col-sm-5" name="dms_adm_username" type="text" value="{{$row['dms_adm_username']}}" placeholder="" data-rule="用户名:required">
                    <span class="col-sm-7" style="color: rgba(0, 0, 0, 0.45);margin-top: 3px"> A~Z，0~9，字母或数字，-或_组合，不区分大小写，不支持中文。长度3~32位。</span>
                </div>
                <span class="msg-box" style="position:static;" for="username"></span>
            </div>
        </div>

        {{--<div class="form-group row form-item">--}}
            {{--<label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">所属管理员：</label>--}}
            {{--<div class="col-xs-12 col-sm-10">--}}
                {{--<div class="row">--}}
                    {{--<select  class="col-sm-5">--}}
                        {{--<option value="1">超级管理员</option>--}}
                        {{--<option value="1">一般管理员</option>--}}
                    {{--</select>--}}
                    {{--<span class="col-sm-7" style="color: rgba(0, 0, 0, 0.45);margin-top: 3px"> 选择该项对应账号，则新增管理员为被选中账号的子账号；不选择则为主账号。</span>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span class="control-span">*</span> 真实姓名：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  class="form-control col-sm-5" name="dms_real_name" id="dms_real_name" type="text" value="{{$row['dms_real_name']}}" placeholder="" data-rule="真实姓名:required">
                    <span class="col-sm-7" style="color: rgba(0, 0, 0, 0.45);margin-top: 3px"> 支持中英文。长度6 ~ 12位（2个汉字 ~ 4个汉字）。</span>
                </div>
                <span class="msg-box" style="position:static;" for="dms_real_name"></span>
            </div>
        </div>

        <div class="form-group row form-item">

            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span class="control-span">*</span> Email：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input id="email" class="form-control col-sm-5" name="dms_adm_email" type="text" value="{{$row['dms_adm_email']}}" placeholder="" data-rule="邮箱:required;email">
                    <span class="col-sm-7" style="color: rgba(0, 0, 0, 0.45);margin-top: 3px"> 邮件地址，接收系统通知邮件或其它联系。推荐使用QQ邮箱
                        <a href="https://mail.qq.com" target="_blank" style="margin-left: 10px">申请QQ邮箱</a>
                    </span>

                </div>

                <span class="msg-box" style="position:static;" for="email"></span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">角色组：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <select  class="col-sm-5" name="dms_adm_group_id">
                        @foreach($agtGroupList as $k=>$v)
                            <option value="{{$k}}" @if($k == $row['dms_adm_group_id']) selected @endif>{{$v}}</option>
                        @endforeach
                    </select>
                    <span class="col-sm-7" style="color: rgba(0, 0, 0, 0.45);margin-top: 3px"> 角色组为一组权限的组合。可单独为管理员设置权限，也可赋予角色权限。</span>
                </div>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">状态：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row radios">
                    <div style="display: inline-block;padding-top: 5px;padding-left: 0" class="col-sm-5">
                        @component('component/radio',['radio'=>[PUBLIC_ENABLE=>'启用',PUBLIC_DISABLE=>'禁用',PUBLIC_LOCK=>'锁定'],'name'=>'dms_adm_status','default_key'=>$row['dms_adm_status']??PUBLIC_ENABLE])
                        @endcomponent
                    </div>
                    <span class="col-sm-7" style="color: rgba(0, 0, 0, 0.45);margin-top: 3px"> 启用：正常；禁用：账号不能使用；锁定：暂时不能使用</span>
                </div>
            </div>
        </div>


        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span class="control-span">*</span> 登录密码：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input id="c-mch_name" class="form-control col-sm-5" name="dms_adm_password" type="password" value="" placeholder="">
                    <span class="col-sm-7" style="color: rgba(0, 0, 0, 0.45);margin-top: 3px"> 0~9A~Z和特殊符号组合而成，区分大小写，不支持任意连续的3个字符。长度6~32位，密码不修改则留空</span>
                </div>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span class="control-span">*</span> 确认密码：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input id="c-mch_name" class="form-control col-sm-5" name="confirm_password" type="password" value="" placeholder="">
                    <span class="col-sm-7" style="color: rgba(0, 0, 0, 0.45);margin-top: 3px"> 请重复输入上面的密码，密码不修改则留空</span>
                </div>
            </div>
        </div>

        <div class="form-group row form-item" style="height: 12%;">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">头像：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div style="padding-left: 0" class="col-sm-5">
                        @component('component/image_upload',['name'=>'dms_adm_avatar','direction'=>1,'browse_btn'=>'form-avatar','content_class'=>'background','img_format'=>'jpg,jpeg,png','img_size'=>'300kb','uploader'=>'uploader','num'=>1,'value'=>$row['dms_adm_avatar']])
                        @endcomponent
                    </div>
                    <span class="col-sm-7" style="color: rgba(0, 0, 0, 0.45);margin-top: 3px">推荐：80 * 80 PX，大小：100K。格式：gif / jpg / jpeg / png</span>
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