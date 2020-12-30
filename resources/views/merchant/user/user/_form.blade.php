<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/user/user/save" onsubmit="return false;" autocomplete="off">
    <input type=hidden value="{{$row['user_id']}}" name="user_id" id="user_id">
    <input type=hidden value="{{$row['mch_id']}}" name="mch_id" id="mch_id">
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 会员等级：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <select id="cust_lv_id" class="form-control col-sm-5" name="cust_lv_id" type="text" data-rule="会员等级:required">
                        @foreach($gradeList as $key => $value)
                            <option value="{{$key}}" @if($row['cust_lv_id']=="$key") selected @endif  >{{$value}}</option>
                        @endforeach
                    </select>
                    <span class="col-sm-7 color-6A6969"> 为会员分配一个等级，不同等级有不同优惠。</span>
                </div>
                <span class="msg-box" style="position:static;" for="cust_lv_id"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 账号：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="user_name" class="form-control col-sm-5" name="user_name" type="text" value="{{$row['user_name']}}" placeholder="" data-rule="用户名:required">
                    <span class="col-sm-7 color-6A6969"> 数字或字母组合,不区分大小写,不支持中文,长度3~15位。</span>
                </div>
                <span class="msg-box" style="position:static;" for="user_name"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 昵称：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="user_nickname" class="form-control col-sm-5" name="user_nickname" type="text" value="{{$row['user_nickname']}}" placeholder="" data-rule="昵称:required">
                    <span class="col-sm-7 color-6A6969"> 支持中英文,区分大小写,长度2~15位。</span>
                </div>
                <span class="msg-box" style="position:static;" for="user_nickname"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 密码：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="password" class="form-control col-sm-5" name="password" type="text"  placeholder="" data-rule="@if (!isset($row['user_id']))密码:required;password @endif">
                    <span class="col-sm-7 color-6A6969"> 登录使用的密码@if ($row['user_id']) ，不修改密码请留空。  @endif</span>
                </div>
                <span class="msg-box" style="position:static;" for="password"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">手机号：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="user_mobile" class="form-control col-sm-5" name="user_mobile" type="text" value="{{$row['user_mobile']}}" placeholder="">
                    <span class="col-sm-7 color-6A6969"> 联系方式。</span>
                </div>
                <span class="msg-box" style="position:static;" for="user_mobile"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">邮箱：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="user_email" class="form-control col-sm-5" name="user_email" type="text" value="{{$row['user_email']}}" placeholder="">
                    <span class="col-sm-7 color-6A6969"> 请输入正确的邮箱地址，可能用于密码找回。</span>
                </div>
                <span class="msg-box" style="position:static;" for="user_email"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 头像：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div style="padding-left: 0;height: 108px" class="col-sm-5">
                        @component('component/image_upload',['name'=>'user_avatar','value'=>$row['user_avatar'],'rule'=>'头像:required','direction'=>1,'browse_btn'=>'form-avatar','content_class'=>'background','img_format'=>'jpg,jpeg,png','uploader'=>'uploader','num'=>1])
                        @endcomponent
                    </div>
                    <span class="col-sm-7 color-6A6969"  style="margin-top: 30px">建议尺寸:90*90，格式：JPG/JPEG/PNG格式， 文件大小：100Kb</span>
                </div>
                <span class="msg-box" style="position:static;" for="user_avatar"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">生日：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div class="col-sm-5" style="padding-left: 0;padding-right: 0">
                        <input name="user_birthday" @if($row['user_birthday'])value="{{date("Y-m-d H:i:s",$row['user_birthday'])}}" @endif type="text" style="font-size: 12px;width: 100%" id="reservationtime" class="form-control float-right date-picker datetimerange">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <span class="col-sm-7 color-6A6969"> 请选择日期。</span>
                </div>
                <span class="msg-box" style="position:static;" for="user_birthday"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">余额：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="balance" class="form-control col-sm-5" name="balance" type="text" value="{{$row['balance']}}" placeholder="">
                    <span class="col-sm-7 color-6A6969"> 账号余额，可用于消费。</span>
                </div>
                <span class="msg-box" style="position:static;" for="balance"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">积分：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="score" class="form-control col-sm-5" name="score" type="text" value="{{$row['score']}}" placeholder="">
                    <span class="col-sm-7 color-6A6969"> 账号积分，可通过商家的活动获取。</span>
                </div>
                <span class="msg-box" style="position:static;" for="score"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">状态：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div class="col-sm-5">
                        @component('component/radio',['radio'=>['1'=>'启用','0'=>'禁用'],'name'=>'status','default_key'=>$row['status']??'1'])
                        @endcomponent
                    </div>
                    <span class="col-sm-7 color-6A6969"> 启用：开启，禁用：不开启。</span>
                </div>
                <span class="msg-box" style="position:static;" for="status"></span>
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

<script>
    //验证手机号
    $("body").delegate("#user_mobile","change",function () {
        var reg = /^1[3|4|5|7|8][0-9]{9}$/; //验证规则
        var mobile = $(this).val();
        if(mobile==""){
            return;
        }
        //手机号验证
        if(!reg.test(mobile)){
            layer.msg("请输入的正确的手机号码");
            return;
        }
    });

    //验证邮箱
    $("body").delegate("#user_email","change",function () {
        var reg=/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i;
        var email=$(this).val();
        if(email==""){
            return;
        }
        if(!reg.test(email)){
            layer.msg( '请输入的正确的邮箱');
        }
    });

</script>