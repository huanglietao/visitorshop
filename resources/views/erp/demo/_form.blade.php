<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal" id="form-save" method="post" action="/demo/save" autocomplete="off">
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 管理员账号：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="username" class="form-control col-sm-5" name="username" type="text" value="" placeholder="" data-rule="用户名:required">
                    <span class="col-sm-7 color-6A6969"> A~Z，0~9，字母或数字，-或_组合，不区分大小写，不支持中文。长度3~32位。</span>
                </div>
                <span class="msg-box" style="position:static;" for="username"></span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 真实姓名：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  class="form-control col-sm-5" name="nickname" id="nickname" type="text" value="" placeholder="" data-rule="真实姓名:required">
                    <span class="col-sm-7 color-6A6969"> 支持中英文。长度6 ~ 12位（2个汉字 ~ 4个汉字）。</span>
                </div>
                <span class="msg-box" style="position:static;" for="nickname"></span>
            </div>
        </div>

        <div class="form-group row form-item">

            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> Email：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input id="email" class="form-control col-sm-5" name="email" type="text" value="" placeholder="" data-rule="required;email">
                    <span class="col-sm-7 color-6A6969"> 邮件地址，接收系统通知邮件或其它联系。推荐使用QQ邮箱</span>
                </div>
                <span class="msg-box" style="position:static;" for="email"></span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 角色组：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <select  class="form-control col-sm-5">
                        <option value="1">超级管理员</option>
                        <option value="1">一般管理员</option>
                    </select>
                    <span class="col-sm-7 color-6A6969"> 角色组为一组权限的组合。可单独为管理员设置权限，也可赋予角色权限。</span>
                </div>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 状态：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input id="c-mch_name" class="form-control col-sm-5" name="status" type="text" value="" placeholder="">
                    <span class="col-sm-7 color-6A6969"> 启用：正常；禁用：账号不能使用；锁定：暂时不能使用</span>
                </div>
            </div>
        </div>


        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 登录密码：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input id="c-mch_name" class="form-control col-sm-5" name="password" type="text" value="" placeholder="">
                    <span class="col-sm-7 color-6A6969"> 0~9A~Z和特殊符号组合而成，区分大小写，不支持任意连续的3个字符。长度6~32位</span>
                </div>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 确认密码：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input id="c-mch_name" class="form-control col-sm-5" name="req_password" type="text" value="" placeholder="">
                    <span class="col-sm-7 color-6A6969"> 请重复输入上面的密码</span>
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

