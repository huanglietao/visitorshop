<!-- form表单视图 -->
@if($message)
    <div class="row" style="text-align: center;font-size: 14px;padding-top: 20px">
        <span class="col-xs-12 col-sm-12">{{$message}}</span>
    </div>
@else
    <div style="margin-top:30px">
        <form class="form-horizontal common-form" id="form-save" method="post" action="/agent/account/save" onsubmit="return false;" autocomplete="off">
        <input type=hidden value="{{$row['dms_adm_id']}}" name="dms_adm_id" id="dms_adm_id">
        <input type=hidden value="{{$row['mch_id']}}" name="mch_id" id="mch_id">

            <div class="form-group row form-item">
                <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                    <span style="color:red">*</span> 关联商户资料：</label>
                <div class="col-xs-12 col-sm-10">
                    <div class="row">
                        <input readonly class="form-control col-sm-5" id="agent_name"  value="{{$infoList['agent_name']}}"/>
                        <input hidden class="form-control col-sm-5" id="agent_info_id" name="agent_info_id" value="{{$infoList['agent_info_id']}}" />
                        <span class="col-sm-7 color-6A6969">绑定商户资料信息</span>
                    </div>
                    <span class="msg-box" style="position:static;" for="agent_info_id"></span>
                </div>
            </div>

            <div class="form-group row form-item">
                <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 账号：</label>
                <div class="col-xs-12 col-sm-10">
                    <div class="row">
                        <input  id="dms_adm_username" class="form-control col-sm-5" name="dms_adm_username" type="text" value="{{$row['dms_adm_username']}}" placeholder="" data-rule="账号:required">
                        <span class="col-sm-7 color-6A6969"> 数字或字母组合,不区分大小写,不支持中文,长度3~15位。</span>
                    </div>
                    <span class="msg-box" style="position:static;" for="dms_adm_username"></span>
                </div>
            </div>
            <div class="form-group row form-item">
                <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 密码：</label>
                <div class="col-xs-12 col-sm-10">
                    <div class="row">
                        <input  id="dms_adm_password" class="form-control col-sm-5" name="dms_adm_password" type="text" value="" placeholder="" data-rule="@if (!isset($row['dms_adm_id']))密码:required;password @endif">
                        <span class="col-sm-7 color-6A6969"> 商户登录使用密码@if ($row['dms_adm_id']) ,不修改密码请留空。  @endif</span>
                    </div>
                    <span class="msg-box" style="position:static;" for="dms_adm_password"></span>
                </div>
            </div>

            <div class="form-group row form-item">
                <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">昵称：</label>
                <div class="col-xs-12 col-sm-10">
                    <div class="row">
                        <input  id="dms_adm_nickname" class="form-control col-sm-5" name="dms_adm_nickname" type="text" value="{{$row['dms_adm_nickname']}}" placeholder="" >
                        <span class="col-sm-7 color-6A6969">支持中英文,区分大小写,长度2~15位。</span>
                    </div>
                </div>
            </div>

            <div class="form-group row form-item">
                <label style=" font-weight: normal;line-height: 85px;" for="c-mch_name" class="control-label col-xs-12 col-sm-2">头像：</label>
                <div class="col-xs-12 col-sm-10">
                    <div class="row">
                        <div style="padding-left: 0;height: 108px" class="col-sm-5">
                            @component('component/image_upload',['name'=>'dms_adm_avatar','value'=>$row['dms_adm_avatar'],'direction'=>1,'browse_btn'=>'form-avatar','content_class'=>'background','img_format'=>'jpg,jpeg,png','uploader'=>'uploader','num'=>1])
                            @endcomponent
                        </div>
                        <span class="col-sm-7 color-6A6969"  style="margin-top: 30px">建议尺寸:90*90，格式：JPG/JPEG/PNG格式， 文件大小：100Kb</span>
                    </div>
                    <span class="msg-box" style="position:static;" for="dms_adm_avatar"></span>
                </div>
            </div>
            <div class="form-group row form-item">
                <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">状态：</label>
                <div class="col-xs-12 col-sm-10" style="padding-top: 6px;">
                    <div class="row">
                        @component('component/radio',['radio'=>['1'=>'启用','0'=>'禁用'],'name'=>'dms_adm_status','default_key'=>$row['dms_adm_status']??'1'])
                        @endcomponent
                    </div>
                </div>
            </div>
            {{--<div class="form-group row form-item">--}}
                {{--<label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">--}}
                {{--<span style="color:red">*</span> 状态;0:禁用,1:启用：</label>--}}
                {{--<div class="col-xs-12 col-sm-10">--}}
                    {{--<div class="row">--}}
                        {{--<input  id="dms_adm_status" class="form-control col-sm-5" name="dms_adm_status" type="text" value="{{$row['dms_adm_status']}}" placeholder="" data-rule="状态;0:禁用,1:启用:required">--}}
                        {{--<span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>--}}
                    {{--</div>--}}
                    {{--<span class="msg-box" style="position:static;" for="dms_adm_status"></span>--}}
                {{--</div>--}}
            {{--</div>--}}

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
@endif