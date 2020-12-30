<!-- form表单视图 -->
@inject('CommonPresenter','App\Presenters\CommonPresenter')
<div id="baseInfo" style="margin-top:30px;">
    <form class="form-horizontal" id="form-base" method="post" autocomplete="off">
        @csrf
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                商家主账号：
            </label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <span class="color-6A6969" style="margin-top: 7px">{{$account['dms_adm_username']}}</span>
                </div>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">状态：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <span class="color-6A6969" style="margin-top: 7px">{{$CommonPresenter->getEnabledOrDisabled($account['dms_adm_status'])}}</span>
                </div>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                等级：
            </label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <span class="color-6A6969" style="margin-top: 7px">{{$info['cust_lv_name']}}</span>
                </div>
            </div>
        </div>


        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span class="control-span">*</span>店铺名称：
            </label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input id="shopname" value="{{$info['agent_name']}}" class="form-control col-sm-5" name="agent_name" type="text"  placeholder="" data-rule="店铺名称:required">
                    <span class="col-sm-7" style="color: rgba(0, 0, 0, 0.45);margin-top: 3px">必填项。中文或英文，长度3~32位。</span>
                </div>
                <span class="msg-box" style="position:static;" for="shopname"></span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">创建时间：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <span class="color-6A6969" style="margin-top: 7px">{{$CommonPresenter->exchangeTime($info['created_at'])}}</span>
                </div>
            </div>
        </div>


        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span class="control-span">*</span> 店铺类型：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <select name="agent_type" class="col-sm-5" disabled="disabled" style="background-color: gainsboro;">
                        @foreach($type as $k=>$v)
                            @if($info['agent_type']==$k)
                                <option value="{{$k}}" selected>{{$v}}</option>
                            @else
                                <option value="{{$k}}">{{$v}}</option>
                            @endif
                        @endforeach
                    </select>
                    <span class="col-sm-7" style="color: rgba(0, 0, 0, 0.45);margin-top: 3px">如需修改，请联系对应商家进行修改</span>
                </div>
            </div>
            <span class="msg-box" style="position:static;" for="username"></span>
        </div>

        <div class="form-group row form-item" style="height: 12%;">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">店铺LOGO：</label>
            <div class="col-xs-12 col-sm-10" style="height: 108px">
                <div class="row">
                    <div style="padding-left: 0;" class="col-sm-5">
                        @component('component/image_upload',['name'=>'agent_logo','value'=>$info['agent_logo'],'num'=>1,'uploader'=>'uploader1','direction'=>1,'browse_btn'=>'basic-avatar','content_class'=>'avatar','img_format'=>'gif,jpg,jpeg,png','img_size'=>'100kb'])
                        @endcomponent
                    </div>
                    <span class="col-sm-7" style="color: rgba(0, 0, 0, 0.45);margin-top: 3px">推荐：200 * 60 PX，大小：100K。格式：gif / jpg / jpeg / png</span>
                </div>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                所在区域：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    @component('component/areas',['province_value'=>$info['province'],'city_value'=>$info['city'],'areas_value'=>$info['district']])
                    @endcomponent
                </div>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                详细地址：
            </label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input id="c-mch_name" class="form-control col-sm-5" name="address" type="text" value="{{$info['address']}}" placeholder="">
                </div>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                店铺网址：
            </label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input id="c-mch_name" class="form-control col-sm-5" name="agent_url" type="text" value="{{$info['agent_url']}}" placeholder="">
                </div>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span class="control-span">*</span>联系人：
            </label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input id="person" class="form-control col-sm-5" name="agent_linkman" type="text" value="{{$info['agent_linkman']}}" placeholder="" data-rule="联系人:required">
                </div>
                <span class="msg-box" style="position:static;" for="person"></span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span class="control-span">*</span>手机：
            </label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input id="mobile" class="form-control col-sm-5" name="mobile" type="number" value="{{$info['mobile']}}" placeholder="" data-rule="手机:required">
                    <span class="col-sm-7" style="color: rgba(0, 0, 0, 0.45);margin-top: 3px">  用于紧急事务联系。最低余额警戒值短信接收。</span>
                </div>
                <span class="msg-box" style="position:static;" for="mobile"></span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">微信号：
            </label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input id="wechat" class="form-control col-sm-5" name="wechat" type="text" value="{{$info['wechat']}}" placeholder="">
                </div>
                <span class="msg-box" style="position:static;" for="wechat"></span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                Email：
            </label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input id="c-mch_name" class="form-control col-sm-5" name="email" type="text" value="{{$info['email']}}" placeholder="">
                </div>
            </div>
        </div>

        @if($isMain==1)
            <div class="form-group row form-item" style="margin-top: 50px">
                <label class="control-label col-xs-12 col-sm-2"></label>
                <div class="col-xs-12 col-sm-8">
                    <button type="submit" id="btn-base" class="btn btn-primary btn-3F51B5 btn-sure" onclick="return false;">确定</button>
                </div>
            </div>
        @endif
    </form>
</div>