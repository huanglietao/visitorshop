<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/test/save" onsubmit="return false;" autocomplete="off">
    <input type=hidden value="{{$row['id']}}" name="id" id="id">
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 商户id：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="mid" class="form-control col-sm-5" name="mid" type="text" value="{{$row['mid']}}" placeholder="" data-rule="商户id:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="mid"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 用户名：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="username" class="form-control col-sm-5" name="username" type="text" value="{{$row['username']}}" placeholder="" data-rule="用户名:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="username"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 昵称：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="nickname" class="form-control col-sm-5" name="nickname" type="text" value="{{$row['nickname']}}" placeholder="" data-rule="昵称:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="nickname"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 密码：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="password" class="form-control col-sm-5" name="password" type="text" value="{{$row['password']}}" placeholder="" data-rule="密码:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="password"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 密码盐：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="salt" class="form-control col-sm-5" name="salt" type="text" value="{{$row['salt']}}" placeholder="" data-rule="密码盐:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="salt"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 是否为主账号：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="is_main" class="form-control col-sm-5" name="is_main" type="text" value="{{$row['is_main']}}" placeholder="" data-rule="是否为主账号:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="is_main"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> token标识：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="token" class="form-control col-sm-5" name="token" type="text" value="{{$row['token']}}" placeholder="" data-rule="token标识:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="token"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 头像1：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="avatar" class="form-control col-sm-5" name="avatar" type="text" value="{{$row['avatar']}}" placeholder="" data-rule="头像1:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="avatar"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 创建时间：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="created_at" class="form-control col-sm-5" name="created_at" type="text" value="{{$row['created_at']}}" placeholder="" data-rule="创建时间:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="created_at"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 更新时间：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="updated_at" class="form-control col-sm-5" name="updated_at" type="text" value="{{$row['updated_at']}}" placeholder="" data-rule="更新时间:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="updated_at"></span>
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

