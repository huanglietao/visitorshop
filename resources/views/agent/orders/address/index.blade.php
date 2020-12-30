<!doctype html>
@extends('layout.iframe')

@section("main-content")
    <link rel="stylesheet" href="{{ URL::asset('css/agent/orders/orders.css') }}">
    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 订单管理 / 管理收货地址' ])
    @endcomponent
    <!-- 面包屑组件end  -->


    <div id="main" style="padding:25px">
        <!--  提示组件 start -->
        @component('component/tips')
            <p style="margin:5px 0 ;padding:0">管理用户收货地址</p>
            <p style="margin:5px 0;padding:0">可对用户自己收货地址进行增加，修改，删除等操作</p>
            <p style="margin:5px 0;padding:0"> </p>
        @endcomponent
        <!--  提示组件 end -->

        <div class="d_well d_well-sm">
            <span class="d_well-img-c"><img src="/images/clipboard.png" alt=""></span>
            <span class="d_well-font">收货地址</span>
        </div>
        <div>
            <span class="oa_new_address_text">新增收货地址</span>
        </div>
        <div class="c_new_address">
            <div class="col-lg-12 form-group c_group">
                <div class="row" style="align-items: baseline !important;">
                    <div class="col-lg-1 col-md-1 oc_label">
                        <span class="c_necessity">     *    </span>
                        <label class="control-label c_label">&nbsp;收货人:</label>
                    </div>
                    <div class="col-lg-2 col-md-3 oc_g_input">
                        <input class="c_input" type="text">
                    </div>
                    <div class="col-lg-1 oc_label">
                        <label class="control-label c_label">&nbsp;邮政编码:</label>
                    </div>
                    <div class="col-lg-2 col-md-3  oc_g_input">
                        <input class="c_input" type="text">
                    </div>
                </div>
            </div>
            <div class="col-lg-12 form-group c_group ">
                <div class="row" style="align-items: baseline !important;">
                    <div class="col-lg-1 col-md-1 oc_label">
                        <span class="c_necessity">     *    </span>
                        <label class="control-label c_label">&nbsp;收货人手机:</label>
                    </div>
                    <div class="col-lg-2 col-md-3  oc_g_input">
                        <input class="c_input" type="text">
                    </div>
                    <div class="col-lg-1 oc_label">
                        <label class="control-label c_label">&nbsp;座机电话:</label>
                    </div>
                    <div class="col-lg-2 col-md-3  oc_g_input">
                        <input class="c_input" type="text">
                    </div>
                </div>
            </div>
            <div class="col-lg-12 form-group c_group">
                <div class="row" style="align-items: baseline !important;">
                    <div class="col-lg-1 oc_label">
                        <span class="c_necessity">     *    </span>
                        <label class="control-label c_label">&nbsp;收货人地区:</label>
                    </div>
                    <div class="col-lg-3 col-md-5 " style="text-align: left">
                        <!-- 省市区组件 宽度:410px,高度:22px-->

                        <div class="areas-one">
                            <select class="areas-province">
                                <option value="0">省</option>
                                <option value="110000">北京</option><option value="120000">天津</option><option value="130000">河北省</option><option value="140000">山西省</option><option value="150000">内蒙古自治区</option><option value="210000">辽宁省</option><option value="220000">吉林省</option><option value="230000">黑龙江省</option><option value="310000">上海</option><option value="320000">江苏省</option><option value="330000">浙江省</option><option value="340000">安徽省</option><option value="350000">福建省</option><option value="360000">江西省</option><option value="370000">山东省</option><option value="410000">河南省</option><option value="420000">湖北省</option><option value="430000">湖南省</option><option value="440000">广东省</option><option value="450000">广西壮族自治区</option><option value="460000">海南省</option><option value="500000">重庆</option><option value="510000">四川省</option><option value="520000">贵州省</option><option value="530000">云南省</option><option value="540000">西藏自治区</option><option value="610000">陕西省</option><option value="620000">甘肃省</option><option value="630000">青海省</option><option value="640000">宁夏回族自治区</option><option value="650000">新疆维吾尔自治区</option><option value="710000">台湾省</option><option value="810000">香港特别行政区</option><option value="820000">澳门特别行政区</option></select>
                            <select class="areas-city">
                                <option value="-1">市</option>
                            </select>
                            <select class="areas-area">
                                <option>区</option>
                            </select>
                        </div>


                    </div>

                </div>
            </div>
            <div class="col-lg-12 form-group c_group">
                <div class="row" style="align-items: baseline !important;">
                    <div class="col-lg-1 oc_label">
                        <span class="c_necessity">     *    </span>
                        <label class="control-label c_label">&nbsp;详细地址:</label>
                    </div>
                    <div class="col-lg-5 col-md-6  oc_g_input oc_g_address">
                        <input class="c_input" type="text">
                    </div>
                </div>
            </div>
            <div class="col-lg-12 form-group c_group">
                <div class="row" style="align-items: baseline !important;">
                    <div class="col-lg-1 oc_label">
                        <span class="c_necessity"></span>
                        <label class="control-label c_label"></label>
                    </div>
                    <div class="col-lg-5 col-md-6  oc_g_input oc_g_address" style="text-align: left">
                        <button class="btn btn-write" style="padding: 3px 15px">保存</button>
                    </div>
                </div>
            </div>

        </div>




        <!-- 列表start  -->
        <div id="table">
            <table class="no-border-table s_table" data-url="/orders/manage_address/table">
                <thead>
                <tr class="s_header_tr"></tr>
                <tr class="table-head">
                    <td>收货人</td>
                    <td>所在地区</td>
                    <td>详细地址</td>
                    <td>邮编</td>
                    <td>电话/手机</td>
                    <td>操作</td>
                    <td></td>
                </tr>
              {{--  <tr class="s_header_tr"></tr>--}}

                </thead>
                <tbody class="tbl-content">

                </tbody>
            </table>
            @component('component/paginate',['limit' => $pageLimit])
            @endcomponent
        </div>
        <!-- 列表end    -->


    </div>
@endsection
@section("js-file")
    <!-- AdminLTE App -->
    <script src="{{ URL::asset('js/agent/orders/orders.js')}}"></script>

@endsection






