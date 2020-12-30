@extends('layout.agent_official',['title'=>'注册'])

@section("content")
    <div class="row" style="width: 70%;background: white;margin: 70px auto 0 auto;padding: 80px 0 0 0">
        <form class="form-horizontal common-form"  id="form-save" method="post" action="/index/register/save" onsubmit="return false;" autocomplete="off">
            <div class="form-group row form-item">
                <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                    <span style="color:red">*</span> 店铺/工作室名称：</label>
                <div class="col-xs-12 col-sm-10">
                    <div class="row" style="display: flex;justify-content: space-between">
                        <input  id="agent_name" class="form-control col-sm-5" name="agent_name" type="text"  placeholder="" data-rule="店铺名称:required">
                        <span class="col-sm-5 color-6A6969" style="padding-top: 7px;"> 店铺的名称。</span>
                    </div>
                    <span class="msg-box" style="position:static;" for="agent_name"></span>
                </div>
            </div>

            <div class="form-group row form-item">
                <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                    <span style="color:red">*</span> 店铺类型：</label>
                <div class="col-xs-12 col-sm-10">
                    <div class="row radio" style="display: flex;justify-content: space-between">
                        <div class="col-sm-5" style="width: 100%;padding-left: 0">
                            @component('component/radio',['radio'=>$shop_type,'name'=>'agent_type','default_key'=>1])
                            @endcomponent
                        </div>
                        <span class="col-sm-5 color-6A6969"> 店铺的类型。</span>
                    </div>
                    <span class="msg-box" style="position:static;" for="agent_type"></span>
                </div>
            </div>

            <div class="form-group row form-item">
                <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                    <span style="color:red">*</span> 联系人：</label>
                <div class="col-xs-12 col-sm-10">
                    <div class="row" style="display: flex;justify-content: space-between">
                        <input  id="agent_linkman" class="form-control col-sm-5" name="agent_linkman" type="text" value="" placeholder="" data-rule="联系人:required">
                        <span class="col-sm-5 color-6A6969"> 店铺联系人。</span>
                    </div>
                    <span class="msg-box" style="position:static;" for="agent_linkman"></span>
                </div>
            </div>

            <div class="form-group row form-item">
                <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                    <span style="color:red">*</span> 手机号码：</label>
                <div class="col-xs-12 col-sm-10">
                    <div class="row" style="display: flex;justify-content: space-between">
                        <input  id="mobile" class="form-control col-sm-5" name="mobile" type="number" value="" placeholder="" data-rule="手机号码:required">
                        <span class="col-sm-5 color-6A6969"> 分销商管理平台登录所用的账号，请填写有效的手机号码。</span>
                    </div>
                    <span class="msg-box" style="position:static;" for="mobile"></span>
                </div>
            </div>

            <div class="form-group row form-item">
                <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                    <span style="color:red">*</span> 密码：</label>
                <div class="col-xs-12 col-sm-10">
                    <div class="row" style="display: flex;justify-content: space-between">
                        <input  id="dms_adm_password" class="form-control col-sm-5" name="dms_adm_password" type="password" value="" placeholder="" data-rule="密码:required;password">
                        <span class="col-sm-5 color-6A6969"> 分销商管理平台登录使用密码</span>
                    </div>
                    <span class="msg-box" style="position:static;" for="dms_adm_password"></span>
                </div>
            </div>
            <div class="form-group row form-item">
                <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                    <span style="color:red">*</span> 确认密码：</label>
                <div class="col-xs-12 col-sm-10">
                    <div class="row" style="display: flex;justify-content: space-between">
                        <input  id="dms_real_password" class="form-control col-sm-5" type="password" data-rule="确认密码:required;password">
                        <span class="col-sm-5 color-6A6969"> 请填写确认跟登录密码一致</span>
                    </div>
                    <span class="msg-box" style="position:static;" for="dms_real_password"></span>
                </div>
            </div>

            <div class="form-group row form-item">
                <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">邀请码：</label>
                <div class="col-xs-12 col-sm-10">
                    <div class="row" style="display: flex;justify-content: space-between">
                        <input  id="inviter" class="form-control col-sm-5" name="inviter" type="text">
                        <span class="col-sm-5 color-6A6969">有则填写</span>
                    </div>
                    <span class="msg-box" style="position:static;" for="inviter"></span>
                </div>
            </div>


            {{--<div class="form-group row form-item">--}}
                {{--<label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">店铺LOGO：</label>--}}
                {{--<div class="col-xs-12 col-sm-10">--}}
                    {{--<div class="row" style="display: flex;">--}}
                        {{--<div style="padding-left: 0;float: right;height: 108px;width: 100%" class="col-sm-5">--}}
                            {{--@component('component/image_upload',['name'=>'agent_logo','uploader'=>'uploader1','num'=>1,'value'=>"",'direction'=>1,'browse_btn'=>'form-avatar','content_class'=>'background','img_format'=>'jpg,jpeg,png','img_size'=>'300kb'])--}}
                            {{--@endcomponent--}}
                        {{--</div>--}}
                        {{--<span class="col-sm-5 color-6A6969"> 店铺的图标。</span>--}}
                    {{--</div>--}}
                    {{--<span class="msg-box" style="position:static;" for="agent_logo"></span>--}}
                {{--</div>--}}
            {{--</div>--}}

            {{--<div class="form-group row form-item">--}}
                {{--<label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">店铺网址：</label>--}}
                {{--<div class="col-xs-12 col-sm-10">--}}
                    {{--<div class="row" style="display: flex;justify-content: space-between">--}}
                        {{--<input  id="agent_url" class="form-control col-sm-5" name="agent_url" type="text" value="" placeholder="">--}}
                        {{--<span class="col-sm-5 color-6A6969"> 比如淘宝店铺地址。</span>--}}
                    {{--</div>--}}
                    {{--<span class="msg-box" style="position:static;" for="agent_url"></span>--}}
                {{--</div>--}}
            {{--</div>--}}

            {{--<div class="form-group row form-item">--}}
                {{--<label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">客服电话：</label>--}}
                {{--<div class="col-xs-12 col-sm-10">--}}
                    {{--<div class="row" style="display: flex;justify-content: space-between">--}}
                        {{--<input  id="telephone" class="form-control col-sm-5" name="telephone" type="text" value="" placeholder="">--}}
                        {{--<span class="col-sm-5 color-6A6969"> 店铺官方号码。</span>--}}
                    {{--</div>--}}
                    {{--<span class="msg-box" style="position:static;" for="telephone"></span>--}}
                {{--</div>--}}
            {{--</div>--}}

            {{--<div class="form-group row form-item">--}}
                {{--<label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">微信号：</label>--}}
                {{--<div class="col-xs-12 col-sm-10">--}}
                    {{--<div class="row" style="display: flex;justify-content: space-between">--}}
                        {{--<input  id="wechat" class="form-control col-sm-5" name="wechat" type="text" value="" placeholder="">--}}
                        {{--<span class="col-sm-5 color-6A6969"> 联系人微信号。</span>--}}
                    {{--</div>--}}
                    {{--<span class="msg-box" style="position:static;" for="wechat"></span>--}}
                {{--</div>--}}
            {{--</div>--}}

            <div class="form-group row form-item">
                <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">邮箱：</label>
                <div class="col-xs-12 col-sm-10">
                    <div class="row" style="display: flex;justify-content: space-between">
                        <input  id="email" class="form-control col-sm-5" name="email" type="email" value="" placeholder="">
                        <span class="col-sm-5 color-6A6969"> 联系人邮箱。</span>
                    </div>
                    <span class="msg-box" style="position:static;" for="email"></span>
                </div>
            </div>

            <div class="form-group row form-item">
                <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">地区：</label>
                <div class="col-xs-12 col-sm-10">
                    <div class="row" style="display: flex;justify-content: space-between">
                        <div style="display: inline-block;padding: 0;width: 100%" class="col-sm-5">
                            @component('component.areas')@endcomponent
                        </div>
                        <span class="col-sm-5 color-6A6969"> 店铺所在地区。</span>
                    </div>
                </div>
            </div>

            <div class="form-group row form-item">
                <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">详细地址：</label>
                <div class="col-xs-12 col-sm-10">
                    <div class="row" style="display: flex;justify-content: space-between">
                        <input  id="address" class="form-control col-sm-5" name="address" type="text" value="" placeholder="">
                        <span class="col-sm-5 color-6A6969"> 店铺详细地址。</span>
                    </div>
                    <span class="msg-box" style="position:static;" for="address"></span>
                </div>
            </div>

            {{--<div class="form-group row form-item">--}}
                {{--<label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">店铺描述：</label>--}}
                {{--<div class="col-xs-12 col-sm-10">--}}
                    {{--<div class="row" style="display: flex;justify-content: space-between">--}}
                        {{--<textarea id="agent_desc" rows="5" class="form-control col-sm-5" name="agent_desc" type="text"  placeholder=""></textarea>--}}
                        {{--<span class="col-sm-5 color-6A6969"> 对店铺的描述。</span>--}}
                    {{--</div>--}}
                    {{--<span class="msg-box" style="position:static;" for="agent_desc"></span>--}}
                {{--</div>--}}
            {{--</div>--}}

            <div class="form-group row form-item">
                <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">备注：</label>
                <div class="col-xs-12 col-sm-10">
                    <div class="row" style="display: flex;justify-content: space-between">
                        <textarea id="agent_apply_desc" rows="10" class="form-control col-sm-5" name="agent_apply_desc" type="text" placeholder="" > </textarea>
                        <span class="col-sm-5 color-6A6969"> 说明，不超过200个字。</span>
                    </div>
                    <span class="msg-box" style="position:static;" for="agent_apply_desc"></span>
                </div>
            </div>

            <div class="form-group row form-item" style="padding-top: 20px">
                <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2"></label>
                <div class="col-xs-12 col-sm-10">
                    <div class="row" style="display: flex;">
                        <input id="is_check"  class="btn-checkbox"  type="checkbox"/>
                        <label for="is_check" style="margin-top: 4px"></label>
                        <span style="margin-left: {{isset($left_distance)?$left_distance:'25'}}px;margin-right: {{isset($right_distance)?$right_distance:'30'}}px;">
                            同意并遵守
                            <a class="link" href="/index/clause" target="_blank" style="background-color: transparent;color: #3c8dbc;text-decoration: none">
                                <span class="check-text">《服务条款》</span>
                            </a>
                        </span>
                    </div>
                    <span class="msg-box" style="position:static;" for="is_check"></span>
                </div>
            </div>

            <div class="form-group row form-item" style="padding:1% 0 2% 0">
                <label class="control-label col-xs-12 col-sm-2"></label>
                <div class="col-xs-12 col-sm-10">
                    <button type="submit" id="confirm" disabled="disabled"  class="btn btn-3F51B5 is_check btn-confirm" onclick="return false;">确定</button>
                </div>
            </div>
        </form>
    </div>

    <style>
        .areas-one{
            width: 100%;
            display: flex;
            justify-content: space-between;
        }
        .areas-province,.areas-city,.areas-area{
            height: 30px !important;
            width: 32%;
        }


    </style>
@endsection
<!--  加载js -->
@section("js-file")
    <script src="{{ URL::asset('js/agent/index/register.js')}}"></script>
@endsection
@section("pages-js")

@endsection
