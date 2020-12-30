<!DOCTYPE html>
@extends('layout.iframe')
@inject('CommonPresenter','App\Presenters\CommonPresenter')

@section("main-content")
    <link rel="stylesheet" href="{{ URL::asset('assets/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/agent/orders/orders.css') }}">
    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '订单管理 / 订单列表 / 订单详情' ])
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

        <div class="d_step">
                <div class="d_column">

                    <span class="d_logistic_status">提交订单</span>
                    <div class="d_step_c_circle d_step_circle1">
                        <div class="d_small_active_circle d_small_circle1">
                            @if($data['order_status'] == ORDER_STATUS_WAIT_CONFIRM)
                                <span>1</span>
                            @else
                                <i class="fa fa-check d_check"></i>
                            @endif
                        </div>
                    </div>
                    <span class="d_logistic_time d_logistic_time1">{{$CommonPresenter->exchangeTime($data['order_conf_time'])}}</span>

                    <div class="line1 @if($data['order_status'] >= ORDER_STATUS_WAIT_PAY) active_line @endif"></div>
                    <span class="d_logistic_status" style="left: 24%">支付订单</span>
                    <div class="d_step_c_circle d_step_circle2">
                        <div class="@if($data['order_status'] >= ORDER_STATUS_WAIT_PAY) d_small_active_circle @endif d_small_circle2">
                            @if($data['order_status'] > ORDER_STATUS_WAIT_PAY)
                                <i class="fa fa-check d_check"></i>
                            @else
                                <span>2</span>
                            @endif
                        </div>
                    </div>
                    <span class="d_logistic_time d_logistic_time2">{{$CommonPresenter->exchangeTime($data['order_pay_time'])}}</span>

                    <div class="line2 @if($data['order_status'] >= ORDER_STATUS_WAIT_DELIVERY) active_line @endif"></div>
                    <span class="d_logistic_status" style="left: 49%">商家发货</span>
                    <div class="d_step_c_circle d_step_circle3">
                        <div class="@if($data['order_status'] >= ORDER_STATUS_WAIT_DELIVERY) d_small_active_circle @endif d_small_circle3">
                            @if($data['order_status'] > ORDER_STATUS_WAIT_DELIVERY)
                                <i class="fa fa-check d_check"></i>
                            @else
                                <span>3</span>
                            @endif
                        </div>
                    </div>
                    <span class="d_logistic_time d_logistic_time3">{{$CommonPresenter->exchangeTime($data['order_shipping_time'])}}</span>

                    <div class="line3 @if($data['order_status'] >= ORDER_STATUS_FINISH) active_line @endif"></div>
                    <span class="d_logistic_status" style="left: 74%">确认收货</span>
                    <div class="d_step_c_circle d_step_circle4">
                        <div class=" @if($data['order_status'] >= ORDER_STATUS_FINISH) d_small_active_circle @endif d_small_circle4">
                            @if($data['order_status'] >= ORDER_STATUS_FINISH)
                                <i class="fa fa-check d_check"></i>
                            @else
                                <span>4</span>
                            @endif
                        </div>
                    </div>
                    <span class="d_logistic_time d_logistic_time4" ></span>

                    <div class=" line4 @if($data['order_evaluate_status'] == ORDER_EVALUATED) active_line @endif"></div>
                    <span class="d_logistic_status" style="left: 99%">评价订单</span>
                    <div class="d_step_c_circle d_step_circle5">
                        <div class="@if($data['order_evaluate_status'] == ORDER_EVALUATED) d_small_active_circle @endif d_small_circle4">
                            @if($data['order_evaluate_status'] == ORDER_EVALUATED)
                                <i class="fa fa-check d_check"></i>
                            @else
                                <span>5</span>
                            @endif
                        </div>
                    </div>
                    <span class="d_logistic_time d_logistic_time5"></span>
                </div>
        </div>
        @csrf

        <div class="d_well d_well-sm">
            <span class="d_well-img-c"><img src="/images/clipboard.png" alt=""></span>
            <span class="d_well-font">基本信息</span>
        </div>
        <div class="d_o_info_line">
            <div class="d_o_info_item row" >
                <div class="col-lg-3 form-group">
                    <div class="row">
                        <div class="col-lg-4 col-md-3 d_o_info_item_z">
                            <label class="control-label d_o_info_item_attr">订单编号:</label>
                        </div>
                        <div class="col-lg-8 col-md-9 d_o_info_item_val">
                            {{$data['order_no']}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 form-group">
                    <div class="row">
                        <div class="col-lg-4 col-md-3 d_o_info_item_z">
                            <label class="control-label d_o_info_item_attr">实收金额:</label>
                        </div>
                        <div class="col-lg-8 col-md-9 d_o_info_item_val">
                            ￥ {{$data['order_real_total']}} 元（含运费：￥ {{$data['order_exp_fee']}} 元）
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 form-group">
                    <div class="row">
                        <div class="col-lg-4 col-md-3 d_o_info_item_z" >
                            <label class="control-label d_o_info_item_attr">关联单号:</label>
                        </div>
                        <div class="col-lg-8 col-md-9 d_o_info_item_val">
                            {{$data['order_relation_no']}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 form-group">
                    <div class="row">
                        <div class="col-lg-4 col-md-3 d_o_info_item_z">
                            <label class="control-label d_o_info_item_attr">支付流水号:</label>
                        </div>
                        <div class="col-lg-8 col-md-9 d_o_info_item_val">
                            {{$data['outer_trade_no']}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 form-group">
                    <div class="row">
                        <div class="col-lg-4 col-md-3 d_o_info_item_z">
                            <label class="control-label d_o_info_item_attr">订单状态:</label>
                        </div>
                        <div class="col-lg-8 col-md-9 d_o_info_item_val">
                            {{$CommonPresenter->exchangeOrderStatus($data['order_status'])}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 form-group">
                    <div class="row">
                        <div class="col-lg-4 col-md-3 d_o_info_item_z">
                            <label class="control-label d_o_info_item_attr">支付状态:</label>
                        </div>
                        <div class="col-lg-8 col-md-9 d_o_info_item_val">
                            {{$CommonPresenter->exchangePayStatus($data['order_pay_status'])}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 form-group">
                    <div class="row">
                        <div class="col-lg-4 col-md-3 d_o_info_item_z">
                            <label class="control-label d_o_info_item_attr">确认状态:</label>
                        </div>
                        <div class="col-lg-8 col-md-9 d_o_info_item_val">
                            {{$CommonPresenter->exchangeConfirmStatus($data['order_comf_status'])}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 form-group">
                    <div class="row">
                        <div class="col-lg-4 col-md-3 d_o_info_item_z">
                            <label class="control-label d_o_info_item_attr">发货状态:</label>
                        </div>
                        <div class="col-lg-8 col-md-9 d_o_info_item_val">
                            {{$CommonPresenter->exchangeDeliveryStatus($data['order_shipping_status'])}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 form-group">
                    <div class="row">
                        <div class="col-lg-4 col-md-3 d_o_info_item_z">
                            <label class="control-label d_o_info_item_attr">创建时间:</label>
                        </div>
                        <div class="col-lg-8 col-md-9 d_o_info_item_val">
                            {{$data['created_at']}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 form-group">
                    <div class="row">
                        <div class="col-lg-4 col-md-3 d_o_info_item_z">
                            <label class="control-label d_o_info_item_attr">支付时间:</label>
                        </div>
                        <div class="col-lg-8 col-md-9 d_o_info_item_val">
                            {{$CommonPresenter->exchangeTime($data['order_pay_time'])}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 form-group">
                    <div class="row">
                        <div class="col-lg-4 col-md-3 d_o_info_item_z">
                            <label class="control-label d_o_info_item_attr">发货时间:</label>
                        </div>
                        <div class="col-lg-8 col-md-9 d_o_info_item_val">
                            {{$CommonPresenter->exchangeTime($data['order_shipping_time'])}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 form-group">
                    <div class="row">
                        <div class="col-lg-4 col-md-3 d_o_info_item_z">
                            <label class="control-label d_o_info_item_attr">确认时间:</label>
                        </div>
                        <div class="col-lg-8 col-md-9 d_o_info_item_val">
                            {{$CommonPresenter->exchangeTime($data['order_conf_time'])}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 form-group">
                    <div class="row">
                        <div class="col-lg-4 col-md-3 d_o_info_item_z">
                            <label class="control-label d_o_info_item_attr">支付方式:</label>
                        </div>
                        <div class="col-lg-8 col-md-9 d_o_info_item_val">
                            {{$data['pay_name']}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 form-group">
                    <div class="row">
                        <div class="col-lg-4 col-md-3 d_o_info_item_z">
                            <label class="control-label d_o_info_item_attr">物流方式:</label>
                        </div>
                        <div class="col-lg-8 col-md-9 d_o_info_item_val">
                            {{$data['delivery_name']}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 form-group">
                    <div class="row">
                        <div class="col-lg-4 col-md-3 d_o_info_item_z">
                            <label class="control-label d_o_info_item_attr">下单人:</label>
                        </div>
                        <div class="col-lg-8 col-md-9 d_o_info_item_val">
                            {{$data['agent_name']}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 form-group">
                    <div class="row">
                        <div class="col-lg-4 col-md-3 d_o_info_item_z">
                            <label class="control-label d_o_info_item_attr">联系电话:</label>
                        </div>
                        <div class="col-lg-8 col-md-9 d_o_info_item_val">
                            {{$data['order_rcv_phone']}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 form-group">
                    <div class="row">
                        <div class="col-lg-4 col-md-3 d_o_info_item_z">
                            <label class="control-label d_o_info_item_attr">订单备注:</label>
                        </div>
                        <div class="col-lg-8 col-md-9 d_o_info_item_val">
                            {{$data['order_remark_user']}}
                        </div>
                    </div>
                </div>


            </div>
        </div>

        <div class="d_well d_well-sm">
            <span class="d_well-img-c"><img src="/images/address-card.png" alt=""></span>
            <span class="d_well-font">收货人信息</span>
        </div>
        <div class="d_o_info_line">
            <div class="d_o_info_item row" >
                <div class="col-lg-3 form-group">
                    <div class="row">
                        <div class="col-lg-4 col-md-3 d_o_info_item_z">
                            <label class="control-label d_o_info_item_attr">收货人:</label>
                        </div>
                        <div class="col-lg-8 col-md-9 d_o_info_item_val">
                            {{$data['order_rcv_user']}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 form-group">
                    <div class="row">
                        <div class="col-lg-4 col-md-3 d_o_info_item_z">
                            <label class="control-label d_o_info_item_attr">联系手机:</label>
                        </div>
                        <div class="col-lg-8 col-md-9 d_o_info_item_val">
                            {{$data['order_rcv_phone']}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 form-group">
                    <div class="row">
                        <div class="col-lg-4 col-md-3 d_o_info_item_z" >
                            <label class="control-label d_o_info_item_attr">联系电话:</label>
                        </div>
                        <div class="col-lg-8 col-md-9 d_o_info_item_val">
                            {{$data['order_rcv_phone']}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 form-group dis_group">

                </div>
                {{--保证对齐--}}
                <div class="col-lg-3 form-group">
                    <div class="row">
                        <div class="col-lg-4 col-md-3  d_o_info_item_z">
                            <label class="control-label d_o_info_item_attr">收货地址:</label>
                        </div>
                        <div class="col-lg-8 col-md-9 d_o_info_item_val" >
                            {{$data['province_name']}}  {{$data['city_name']}}  {{$data['area_name']}}  {{$data['order_rcv_address']}}
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 form-group dis_group"></div>
                <div class="col-lg-3 form-group dis_group"></div>
                <div class="col-lg-3 form-group dis_group"></div>
                {{--保证对齐--}}
                <div class="col-lg-3 form-group">
                    <div class="row">
                        <div class="col-lg-4 col-md-3 d_o_info_item_z" >
                            <label class="control-label d_o_info_item_attr">邮编:</label>
                        </div>
                        <div class="col-lg-8 col-md-9 d_o_info_item_val">
                            {{$data['order_rcv_zipcode']}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 form-group">
                    <div class="row">
                        <div class="col-lg-4 col-md-3 d_o_info_item_z">
                            <label class="control-label d_o_info_item_attr">物流单号:</label>
                        </div>
                        <div class="col-lg-8 col-md-9 d_o_info_item_val">
                            @if(!empty($data['delivery_code']))
                                {{$data['delivery_code']}}
                                <a class="btn-dialog" data-url="{{URL::asset('/order/list/logistics/'.$data['order_id'])}}" data-title="物流信息" style="cursor: pointer;color: #007BFF;margin-left: 10px;">跟踪物流信息</a>
                            @endif
                        </div>
                    </div>
                </div>


            </div>
        </div>

        <div class="d_well d_well-sm" style="margin-bottom: 1px" >
            <span class="d_well-img-c"><i class="fa fa-archive"></i></span>
            <span class="d_well-font">商品信息</span>
        </div>
        <div>
            <input type="checkbox" class="checkall-1 checkbox" id="all">
            <label for="all" style="margin-right: 10px"></label>
            <span class="o_checkall-text">全选/反选</span>
        </div>
        <input hidden type="text" class="prj_ids"/>
        @foreach($data['prod_info'] as $k=>$v)
            <div id="d_table">
                <table class="no-border-table">
                    <thead>
                    <tr class="table-head d_table-head">
                        <td>商品名称</td>
                        <td>商品属性</td>
                        <td>项目号</td>
                        <td>单价</td>
                        <td>数量</td>
                        <td>优惠/折扣</td>
                        <td>重量</td>
                        <td>小计</td>
                        <td>操作</td>
                    </tr>
                    </thead>
                    <tbody class="tbl-content">
                    <tr>
                        <td class="d_o_attr_goods">
                            <div class="d_o_works_info">
                                <div style="display: inline-block;">
                                    <input type="checkbox" name='checkworks' class="checkedres checkbox" id="{{$v['prj_id']}}" @if($v['prj_type'] == WORKS_FILE_TYPE_UPLOAD) data-value="{{$v['prj_id']}}U" @elseif($v['prj_type'] == WORKS_FILE_TYPE_EMPTY) data-value="{{$v['prj_id']}}-{{$v['ord_prod_id']}}" @else data-value="{{$v['prj_id']}}" @endif/>
                                    <label for="{{$v['prj_id']}}" style="margin-right: 10px"></label>
                                </div>
                                <div class="d_o_works_img">
                                    <img src="{{$v['prod_main_thumb']}}">
                                </div>
                                <div class="d_o_works_detail">
                                    <p class="d_o_works_name">{{$v['prod_name']}}</p>
                                    <p class="d_o_works_pnum">商品货号：{{$v['sku_sn']}}</p>
                                </div>
                            </div>
                        </td>
                        <td class="d_o_attr d_d_o_works_spec_info"> <p class="d_o_works_spec">{{$v['prod_attr_str']}}</p></td>
                        <td class="d_o_attr d_o_attr_sn">{{$v['ord_prj_item_no']}}</td>
                        <td class="d_o_attr d_o_attr_price">￥{{$v['prod_sku_price']}}</td>
                        <td class="d_o_attr d_o_attr_num">{{$v['prod_num']}}</td>
                        <td class="d_o_attr d_o_attr_sale">{{$v['cou_name']}}</td>

                        <td class="d_o_attr d_o_attr_weight">
                            {{$v['prod_sku_weight']}}
                        </td>
                        <td class="d_o_attr d_o_attr_count">
                            ￥{{$v['subtotal']}}
                        </td>
                        <td class="d_o_attr d_o_attr_other">
{{--                            @if($v['prj_type'] != WORKS_FILE_TYPE_EMPTY)--}}
                                <p>
                                    <span @if($sync_sdk==0) class="btn-check" @else class="btn-tongbu-check" @endif @if($v['prj_type'] == WORKS_FILE_TYPE_UPLOAD) data-id="{{$v['prj_id']}}U" @elseif($v['prj_type'] == WORKS_FILE_TYPE_EMPTY) data-id="{{$v['prj_id']}}-{{$v['ord_prod_id']}}" @else data-id="{{$v['prj_id']}}" @endif style="cursor: pointer;color: #007bff">
                                        再次订购
                                    </span>
                                </p>
                            {{--@endif--}}
                            @if($v['prj_type'] != WORKS_FILE_TYPE_EMPTY)
                                <div class="double-d" onclick="showWork(this)">
                                    <img src="/images/chevron-double-down.png" alt="" data-action="show">
                                </div>
                            @endif
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            @if($v['prj_type'] != WORKS_FILE_TYPE_EMPTY)
                <div class="d_transition" style="display: none;">
                    <div class="d_children_goods">
                        <div class="d_o_attr_goods">
                            <div class="d_o_works_info">
                                <div class="d_o_works_img"  style="width: 80px;height: 80px;">
                                    @if($v['prj_type'] != WORKS_FILE_TYPE_UPLOAD)<img src="{{$v['prj_image']}}">@endif
                                </div>
                                <div class="d_o_works_detail">
                                    <p class="o_cg_name"> @if($v['prj_type'] != WORKS_FILE_TYPE_UPLOAD){{$v['prj_name']}}@endif</p>
                                    <p class="o_cg_pnum"> @if($v['prj_type'] != WORKS_FILE_TYPE_UPLOAD)作品号：{{$v['prj_sn']}}@endif</p>
                                </div>
                            </div>
                        </div>
                        <span class="d_cg_supplier">{{$v['sp_name']}}</span>
                        <span class="d_cg_compound">{{$v['pro_type']}}</span>
                        <span class="d_cg_examine">
                              @if($v['prj_type'] == WORKS_FILE_TYPE_DIY)
                                <a href="/order/list/check/{{$v['ord_prj_item_no']}}" target="_blank">审核文件</a>
                            @else
                                @if($v['pro_handel_type'] == WORKS_HANDEL_TYPE_PROCESSED)
                                    <a href="###" class="downloadFile" onclick="return false" data-value="{{$v['ord_prod_id']}}">下载文件</a>
                                @endif
                            @endif
                        </span>
                    </div>
                </div>
            @endif
        @endforeach
{{--        @if($v['prj_type'] != WORKS_FILE_TYPE_EMPTY)--}}
            <div style="height: 50px;border-bottom:1px solid #f5f5f5;border-left:1px solid #f5f5f5;border-right:1px solid #f5f5f5;display: flex;align-items:center;justify-content: center">
                <button @if($sync_sdk==0) class="btn btn-write btn-check" @else class="btn btn-write  btn-tongbu-check" @endif><i class="fa fa-check"></i> 批量订购</button>
            </div>
        {{--@endif--}}


        <div class="d_well d_well-sm">
            <span class="d_well-img-c"><i class="fa fa-credit-card-alt"></i></span>
            <span class="d_well-font">费用信息</span>
        </div>
        <div class="d_o_info_line">
            <div class="d_o_info_item row" >
                <div class="col-lg-3 form-group">
                    <div class="row">
                        <div class="col-lg-4 col-md-3 d_o_info_item_z">
                            <label class="control-label d_o_info_item_attr">订单总金额:</label>
                        </div>
                        <div class="col-lg-8 col-md-9 d_o_info_item_val">
                            ￥ {{$data['order_real_total']}}
                            <span class="o_info_compute" >=</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 form-group">
                    <div class="row">
                        <div class="col-lg-4 col-md-3 d_o_info_item_z">
                            <label class="control-label d_o_info_item_attr">商品总额:</label>
                        </div>
                        <div class="col-lg-8 col-md-9 d_o_info_item_val">
                            ￥ {{$data['prod_amount']}}
                            <span class="o_info_compute" >+</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 form-group">
                    <div class="row">
                        <div class="col-lg-4 col-md-3 d_o_info_item_z" >
                            <label class="control-label d_o_info_item_attr">运费:</label>
                        </div>
                        <div class="col-lg-8 col-md-9 d_o_info_item_val">
                            ￥ {{$data['order_exp_fee']}}
                            <span class="o_info_compute" >-</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 form-group">
                    <div class="row">
                        <div class="col-lg-4 col-md-3 d_o_info_item_z">
                            <label class="control-label d_o_info_item_attr">折扣:</label>
                        </div>
                        <div class="col-lg-8 col-md-9 d_o_info_item_val">
                            ￥ {{$data['discount_amount']}}
                        </div>
                    </div>
                </div>


            </div>
        </div>

        <div class="d_o_info_line">
            <div class="d_o_info_operate" >
                @if($data['order_status'] == ORDER_STATUS_WAIT_RECEIVE && $data['order_pay_status'] == ORDER_PAYED && $data['order_comf_status'] == ORDER_CONFIRMED)
                    <button class="btn btn-write od_receiving btn-receiver" data-value="{{$data['order_id']}}" data-title = "确认收货">确认收货</button>
                @endif
                @if($data['order_status'] == ORDER_STATUS_FINISH)
                    {{--确认收货后才可申请售后和评价订单--}}
{{--                    <button class="btn btn-write od_evaluate btn-dialog" data-url="{{URL::asset('/order/test')}}" data-title = "评价订单">评价订单</button>--}}
                    <button class="btn btn-write  od_evaluate"><a href="{{URL::asset('/order/service')}}" style="color: #222;">申请售后</a></button>

                        {{--<button class="btn btn-write od_aftersale  btn-dialog"   data-url="{{URL::asset('/order/service')}}" data-title = "申请售后">申请售后</button>--}}
                @endif
                <button class="btn btn-write od_return_list">返回</button>


            </div>
        </div>
{{--
        <div class="d_o_info_line">
        <div class="d_footer-tips">
            <p>
                确认收货：只有在订单已支付、已确认、已发货状态下才能出现操作（货到付款除外）<br>
                评价订单：只有在确认收货后才能操作，评价是针对订单商品进行的<br>
                申请售后：只有在确认收货后才能操作售后功能，售后完的订单也是可以评价
            </p>
        </div>
        </div>--}}

    </div>
@endsection

@section("js-file")
    <!-- AdminLTE App -->
    <script src="{{ URL::asset('assets/daterangepicker/moment.min.js')}}"></script>
    <script src="{{ URL::asset('assets/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{ URL::asset('js/agent/orders/orders.js')}}"></script>
    <script src="{{ URL::asset('js/agent/works/works.js')}}"></script>
@endsection

@section("pages-js")
    function showWork(obj) {
        var action = $(obj).find("img").attr("data-action");
        if (action == "hide")
        {
            $(obj).find("img").attr("src","/images/chevron-double-down.png");
            $(obj).find("img").attr("data-action","show");
            $(obj).parent().parent().parent().parent().parent().next().slideUp(500);
        }else{
            $(obj).find("img").attr("src","/images/chevron-double-up.png")
            $(obj).find("img").attr("data-action","hide");
            $(obj).parent().parent().parent().parent().parent().next().slideDown(500);
        }
    }
@endsection








