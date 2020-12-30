@extends('layout.mch_iframe')
<!DOCTYPE html>
@section("main-content")

    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 分销管理 / 站点配置' ])
    @endcomponent
    <!-- 面包屑组件end  -->

    <div id="main">
        <!--  提示组件 start -->
        @component('component/tips')
            {{--<p style="margin:5px 0 ;padding:0">用于分销网站首页的相关信息展示。</p>--}}
    @endcomponent
        <!--  提示组件 end -->

        <div class="row" style="width: 100%;display: flex;flex-direction: column;padding-top: 50px;font-size: 12px;color: #797777;padding-left: 30px;">
            <form method="post" action="/agent/deploy/save">
                @csrf
                <input type="hidden" name="dms_deploy_id" value="{{$data['dms_deploy_id']}}"/>

                <div class="col-sm-12 col-xs-12" style="width: 100%;flex-direction: row;display: flex;margin-bottom: 15px;">
                    <div style="margin-right: 20px;text-align: right;line-height: 27px;width: 12%;"><span style="color: red;">*</span>站点名称:</div>
                    <div style="width: 88%">
                        <div class="row">
                            <input type="text" id="deploy_sitename" class="form-control col-sm-7" name="deploy_sitename" value="{{$data['deploy_sitename']}}" data-rule="站点名称:required"/>
                        </div>
                        <span class="msg-box" style="position:static;" for="deploy_sitename"></span>
                    </div>
                </div>

                <div class="col-sm-12 col-xs-12" style="width: 100%;flex-direction: row;display: flex;margin-bottom: 15px;">
                    <div style="margin-right: 20px;text-align: right;line-height: 27px;width: 12%;">站点url:</div>
                    <div style="width: 88%">
                        <div class="row">
                            <input type="text" id="deploy_siteurl" class="form-control col-sm-7" name="deploy_siteurl" value="{{$url}}" disabled/>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-xs-12" style="width: 100%;flex-direction: row;display: flex;margin-bottom: 15px;">
                    <div style="margin-right: 20px;text-align: right;line-height: 27px;width: 12%;">绑定域名:</div>
                    <div style="width: 88%">
                        <div class="row">
                            <input type="text" id="deploy_siteurl" class="form-control col-sm-7" name="deploy_bindurl" @if(!empty($data['deploy_bindurl']))value="{{$data['deploy_bindurl']}}" disabled @endif/>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-xs-12" style="width: 100%;flex-direction: row;display: flex;margin-bottom: 15px;">
                    <div style="margin-right: 20px;text-align: right;line-height: 27px;width: 12%;">座机号:</div>
                    <div style="width: 88%">
                        <div class="row">
                            <input type="text" id="deploy_seat_number" class="form-control col-sm-7" name="deploy_seat_number" value="{{$data['deploy_seat_number']}}"/>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-xs-12" style="width: 100%;flex-direction: row;display: flex;margin-bottom: 15px;">
                    <div style="margin-right: 20px;text-align: right;line-height: 27px;width: 12%;"><span style="color: red;">*</span>手机号:</div>
                    <div style="width: 88%">
                        <div class="row">
                            <input type="text" id="deploy_telphone" class="form-control col-sm-7" name="deploy_telphone" value="{{$data['deploy_telphone']}}" data-rule="手机号:required"/>
                        </div>
                        <span class="msg-box" style="position:static;" for="deploy_telphone"></span>
                    </div>
                </div>

                <div class="col-sm-12 col-xs-12" style="width: 100%;flex-direction: row;display: flex;margin-bottom: 15px;">
                    <div style="margin-right: 20px;text-align: right;line-height: 27px;width: 12%;"><span style="color: red;">*</span>商务邮箱:</div>
                    <div style="width: 88%">
                        <div class="row">
                            <input type="text" id="deploy_email" class="form-control col-sm-7" name="deploy_email" value="{{$data['deploy_email']}}" data-rule="商务邮箱:required"/>
                        </div>
                        <span class="msg-box" style="position:static;" for="deploy_email"></span>
                    </div>
                </div>

                <div class="col-sm-12 col-xs-12" style="width: 100%;flex-direction: row;display: flex;margin-bottom: 15px;height: 100px;">
                    <div style="margin-right: 20px;text-align: right;line-height: 90px;width: 12%;">二维码上传:</div>
                    <div style="width: 88%">
                        <div class="row">
                            @component('component/image_upload',['name'=>'deploy_qr_code','direction'=>1,'browse_btn'=>'form-avatar','content_class'=>'background','img_format'=>'jpg,jpeg,png','uploader'=>'uploader','num'=>1,'value'=>$data['deploy_qr_code']])
                            @endcomponent
                        </div>
                        <span class="msg-box" style="position:static;" for="deploy_qr_code"></span>
                    </div>
                </div>

                <div class="col-sm-12 col-xs-12" style="width: 100%;flex-direction: row;display: flex;margin-bottom: 15px;">
                    <div style="margin-right: 20px;text-align: right;line-height: 27px;width: 12%;"><span style="color: red;">*</span>联系地址:</div>
                    <div style="width: 88%">
                        <div class="row">
                            <input type="text" id="deploy_address" class="form-control col-sm-7" name="deploy_address" value="{{$data['deploy_address']}}" data-rule="联系地址:required"/>
                        </div>
                        <span class="msg-box" style="position:static;" for="deploy_address"></span>
                    </div>
                </div>

                <div class="col-sm-12 col-xs-12" style="width: 100%;flex-direction: row;display: flex;margin-bottom: 15px;">
                    <div style="margin-right: 20px;text-align: right;line-height: 27px;width: 12%;"><span style="color: red;">*</span>备案信息:</div>
                    <div style="width: 88%">
                        <div class="row">
                            <input type="text" id="deploy_remarks" class="form-control col-sm-7" name="deploy_remarks" value="{{$data['deploy_remarks']}}" data-rule="备案信息:required"/>
                        </div>
                        <span class="msg-box" style="position:static;" for="deploy_remarks"></span>
                    </div>
                </div>

                <div class="col-sm-12 col-xs-12" style="width: 100%;flex-direction: row;display: flex;margin-bottom: 15px;">
                    <div style="margin-right: 20px;text-align: right;line-height: 27px;width: 12%;">开启购物车:</div>
                    <div style="width: 50%;padding-top: 5px;">
                        @component('component/radio',['radio'=>[PUBLIC_YES=>'是',PUBLIC_NO=>'否'],'name'=>'shopping_car','default_key'=>$data['shopping_car']??PUBLIC_YES])
                        @endcomponent
                    </div>
                </div>

                <div class="col-sm-12 col-xs-12" style="width: 100%;height: 80px;flex-direction: row;display: flex">
                    <div style="width:12%;margin-right: 20px;text-align: right;line-height: 130px;"></div>
                    <div style="width: 50%;margin-top: 25px;">
                        <button type="submit" id="comfirm" class="btn btn-primary btn-3F51B5 btn-sure" style="cursor:pointer;" onclick="return false;">确定</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
@endsection

<!---  引入当前页需使用的js  -->
@section("pages-js")

@endsection
@section("js-file")
    <script src="{{ URL::asset('js/merchant/agent/deploy.js')}}"></script>

@endsection
