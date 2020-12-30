<!DOCTYPE html>
@extends('layout.iframe')

@section("main-content")

    <link rel="stylesheet" href="{{ URL::asset('css/agent/orders/orders.css') }}">
    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '订单管理 / 订单列表 / 提交订单' ])
    @endcomponent
    <!-- 面包屑组件end  -->


    <div id="main" style="padding:25px">
        <!--  提示组件 start -->
        @component('component/tips')
            <p style="margin:5px 0 ;padding:0">订单详情记录订单的基本信息、收货人信息、商品信息、费用信息等。订单分5个大流程分别是提交、支付、发货、收货及评价。</p>
            <p style="margin:5px 0;padding:0">特殊类目订单由于行业特性可能有其它支流程，且该类订单商品拥有特殊子项目信息，可点击展开键查看特殊信息。</p>
            <p style="margin:5px 0;padding:0">分销类商家提交成功的订单是无法修改的（系统会自动确认执行后续处理流程），只能联系商家进行修改。订单售后问题需要确认收货后才能申请售后。</p>
        @endcomponent
        <!--  提示组件 end -->

        @component('component/step',['count' => 5,'now_step'=>'1','subtitle' => ['1' => '提交订单','2' => '支付订单','3'=>'商家发货','4'=>'确认收货','5'=>'评价订单'],'default_color'=>'#bbb','active_color'=>'#259B24' ])
        @endcomponent



        <div class="d_well d_well-sm">
            <span class="d_well-img-c"><img src="/images/clipboard.png" alt=""></span>
            <span class="d_well-font">收货地址</span>
          {{--  <a href="{{ URL::asset('/orders/manage_address/123') }}" class="o_c_manage_address">管理收货地址</a>--}}
        </div>
        <div class="d_o_info_line">
            <div class="d_o_info_item row" >

                @php $i =1; @endphp
            @foreach($addressList as $k => $v)
                    <div class="c_address @if($i>5) c_address_hide c_hide @endif">
                        <input type="hidden" class="province_id" value="{{$v['province']}}">
                        <input type="hidden" class="city_id" value="{{$v['city']}}">
                        <input type="hidden" class="district_id" value="{{$v['district']}}">
                        <input type="hidden" class="ship_tel" value="{{$v['rcv_landline']}}">
                        <input type="hidden" class="ship_zip" value="{{$v['zip_code']}}">
                        <div class="c_address_d" data-addr-id = {{$v['rcv_addr_id']}}>
                            <div class="ca_contacts">
                                <span>
                                    <span class="prov-name" data-id="{{$v['province']}}" data-value="{{$v['prov_name']}}">{{$v['prov_name']}}</span>
                                    <span class="city-name" data-id="{{$v['city']}}" data-value="{{$v['city_name']}}">{{$v['city_name']}}</span>
                                    <span class="rcv-user" data-value="{{$v['rcv_username']}}">（{{$v['rcv_username']}} 收）</span>
                                </span>
                            </div>
                            <div class="ca_add_text">
                                <span><span class="area-name" data-id="{{$v['district']}}" data-value="{{$v['area_name']}}">{{$v['area_name']}}</span> <span class="caa_address">{{$v['rcv_address']}}</span> <span class="caa_phone" data-value="{{$v['rcv_phone']}}">{{$v['rcv_phone']}}</span></span>
                            </div>
                        </div>
                        <div class="c_quarter c_hide">

                        </div>
                        <img class="c_check_img c_hide" src="/images/c_check.png" alt="">

                    </div>
               @php ++$i; @endphp
            @endforeach



            </div>
        </div>

        <div class="c_transition">
            <div class="c_u_new_add">
                <i class="fa fa-plane"></i>
                使用新地址

            </div>
            <span class="c_necessity" style="padding-left: 125px; font-size: 12px; font-weight: normal;">如若填写了新地址则优先使用新地址</span>
            <span class="c_show_add" data-action="show">显示全部地址</span>
            <i class="fa fa-angle-double-down c_angle-double-down"></i>
        </div>

        <div class="c_new_address">
            <div class="col-lg-12 form-group c_group">
                <div class="row" style="align-items: baseline !important;">
                    <div class="col-lg-1 col-md-1 oc_label">
                        <span class="c_necessity">     *    </span>
                        <label class="control-label c_label">&nbsp;收货人:</label>
                    </div>
                    <div class="col-lg-2 col-md-3 oc_g_input" >
                        <input class="c_input doc_new_consignee" type="text">
                    </div>
                    <div class="col-lg-1 oc_label">
                        <label class="control-label c_label">&nbsp;邮政编码:</label>
                    </div>
                    <div class="col-lg-2 col-md-3  oc_g_input" >
                        <input class="c_input doc_new_code" type="text">
                    </div>
                </div>
            </div>
            <div class="col-lg-12 form-group c_group ">
                <div class="row" style="align-items: baseline !important;">
                    <div class="col-lg-1 col-md-1 oc_label">
                        <span class="c_necessity">     *    </span>
                        <label class="control-label c_label">&nbsp;收货人手机:</label>
                    </div>
                    <div class="col-lg-2 col-md-3  oc_g_input" >
                        <input class="c_input doc_new_consignee_phone" type="text">
                    </div>
                    <div class="col-lg-1 oc_label">
                        <label class="control-label c_label">&nbsp;座机电话:</label>
                    </div>
                    <div class="col-lg-2 col-md-3  oc_g_input" >
                        <input class="c_input doc_new_landline" type="text">
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
                        @component('component/areas')

                        @endcomponent
                    </div>

                </div>
            </div>
            <div class="col-lg-12 form-group c_group">
                <div class="row" style="align-items: baseline !important;">
                    <div class="col-lg-1 oc_label">
                        <span class="c_necessity">     *    </span>
                        <label class="control-label c_label">&nbsp;详细地址:</label>
                    </div>
                    <div class="col-lg-5 col-md-6  oc_g_input oc_g_address" >
                        <input class="c_input doc_new_detail_address" type="text">
                    </div>
                </div>
            </div>
        </div>


        <div class="d_well d_well-sm" style="margin-bottom: 1px" >
            <span class="d_well-img-c"><i class="fa fa-archive"></i></span>
            <span class="d_well-font">商品信息</span>
        </div>
        <div id="d_table">
            <table class="no-border-table">
                <thead>
                <tr class="table-head d_table-head">
                    <td>商品名称</td>
                    <td>作品信息</td>
                    <td>商品属性</td>
                    <td>单价</td>
                    <td>重量</td>
                    <td>数量</td>

                    <td>小计</td>
                </tr>
                </thead>
                <tbody class="tbl-content">
                <input type="hidden" class="cart_id" value="{{$cart_id}}">
                <input type="hidden" class="is_fast" value="{{$is_fast}}">
                @foreach($workInfo as $k => $v)
                    <tr>
                        <td class="d_o_attr_goods">
                            <div class="d_o_works_info">
                                <div class="d_o_works_img">
                                    <img src="{{$v['prod_photo']}}">
                                </div>
                                <div class="d_o_works_detail doc_work_detail">
                                    <input type="hidden" class="temp_id" value="{{$v['prod_temp_id']}}">
                                    <input type="hidden" class="proj_id" value="{{$v['proj_id']}}">
                                    <input type="hidden" class="sku_id" value="{{$v['sku_id']}}">
                                    <input type="hidden" class="prod_id" value="{{$v['prod_id']}}">
                                    <p class="d_o_works_name">{{$v['prod_name']}}</p>
                                    <p class="d_o_works_pnum" style="white-space: nowrap;">商品货号：{{$v['prod_sku_sn']}}</p>
                                </div>

                            </div>

                        </td>
                        <td class="d_o_attr_goods">
                            <div class="d_o_works_info">
                                @if(!empty($v['works']))
                                <div class="d_o_works_img">
                                    <img src="/images/1.jpg">
                                </div>
                                <div class="d_o_works_detail" >
                                    <p class="d_o_works_name">{{$v['works']['prj_name']}}</p>
                                    <p class="d_o_works_pnum">作品编号：{{$v['works']['prj_sn']}}</p>
                                </div>
                                    @else
                                    <span style="flex-direction: column;">无作品数据</span>
                                @endif

                            </div>

                        </td>
                        <td class="d_o_attr d_d_o_works_spec_info">

                            <div style="display: inline-block;text-align: left;">
                                @foreach($v['sku_attr'] as $kk =>$vv)
                                    <span class="d_o_works_spec">{{$vv}}<br></span>
                                @endforeach
                            </div>
                        </td>
                        <td class="d_o_attr c_o_attr_price" style="vertical-align: middle;">￥{{$v['sku_price']}}</td>
                        <td class="d_o_attr d_o_attr_weight" style="vertical-align: middle;">
                            <input type="hidden" class="sku_weight" value="{{$v['sku_weight']}}">
                            {{$v['sku_weight']}}克
                        </td>
                        <td class="d_o_attr d_o_attr_num" style="vertical-align: middle;">
                            <input type="hidden" class="prod_num" value="{{$v['prod_num']}}">
                            {{$v['prod_num']}}</td>
                        <td class="d_o_attr d_o_attr_count" style="vertical-align: middle;">
                            ￥{{$v['total_price']}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>

        <div class="d_well d_well-sm">
            <span class="c_well-img-c">
                <img src="/images/g_truck.png" alt="">
            </span>
            <span class="d_well-font">配送信息</span>
        </div>

        <div class="doc_delivery_select"></div>



        <div class="d_well d_well-sm">
            <span class="d_well-img-c"><i class="fa fa-credit-card-alt"></i></span>
            <span class="d_well-font">支付信息</span>
        </div>

        <div class="d_o_info_line">
            <div class="c_d_afford" >
                @foreach($payment as $k => $v)

                    <div class="c_dad @if($v['pay_class_name'] == 'balance')c_active_dad @endif" style="padding: 5px">
                        <input type="hidden" class="pay_id" value="{{$v['pay_id']}}">
                        <input type="hidden" class="pay_classname" value="{{$v['pay_class_name']}}">
                        <img src="{{$v['pay_logo']}}" alt="">
                        <div class="c_dad_text">
                            <span>{{$v['pay_name']}}</span><br>
                            @if($v['pay_class_name'] == 'balance')
                            <span style="color:#5677fc;">￥ {{$balance}}</span>
                             @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{--<div class="d_o_info_line">
            <div class="c_o_invoice" >
                <div class="col-lg-12 form-group c_group">
                    <div class="row" style="align-items: baseline !important;">
                        <div class="col-xl-6 col-md-6 row" style="text-align: left">
                            <div class="col-lg-12 form-group c_group row">
                                <div style="position: relative;text-align: initial;">
                                    <input type="checkbox" id="c_ll" class="checkbox" >
                                    <label for="c_ll" style="margin-right: 10px"></label>
                                    开具发票
                                </div>
                            </div>
                            <div class="c_invoice_item" style="width: 100%;display: none">
                            <div class="col-lg-12 form-group c_group row" style="padding-top: 0">
                                <div class="col-xl-2 col-md-2 c_invoice_item_child">
                                    <label class="control-label c_common_l c_label">发票类型:</label>
                                </div>
                                <div class="col-xl-10 col-md-10 c_common_t c_invoice_item_child2">
                                    电子发票
                                </div>
                            </div>
                            <div class="col-lg-12 form-group c_group row c_coupon_row">
                                <div class="col-xl-2 col-md-2 c_invoice_item_child">
                                    <label class="control-label c_common_l c_label">发票抬头类型:</label>
                                </div>
                                <div class="col-xl-5 col-md-5 c_common_t c_invoice_item_child2">
                                    @component('component/radio',['radio'=>['个人','企业'],'name' => 'radio1','left_distance'=>0,'right_distance'=>10])
                                    @endcomponent
                               --}}{{--     <div class="c_d_radio">
                                        <input type="radio" name="radio1" class="radio" id="radio4">
                                        <label for="radio4" style="margin-right: 10px;margin-left: 0!important;"></label>
                                        个人
                                    </div>
                                    <div class="c_d_radio">
                                        <input type="radio" name="radio1" class="radio" id="radio5">
                                        <label for="radio5" style="margin-right: 10px"></label>
                                        企业
                                    </div>--}}{{--

                                </div>
                            </div>
                            <div class="col-lg-12 form-group c_group row c_coupon_row">
                                <div class="col-xl-2 col-md-2 c_invoice_item_child">
                                    <label class="control-label c_common_l c_label">发票信息:</label>
                                </div>
                                <div class="col-xl-4 col-md-5 c_invoice_item_child2" style="padding-left: 0">
                                    <div class="c_coupon_se">
                                        <select name="" id="">
                                            <option value="">明细</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 form-group c_group row c_coupon_row">
                                    <div class="col-xl-2 col-md-2 c_invoice_item_child">
                                        <label class="control-label c_common_l c_label">发票抬头:</label>
                                    </div>
                                    <div class="col-xl-4 col-md-5 c_common_t c_invoice_item_child2">

                                        <input type="text" class="c_common_i" placeholder="个人">
                                    </div>
                            </div>
                            <div class="col-lg-12 form-group c_group row c_coupon_row ">
                                    <div class="col-xl-2 col-md-2 c_invoice_item_child">
                                        <label class="control-label c_common_l c_label">纳税人识别号:</label>
                                    </div>
                                    <div class="col-xl-4 col-md-5 c_common_t c_invoice_item_child2">

                                        <input type="text" class="c_common_i" placeholder="">
                                    </div>
                            </div>
                            </div>


                        </div>



                        <div class="col-xl-6 col-md-6 row " >
                            <div class="col-lg-12 row c_group">
                                <div class="col-xl-2 col-md-2">
                                    <label class="control-label c_label">优惠券:</label>
                                </div>
                                <div class="col-xl-4 col-md-4 c_coupon_item_child">
                                    <div class="c_coupon_se" style="margin-left: 0">
                                        <select name="" id="">
                                            <option value="">满48元减3元</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-5 col-md-5 c_coupon_item_child2" style="text-align: right">
                                    <span style="color: #91959A">- ￥ 3.00</span>
                                </div>
                            </div>
                            <div class="col-lg-12 row">
                                <div class="col-xl-2 col-md-2">
                                    <label class="control-label c_common_l c_label">留言备注:</label>
                                </div>
                                <div class="col-xl-7 col-md-7" style="padding-left: 15.5px;">
                                    <textarea class="c_common_i cci" placeholder="0/500" style="height: 150px;resize: none;"></textarea>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

              --}}{{--  <div class="col-lg-12 form-group c_group">
                    <div class="row" style="align-items: center !important;">
                        <div class="col-xl-6 col-md-6 row">
                            <div class="col-xl-2 col-md-2">
                                <label class="control-label c_common_l c_label">发票类型:</label>
                            </div>
                            <div class="col-xl-10 col-md-10 c_common_t">
                             电子发票
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6 row c_coupon_row" >
                            <div class="col-xl-2 col-md-2">
                                <label class="control-label c_common_l c_label">留言备注:</label>
                            </div>
                            <div class="col-xl-7 col-md-7">
                                <input type="text" class="c_common_i cci" placeholder="0/500">
                            </div>

                        </div>

                    </div>
                </div>--}}{{--






            </div>
        </div>--}}


            <div class="c_b_information" >
                <p> 共<span class="doc_goods_num">{{$prod_count}}</span> 件商品，共计：<span>￥<span class="doc_goods_total_amount" data-value="{{$totalPrice}}">{{number_format($totalPrice,2)}}</span></span></p>
                <p> + 运费：<span class="doc_delivery_name"></span> <span>￥<span class="doc_delivery_amount"></span></span> </p>
                <div class="c_b_pw">
                    <div class="c_b_price">
                        <input type="hidden" class="create_order_amount" value="">
                        <p>实付金额：<span class="c_b_price_text">￥<span class="doc_order_amount">{{number_format($totalPrice,2)}}</span></span></p>
                        <p>寄送至：
                            <span class="doc_user_address_prov" style="color: #91959A;"></span>
                            <span class="doc_user_address_city" style="color: #91959A;"></span>
                            <span class="doc_user_address_area" style="color: #91959A;"></span>
                            <span class="doc_user_address_detail" style="color: #91959A;"></span>
                        </p>
                        <p>收货人：<span style="color: #91959A;"><span class="doc_rcv_username"></span> <span class="doc_rcv_mobile"></span></span></p>
                    </div>
                </div>

                <div class="c_b_button">
                    <a class="c_button-wrapper-tj" style="cursor: pointer;color: #ffffff">
                        <span class="text">提交订单</span>
                    </a>
                    <a href="{{ URL::asset('/orders/cart') }}" class="c_button-wrapper">
                        <i class="fa fa-mail-reply"></i>
                        <span class="text">返回购物车</span>
                    </a>
                </div>

            </div>

        </div>

    </div>



@endsection

@section("js-file")
    <!-- AdminLTE App -->

    <script src="{{ URL::asset('js/agent/orders/orders.js')}}"></script>

@endsection








