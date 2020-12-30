<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/system/smtp/save" onsubmit="return false;" autocomplete="off">
    <input type=hidden value="{{$row['smtp_id']}}" name="smtp_id" id="id">
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> SMTP地址：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="smtp_address" class="form-control col-sm-5" name="smtp_address" type="text" value="{{$row['smtp_address']}}" placeholder="" data-rule="SMTP地址:required">
                    <span class="col-sm-7 color-6A6969"> 请填写正确的格式。如：smtp.qq.com</span>
                </div>
                <span class="msg-box" style="position:static;" for="smtp_address"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> SMTP端口：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="smtp_port" class="form-control col-sm-5" name="smtp_port" type="number" value="{{$row['smtp_port']}}" placeholder="" data-rule="SMTP端口:required">
                    <span class="col-sm-7 color-6A6969"> 请填写正确的格式。如：3306</span>
                </div>
                <span class="msg-box" style="position:static;" for="smtp_port"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">SMTP用户名：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="smtp_username" class="form-control col-sm-5" name="smtp_username" type="text" value="{{$row['smtp_username']}}" placeholder="">
                    <span class="col-sm-7 color-6A6969"> 请填写正确的格式。如：user.@haoyin.con.cn</span>
                </div>
                <span class="msg-box" style="position:static;" for="smtp_username"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">SMTP密码：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="smtp_password" class="form-control col-sm-5" name="smtp_password" type="password" value="{{$row['smtp_password']}}" placeholder="" data-rule="SMTP密码:length(6~12)">
                    <span class="col-sm-7 color-6A6969"> 请填写密码。</span>
                </div>
                <span class="msg-box" style="position:static;" for="smtp_password"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 发送人：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="sender" class="form-control col-sm-5" name="sender" type="text" value="{{$row['sender']}}" placeholder="" data-rule="发送人:required">
                    <span class="col-sm-7 color-6A6969"> 请填写发送的人员姓名。如：admin</span>
                </div>
                <span class="msg-box" style="position:static;" for="sender"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">连接类型：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row radio">
                    <div style="display: inline-block;padding-top: 5px;padding-left: 0" class="col-sm-5">
                        @component('component/radio',['radio'=>['1'=>'ssl','2'=>'tls'],'name'=>'connecttype','default_key'=>$row['connecttype']?? 1])
                        @endcomponent
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">使用场景：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row radio">
                    <div style="display: inline-block;padding-top: 5px;padding-left: 0" class="col-sm-6">
                        @component('component/radio',['radio'=>['1'=>'内部服务','2'=>'服务器报警','3'=>'客户邮件'],'name'=>'scene','default_key'=>$row['scene']?? 1])
                        @endcomponent
                    </div>
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

