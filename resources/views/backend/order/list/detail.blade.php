<!DOCTYPE html>
@extends('layout.iframe')
@inject('CommonPresenter','App\Presenters\CommonPresenter')

@section("main-content")
    <link rel="stylesheet" href="{{ URL::asset('assets/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{URL::asset('css/backend/order/list.css')}}">

    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '订单管理/订单列表/订单详情' ])
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

        <div class="d_well d_well-sm">
            <span class="d_well-img-c"><img src="/images/cms-clipboard.jpg" alt=""></span>
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
                            @if($data['order_prod_status'] == ORDER_NO_PRODUCE && $data['order_status'] != ORDER_STATUS_CANCEL)
                                <span style="cursor: pointer;margin-left: 10px;">
                                    <a class="btn-dialog" data-url="{{URL::asset('/order/change_delivery/'.$data['order_id'])}}" data-title = "修改物流方式">
                                        <i class="fa fa-pencil-square fa-lg"></i>
                                    </a>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 form-group">
                    <div class="row">
                        <div class="col-lg-4 col-md-3 d_o_info_item_z">
                            <label class="control-label d_o_info_item_attr">订单来源:</label>
                        </div>
                        <div class="col-lg-8 col-md-9 d_o_info_item_val">
                            {{$data['agent_name']}}【{{$data['cha_name']}}】
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 form-group">
                    <div class="row">
                        <div class="col-lg-4 col-md-3 d_o_info_item_z">
                            <label class="control-label d_o_info_item_attr">购买人信息:</label>
                        </div>
                        <div class="col-lg-8 col-md-9 d_o_info_item_val">
                            -
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
            <span class="d_well-img-c"><img src="/images/cms-address-card.jpg" alt=""></span>
            <span class="d_well-font">收货人信息</span>
                @if($data['order_prod_status'] == ORDER_NO_PRODUCE && $data['order_status'] != ORDER_STATUS_CANCEL)
                <span style="cursor: pointer;margin-left: 10px;">
                    <a class="btn-dialog" data-url="{{URL::asset('/order/reciver/'.$data['order_id'])}}" data-title = "修改收货人信息">
                        <i class="fa fa-pencil-square fa-lg"></i>
                    </a>
                </span>
            @endif
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
                    <div class="row">
                        <div class="col-lg-4 col-md-3 d_o_info_item_z" >
                            <label class="control-label d_o_info_item_attr">邮编:</label>
                        </div>
                        <div class="col-lg-8 col-md-9 d_o_info_item_val">
                            {{$data['order_rcv_zipcode']}}
                        </div>
                    </div>
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
                <div class="col-lg-3 form-group">
                    <div class="row">
                        <div class="col-lg-4 col-md-3 d_o_info_item_z" >
                            <label class="control-label d_o_info_item_attr">送货时间:</label>
                        </div>
                        <div class="col-lg-8 col-md-9 d_o_info_item_val">
                            {{$CommonPresenter->exchangeTime($data['order_shipping_time'])}}
                        </div>
                    </div>
                </div>


            </div>
        </div>

        <div class="d_well d_well-sm" style="margin-bottom: 1px" >
            <span class="d_well-img-c"><i class="fa fa-archive"></i></span>
            <span class="d_well-font">商品信息</span>
        </div>

        @foreach($data['prod_info'] as $k=>$v)
            <div id="d_table">
                <table class="no-border-table">
                    <thead>
                    <tr class="table-head d_table-head" style="background: white;">
                        <td>商品名称</td>
                        <td>商品属性</td>
                        <td>项目号</td>
                        <td>单价</td>
                        <td>数量</td>
                        <td>优惠/折扣</td>
                        <td>重量(克)</td>
                        <td>小计</td>
                        <td></td>
                    </tr>
                    </thead>
                    <tbody class="tbl-content">
                    <tr>
                        <td class="d_o_attr_goods">
                            <div class="d_o_works_info">
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
                            @if($v['prj_type'] != WORKS_FILE_TYPE_EMPTY)
                                <div class="double-d" onclick="showWork(this)">
                                    <img src="/images/cms-chevron-double-down.png" alt="" data-action = "show">
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
                                <div class="d_o_works_img" style="width: 80px;height: 80px;">
                                    @if($v['prj_type'] != WORKS_FILE_TYPE_UPLOAD)<img src="{{$v['prj_image']}}">@endif
                                </div>
                                <div class="d_o_works_detail">
                                    <p class="o_cg_name"> @if($v['prj_type'] != WORKS_FILE_TYPE_UPLOAD){{$v['prj_name']}}@endif</p>
                                    <p class="o_cg_pnum"> @if($v['prj_type'] != WORKS_FILE_TYPE_UPLOAD)作品号：{{$v['prj_sn']}}@endif</p>
                                </div>
                            </div>
                        </div>
                        <span class="d_cg_p">{{$v['prj_page_num']}}P</span>
                        <span class="d_cg_nickname">买家昵称：{{$v['buyer_nickname']}}</span>
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
                        @if($v['pro_handel_type'] == WORKS_HANDEL_TYPE_PROCESSED && isset($v['manuscript']))
                            <span class="d_cg_manuscript">
                                @foreach($v['manuscript'] as $key=>$val)
                                    @if($val['filetype'] == GOODS_SIZE_TYPE_COVER)
                                        <a href="/order/list/manuscript?url={{$val['url']}}" target="_blank" style="margin-bottom: 10px">封面下载</a>
                                    @else
                                        <a href="/order/list/manuscript?url={{$val['url']}}" target="_blank">内页下载</a>
                                    @endif
                                @endforeach
                            </span>
                        @endif
                    </div>
                </div>
            @endif
        @endforeach

        <div class="d_well d_well-sm">
            <span class="d_well-img-c"><i class="fa fa-credit-card-alt"></i></span>
            <span class="d_well-font">费用信息</span>
        </div>
        <div class="d_o_info_line">
            <div class="d_o_info_item row" >
                <div class="col-lg-3 col-md-3 d_o_info_invoice">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 d_o_info_invoice-left" style="padding-top: 5px !important;">发票类型：</div>
                            <div class="col-lg-8 col-md-8 d_o_info_invoice-right">
                                @if($data['order_bill_id'] != 0){{$CommonPresenter->exchangeInvoice($data['inv_info']['inv_type'],'inv_type')}}@else - @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 d_o_info_invoice-left" style="padding-top: 5px !important;">发票信息：</div>
                            <div class="col-lg-8 col-md-8 d_o_info_invoice-right">
                                @if($data['order_bill_id'] != 0){{$CommonPresenter->exchangeInvoice($data['inv_info']['inv_info'],'inv_info')}} @else - @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 d_o_info_invoice-left" style="padding-top: 5px !important;">发票抬头类型：</div>
                            <div class="col-lg-8 col-md-8 d_o_info_invoice-right">
                                @if($data['order_bill_id'] != 0){{$CommonPresenter->exchangeInvoice($data['inv_info']['user_type'],'user_type')}} @else - @endif

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 d_o_info_invoice-left" style="padding-top: 5px !important;">发票抬头：</div>
                            <div class="col-lg-8 col-md-8 d_o_info_invoice-right">
                                @if($data['order_bill_id'] != 0){{$data['inv_info']['inv_title']}} @else - @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 d_o_info_invoice-left" style="padding-top: 5px !important;">纳税人识别号：</div>
                            <div class="col-lg-8 col-md-8 d_o_info_invoice-right">
                                @if($data['order_bill_id'] != 0){{$data['inv_info']['inv_taxer_no']}} @else - @endif
                            </div>
                        </div>
                </div>
                <div class="col-lg-9 col-md-9 d_o_info_right">
                    <div class="d_o_info_right_content">
                        <div class="d_o_info_right_content_top">商品金额：</div>
                        <div class="row">
                            <input class="form-control col-lg-8 col-md-8" value="{{$data['prod_amount']}}">
                            <span class="col-lg-4 col-md-4 d_o_info_right_content_top_span">+</span>
                        </div>
                        <div class="d_o_info_right_content_bottom">使用余额：</div>
                        <div class="row">
                            <input class="form-control col-lg-8 col-md-8" value="0.00">
                            <span class="col-lg-4 col-md-4 d_o_info_right_content_top_span">-</span>
                        </div>
                    </div>
                    <div class="d_o_info_right_content">
                        <div class="d_o_info_right_content_top">发票税额：</div>
                        <div class="row">
                            <input class="form-control col-lg-8 col-md-8" value="@if(empty($data['inv_info']['inv_amount'])) 0.00 @else{{$data['inv_info']['inv_amount']}}@endif">
                            <span class="col-lg-4 col-md-4 d_o_info_right_content_top_span">+</span>
                        </div>
                        <div class="d_o_info_right_content_bottom">使用积分：</div>
                        <div class="row">
                            <input class="form-control col-lg-8 col-md-8" value="0.00">
                            <span class="col-lg-4 col-md-4 d_o_info_right_content_top_span">-</span>
                        </div>
                    </div>
                    <div class="d_o_info_right_content">
                        <div class="d_o_info_right_content_top">配送费用：</div>
                        <div class="row">
                            <input class="form-control col-lg-8 col-md-8" value="@if(empty($data['order_exp_fee'])) 0.00 @else{{$data['order_exp_fee']}}@endif">
                            <span class="col-lg-4 col-md-4 d_o_info_right_content_top_span">+</span>
                        </div>
                        <div class="d_o_info_right_content_bottom">使用红包：</div>
                        <div class="row">
                            <input class="form-control col-lg-8 col-md-8" value="0.00">
                            <span class="col-lg-4 col-md-4 d_o_info_right_content_top_span">-</span>
                        </div>
                    </div>
                    <div class="d_o_info_right_content">
                        <div class="d_o_info_right_content_top">支付费用：</div>
                        <div class="row">
                            <input class="form-control col-lg-8 col-md-8" value="0.00">
                            <span class="col-lg-4 col-md-4 d_o_info_right_content_top_span">+</span>
                        </div>
                        <div class="d_o_info_right_content_bottom">使用优惠劵：</div>
                        <div class="row">
                            <input class="form-control col-lg-8 col-md-8" value="{{$data['discount_amount']}}">
                            <span class="col-lg-4 col-md-4 d_o_info_right_content_top_span">-</span>
                        </div>
                    </div>
                    <div class="d_o_info_right_content">
                        <div class="d_o_info_right_content_top">折扣：</div>
                        <div class="row">
                            <input class="form-control col-lg-8 col-md-8" value="0.00">
                            <span class="col-lg-4 col-md-4 d_o_info_right_content_top_span"></span>
                        </div>
                        <div class="d_o_info_right_content_bottom">使用储值卡：</div>
                        <div class="row">
                            <input class="form-control col-lg-8 col-md-8" value="0.00">
                            <span class="col-lg-4 col-md-4 d_o_info_right_content_top_span">-</span>
                        </div>
                    </div>
                    <div class="d_o_info_right_content_two">
                        <div class="row d_o_info_right_content_two_row">
                            <input class="form-control col-lg-8 col-md-8" value="0.00">
                            <span class="col-lg-4 col-md-4 d_o_info_right_content_top_span"></span>
                        </div>
                        <div class="d_o_info_right_content_two_text">已付金额：</div>
                    </div>
                    <div class="d_o_info_right_content_three">
                        <div class="d_o_info_right_content_three_text">订单总金额：</div>
                        <div class="d_o_info_right_content_three_amount">￥{{$data['order_real_total']}}</div>
                        <div class="d_o_info_right_content_three_texts">应付总金额：</div>
                        <div class="d_o_info_right_content_three_amounts">￥{{$data['order_real_total']}}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d_o_info_line">
            <div class="d_o_info_operate" >
                @if($data['order_comf_status'] == ORDER_CONFIRMED && $data['order_pay_status'] == ORDER_PAYED && $data['order_shipping_status'] == ORDER_UNSHIPPED && $data['order_status'] == ORDER_STATUS_WAIT_PRODUCE)
                    <button class="btn od_receiving  btn-dialog btn-operat"   data-url="{{URL::asset('/order/production/'.$data['order_id'])}}" data-title = "提交生产">提交生产</button>
                @endif
                @if($data['order_status'] == ORDER_STATUS_WAIT_PRODUCE && $data['order_pay_status'] == ORDER_PAYED && $data['order_prod_status'] == ORDER_PRODUCED)
                    {{--待生产、已付款状态才可配货--}}
                    {{--<button class="btn od_aftersale  btn-dialog btn-operat"   data-url="{{URL::asset('/order/distribution/'.$data['order_id'])}}" data-title = "订单配货">订单配货</button>--}}
                @endif
                @if($data['order_status'] == ORDER_STATUS_WAIT_DELIVERY)
                    {{--已生产、待发货状态才可操作--}}
                    <button class="btn od_aftersale  btn-dialog btn-operat"   data-url="{{URL::asset('/order/delivery/'.$data['order_id'])}}" data-title = "订单发货">订单发货</button>
                @endif
                @if($data['order_status'] == ORDER_STATUS_FINISH || $data['order_status'] == ORDER_STATUS_WAIT_RECEIVE)
                    {{--订单发货后才可申请售后--}}
                    <button class="btn od_aftersale  btn-operat"><a href="{{URL::asset('/order/service')}}" style="color: #3F51B5;">订单售后</a></button>
                @endif
                @if($data['order_status'] == ORDER_STATUS_WAIT_CONFIRM || $data['order_status'] == ORDER_STATUS_WAIT_PAY || $data['order_status'] == ORDER_STATUS_WAIT_PRODUCE)
                        {{--订单提交生产前可取消交易--}}
                    <button class="btn od_aftersale btn-operat"><a class="btn-del" data-url="{{URL::asset('/order/cancel/'.$data['order_id'])}}" data-title="取消交易" data-text="是否确定取消交易">取消交易</a></button>
                @endif
            </div>
        </div>

        <div class="d_well d_well-sm">
            <span class="d_well-img-c"><i class="fa fa-list-ul"></i></span>
            <span class="d_well-font">订单操作日志</span>
        </div>
        <div class="log">
            @foreach($data['log_info'] as $k=>$v)
                <div class="row">
                    <div class="col-lg-1 col-md-1 log_content">
                        <div class="log_content_first"></div>
                    </div>
                    <div class="col-lg-11 col-md-11 log_contents">
                        <div class="log_contents_first">
                            <div class="log_contents_first_first">{{$v['created_at']}}</div>
                            <div class="log_contents_first_two">【{{$v['action']}}】</div>
                            <div>{{$v['note']}}</div>
                        </div>
                    </div>
                </div>

            @if(!$loop->last)
                <div class="row">
                    <div class="col-lg-1 col-md-1 log_line">
                        <div class="log_line_first"></div>
                    </div>
                </div>
            @endif
            @endforeach

        </div>

    </div>
@endsection

@section("js-file")
    <!-- AdminLTE App -->
    <script src="{{ URL::asset('assets/daterangepicker/moment.min.js')}}"></script>
    <script src="{{ URL::asset('assets/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{ URL::asset('js/backend/order/list.js')}}"></script>
@endsection

@section("pages-js")
    function showWork(obj) {
        var action = $(obj).find("img").attr("data-action");
        if (action == "hide")
        {
            $(obj).find("img").attr("src","/images/cms-chevron-double-down.png");
            $(obj).find("img").attr("data-action","show");
            $(obj).parent().parent().parent().parent().parent().next().slideUp(500);
        }else{
            $(obj).find("img").attr("src","/images/cms-chevron-double-up.png")
            $(obj).find("img").attr("data-action","hide");
            $(obj).parent().parent().parent().parent().parent().next().slideDown(500);
        }
    }
@endsection








