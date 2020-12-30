<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/agent/info/save" onsubmit="return false;" autocomplete="off">
    <input type=hidden value="{{$row['agent_info_id']}}" name="agent_info_id" id="agent_info_id">
    <input type=hidden value="{{$row['mch_id']}}" name="mch_id" id="mch_id">

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 店铺等级：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <select id="cust_lv_id" class="form-control col-sm-5" name="cust_lv_id" type="text">
                        @foreach($grade as $key => $value)
                            <option value="{{$key}}" @if($row['cust_lv_id']==$key) selected @endif >{{$value}}</option>
                        @endforeach
                    </select>
                    <span class="col-sm-7 color-6A6969"> 为店铺选择一个等级，不同等级有不同优惠。</span>
                </div>
                <span class="msg-box" style="position:static;" for="cust_lv_id"></span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 店铺名称：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="agent_name" class="form-control col-sm-5" name="agent_name" type="text" value="{{$row['agent_name']}}" placeholder="" data-rule="店铺名称:required">
                    <span class="col-sm-7 color-6A6969"> 店铺的名称。</span>
                </div>
                <span class="msg-box" style="position:static;" for="agent_name"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">店铺余额：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <span class="col-sm-5" style="padding-top: 0.5%;font-size: 16px">￥ {{$row['agent_balance']}}</span>
                    <input  id="agent_balance" hidden class="form-control col-sm-5" name="agent_balance" type="text" value="{{$row['agent_balance']}}" placeholder="">
                    <span class="col-sm-7 color-6A6969"> 资金余额。</span>
                </div>
                <span class="msg-box" style="position:static;" for="agent_balance"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 店铺类型：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <select id="agent_type" class="form-control col-sm-5" name="agent_type">
                        @foreach($shop_type as $key=>$value)
                            <option value="{{$key}}" @if($row['agent_type']==$key) selected @endif  >{{$value}}</option>
                        @endforeach
                    </select>
                    <span class="col-sm-7 color-6A6969"> 店铺的类型。</span>
                </div>
                <span class="msg-box" style="position:static;" for="agent_type"></span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">邀请人：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <select id="inviter_id" class="form-control col-sm-5" name="inviter_id">
                        @foreach($info as $key=>$value)
                            <option value="{{$key}}" @if($row['inviter_id']==$key) selected @endif  >{{$value}}</option>
                        @endforeach
                    </select>
                    <span class="col-sm-7 color-6A6969"> 该商户的邀请人。</span>
                </div>
                <span class="msg-box" style="position:static;" for="inviter_id"></span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">店铺LOGO：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div style="padding-left: 0;height: 108px" class="col-sm-5">
                        @component('component/image_upload',['name'=>'agent_logo','uploader'=>'uploader1','num'=>1,'value'=>$row['agent_logo'],'direction'=>1,'browse_btn'=>'form-avatar','content_class'=>'background','img_format'=>'jpg,jpeg,png'])
                        @endcomponent
                    </div>
                    <span class="col-sm-7 color-6A6969"> 店铺的图标。</span>
                </div>
                <span class="msg-box" style="position:static;" for="agent_logo"></span>
            </div>
        </div>
        {{--<div class="form-group row form-item">--}}
            {{--<label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">--}}
            {{--<span style="color:red">*</span> 店铺主营业务：</label>--}}
            {{--<div class="col-xs-12 col-sm-10">--}}
                {{--<div class="row">--}}
                    {{--<input  id="shop_business" class="form-control col-sm-5" name="shop_business" type="text" value="{{$row['shop_business']}}" placeholder="" data-rule="店铺主营业务:required">--}}
                    {{--<span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>--}}
                {{--</div>--}}
                {{--<span class="msg-box" style="position:static;" for="shop_business"></span>--}}
            {{--</div>--}}
        {{--</div>--}}
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">店铺描述：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <textarea id="agent_desc" rows="5" class="form-control col-sm-5" name="agent_desc" type="text"  placeholder="" >{{$row['agent_desc']}}</textarea>
                    <span class="col-sm-7 color-6A6969"> 对店铺的描述。</span>
                </div>
                <span class="msg-box" style="position:static;" for="agent_desc"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 联系人：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="agent_linkman" class="form-control col-sm-5" name="agent_linkman" type="text" value="{{$row['agent_linkman']}}" placeholder="" data-rule="联系人:required">
                    <span class="col-sm-7 color-6A6969"> 店铺联系人。</span>
                </div>
                <span class="msg-box" style="position:static;" for="agent_linkman"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 手机号码：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="mobile" class="form-control col-sm-5" name="mobile" type="text" value="{{$row['mobile']}}" placeholder="" data-rule="手机号码:required">
                    <span class="col-sm-7 color-6A6969"> 联系人手机号码。</span>
                </div>
                <span class="msg-box" style="position:static;" for="mobile"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">店铺网址：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row" style="display: flex;justify-content: space-between">
                    <input  id="agent_url" class="form-control col-sm-5" name="agent_url" type="text" value="{{$row['agent_url']}}" placeholder="">
                    <span class="col-sm-7 color-6A6969"> 比如淘宝店铺地址。</span>
                </div>
                <span class="msg-box" style="position:static;" for="agent_url"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">客服电话：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="telephone" class="form-control col-sm-5" name="telephone" type="text" value="{{$row['telephone']}}" placeholder="">
                    <span class="col-sm-7 color-6A6969"> 店铺官方号码。</span>
                </div>
                <span class="msg-box" style="position:static;" for="telephone"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">微信号：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="wechat" class="form-control col-sm-5" name="wechat" type="text" value="{{$row['wechat']}}" placeholder="">
                    <span class="col-sm-7 color-6A6969"> 联系人微信号。</span>
                </div>
                <span class="msg-box" style="position:static;" for="wechat"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">邮箱：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="email" class="form-control col-sm-5" name="email" type="email" value="{{$row['email']}}" placeholder="">
                    <span class="col-sm-7 color-6A6969"> 联系人邮箱。</span>
                </div>
                <span class="msg-box" style="position:static;" for="email"></span>
            </div>
        </div>


        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">地区：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div style="display: inline-block;padding: 0" class="col-sm-5">
                        @component('component.areas',['province_value'=>$row['province'],'city_value'=>$row['city'],'areas_value'=>$row['district']])@endcomponent
                    </div>
                    <span class="col-sm-7 color-6A6969"> 店铺所在地区。</span>
                </div>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">详细地址：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="address" class="form-control col-sm-5" name="address" type="text" value="{{$row['address']}}" placeholder="">
                    <span class="col-sm-7 color-6A6969"> 店铺详细地址。</span>
                </div>
                <span class="msg-box" style="position:static;" for="address"></span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">备注：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <textarea id="agent_info_desc" rows="10" class="form-control col-sm-5" name="agent_info_desc" type="text" placeholder="" >{{$row['agent_info_desc']}} </textarea>
                    <span class="col-sm-7 color-6A6969"> 说明，不超过200个字。</span>
                </div>
                <span class="msg-box" style="position:static;" for="agent_info_desc"></span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 状态：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div style="display: inline-block;padding-top: 5px;padding-left: 0" class="col-sm-5">
                        @component('component/radio',['radio'=>['1'=>'启用','2'=>'禁用'],'name'=>'agent_status','default_key'=>$row['agent_status']??1])
                        @endcomponent
                    </div>
                    <span class="col-sm-7 color-6A6969" style="padding-top: 1%"> 启用:开启;禁用:不开启。</span>
                </div>
                <span class="msg-box" style="position:static;" for="agent_status"></span>
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
<script>
    //验证手机号
    $("body").delegate("#mobile","change",function () {
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
    $("body").delegate("#email","change",function () {
        var reg=/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i;
        var email=$(this).val();
        if(email==""){
            return;
        }
        if(!reg.test(email)){
            layer.msg( '请输入的正确的邮箱');
            return;
        }
    });

</script>