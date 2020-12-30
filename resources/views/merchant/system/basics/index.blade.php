@extends('layout.mch_iframe')

@section("main-content")

    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 系统设置 / 基础配置' ])
    @endcomponent
    <!-- 面包屑组件end  -->

    <div id="main">
        <!--  提示组件 start -->
        @component('component/tips')
            <p style="margin:5px 0 ;padding:0">该页面可以修改当前系统的变量。</p>
        @endcomponent
        <!--  提示组件 end -->

        <div  style="width: 100%;display: flex;flex-direction: column;padding-top: 50px;font-size: 12px;color: #797777;padding-left: 30px;">
            <form class="form-horizontal common-form" id="form-save" method="post" action="/system/basics/save" onsubmit="return false;" autocomplete="off">
                <input type=hidden value="{{$row['oms_set_id']}}" name="oms_set_id" id="id">
                <input type=hidden value="{{$id}}" name="mch_id" id="id">
                <div class="form-group row form-item">
                    <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                        <span style="color:red">*</span> 商户管理平台名称：</label>
                    <div class="col-xs-12 col-sm-10">
                        <div class="row">
                            <input  id="oms_name" class="form-control col-sm-5" name="oms_name" type="text" value="{{$row['oms_name']}}" placeholder="" data-rule="商户管理平台名称:required">
                            <span class="col-sm-7 color-6A6969">当前系统站点名，进入系统左上角显示的名称。</span>
                        </div>
                        <span class="msg-box" style="position:static;" for="oms_name"></span>
                    </div>
                </div>
                <div class="form-group row form-item">
                    <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                        <span style="color:red">*</span> 分销管理平台名称：</label>
                    <div class="col-xs-12 col-sm-10">
                        <div class="row">
                            <input  id="dms_name" class="form-control col-sm-5" name="dms_name" type="text" value="{{$row['dms_name']}}" placeholder="" data-rule="分销管理平台名称:required">
                            <span class="col-sm-7 color-6A6969">分销管理品台进入系统左上角显示的名称。</span>
                        </div>
                        <span class="msg-box" style="position:static;" for="dms_name"></span>
                    </div>
                </div>
                <div class="form-group row form-item">
                    <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">备案号：</label>
                    <div class="col-xs-12 col-sm-10">
                        <div class="row">
                            <input  id="oms_record_num" class="form-control col-sm-5" name="oms_record_num" type="text" value="{{$row['oms_record_num']}}" placeholder="">
                            <span class="col-sm-7 color-6A6969"> 各系统站点底部备案号。</span>
                        </div>
                        <span class="msg-box" style="position:static;" for="oms_record_num"></span>
                    </div>
                </div>
                <div class="form-group row form-item">
                    <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">版权信息：</label>
                    <div class="col-xs-12 col-sm-10">
                        <div class="row">
                            <input  id="oms_copyright" class="form-control col-sm-5" name="oms_copyright" type="text" value="{{$row['oms_copyright']}}" placeholder="">
                            <span class="col-sm-7 color-6A6969"> 各系统站点底部版权信息。</span>
                        </div>
                        <span class="msg-box" style="position:static;" for="oms_copyright"></span>
                    </div>
                </div>
                <div class="form-group row form-item">
                    <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">联系人：</label>
                    <div class="col-xs-12 col-sm-10">
                        <div class="row">
                            <input  id="oms_linkman" class="form-control col-sm-5" name="oms_linkman" type="text" value="{{$row['oms_linkman']}}"  placeholder="">
                            <span class="col-sm-7 color-6A6969"> 联系人。</span>
                        </div>
                        <span class="msg-box" style="position:static;" for="oms_linkman"></span>
                    </div>
                </div>
                <div class="form-group row form-item">
                    <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">联系方式：</label>
                    <div class="col-xs-12 col-sm-10">
                        <div class="row">
                            <input  id="oms_mobile" class="form-control col-sm-5" name="oms_mobile" type="text" value="{{$row['oms_mobile']}}" placeholder="">
                            <span class="col-sm-7 color-6A6969"> 企业的联系方式。</span>
                        </div>
                        <span class="msg-box" style="position:static;" for="oms_mobile"></span>
                    </div>
                </div>
                <div class="form-group row form-item">
                    <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">联系地址：</label>
                    <div class="col-xs-12 col-sm-10">
                        <div class="row">
                            <input  id="oms_address" class="form-control col-sm-5" name="oms_address" type="text" value="{{$row['oms_address']}}" placeholder="">
                            <span class="col-sm-7 color-6A6969"> 企业的详细地址信息。</span>
                        </div>
                        <span class="msg-box" style="position:static;" for="oms_address"></span>
                    </div>
                </div>
                <div class="form-group row form-item">
                    <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">余额提醒阈值：</label>
                    <div class="col-xs-12 col-sm-10">
                        <div class="row">
                            <input  id="oms_balance_reminder" class="form-control col-sm-5" name="oms_balance_reminder" type="text" value="{{$row['oms_balance_reminder']}}"  placeholder="">
                            <span class="col-sm-7 color-6A6969"> 当余额低于该值时发起通知。</span>
                        </div>
                        <span class="msg-box" style="position:static;" for="oms_balance_reminder"></span>
                    </div>
                </div>

                <div class="form-group row form-item" style="padding-top: 3%;">
                    <label class="control-label col-xs-12 col-sm-2"></label>
                    <div class="col-xs-12 col-sm-10">
                        <button type="submit" id="comfirm"  class="btn btn-primary btn-3F51B5 btn-sure" onclick="return false;">确定</button>
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
    <script src="{{ URL::asset('js/backend/system.js')}}"></script>

@endsection
