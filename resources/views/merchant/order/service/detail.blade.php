<!DOCTYPE html>
@extends('layout.mch_iframe')

@section("main-content")
    <link rel="stylesheet" href="{{URL::asset('css/merchant/order/list.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('assets/daterangepicker/daterangepicker.css') }}">


    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '控制台 / 订单管理 / 订单售后 / 售后详情' ])
    @endcomponent
    <!-- 面包屑组件end  -->

    @inject('CommonPresenter','App\Presenters\CommonPresenter');
    <div id="main" style="padding:15px">
        <!--  提示组件 start -->
        @component('component/tips')
            <p style="margin:5px 0 ;padding:0">订单详情记录订单的基本信息、收货人信息、商品信息、费用信息等。订单分5个大流程分别是提交、支付、发货、收货及评价。</p>
            <p style="margin:5px 0;padding:0">特殊类目订单由于行业特性可能有其它支流程，且该类订单商品拥有特殊子项目信息，可点击展开键查看特殊信息。</p>
            <p style="margin:5px 0;padding:0">分销类商家提交成功的订单是无法修改的（系统会自动确认执行后续处理流程），只能联系商家进行修改。订单售后问题需要确认收货后才能申请售后。</p>
        @endcomponent
        <!--  提示组件 end -->
        @csrf
        <div class="d_well d_well-sm">
            <span class="d_well-img-c"><img src="/images/mch-clipboard.jpg" alt=""></span>
            <span class="d_well-font s_info_span">售后单信息</span>
        </div>
        <div class="d_o_info_line" style="flex-direction: column;">
            <div class="d_o_info_item row s_info_content">
                <div class="col-lg-1 col-md-1 s_info_content_left">售后单号：</div>
                <div class="col-lg-11 col-md-2" style="color: #0c0c0c;">{{$data['service_order_no']}}</div>
            </div>
            <div class="d_o_info_item row s_info_content">
                <div class="col-lg-1 col-md-1 s_info_content_left">售后类型：</div>
                <div class="col-lg-11 col-md-2" style="color: #0c0c0c;">{{$CommonPresenter->exchangeService($data['job_type'])}}</div>
            </div>
            <div class="d_o_info_item row s_info_content">
                <div class="col-lg-1 col-md-1 s_info_content_left">状态：</div>
                <div class="col-lg-11 col-md-2" style="color: #0c0c0c;">{{$CommonPresenter->exchangeHandel($data['job_status'])}}</div>
            </div>
            <div class="d_o_info_item row s_info_content">
                <div class="col-lg-1 col-md-1 s_info_content_left">申请时间：</div>
                <div class="col-lg-11 col-md-2" style="color: #0c0c0c;">{{$data['created_at']}}</div>
            </div>
            <div class="d_o_info_item row s_info_content">
                <div class="col-lg-1 col-md-1 s_info_content_left">申请人：</div>
                <div class="col-lg-11 col-md-2" style="color: #0c0c0c;">{{$data['operator']}}</div>
            </div>
            <div class="d_o_info_item row s_info_content">
                <div class="col-lg-1 col-md-1 s_info_content_left">申请原因：</div>
                <div class="col-lg-11 col-md-2" style="color: #0c0c0c;">{{$data['job_reason_text']}}</div>
            </div>
            <div class="d_o_info_item row s_info_content">
                <input type="hidden" value="{{$data['job_id']}}" class="job_id">
                <input type="hidden" value="{{$data['refund_money']}}" class="refund_amount">
                <div class="col-lg-1 col-md-1 s_info_content_left">申请金额：</div>
                <div class="col-lg-11 col-md-2" style="color: #0c0c0c;">￥{{$data['refund_money']}}</div>
            </div>
            <div class="d_o_info_item row s_info_content">
                <div class="col-lg-1 col-md-1 s_info_content_left">售后凭证：</div>
                <div class="col-lg-11 col-md-2" >
                    @foreach($data['job_service_voucher'] as $key=>$val)
                        @if(!empty($val))
                            <img src="{{$val}}" width="80px" height="80px" style="margin-right: 10px;">
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="d_o_info_item row s_info_content">
                <div class="col-lg-1 col-md-1 s_info_content_left">售后说明：</div>
                <div class="col-lg-11 col-md-2" style="color: #0c0c0c;">{{$data['job_note']}}</div>
            </div>
            <div class="d_o_info_item row s_info_content">
                <div class="col-lg-1 col-md-1 s_info_content_left">订单编号：</div>
                <div class="col-lg-11 col-md-2" style="color: #0c0c0c;">{{$data['order_no']}}</div>
            </div>
            <div class="d_o_info_item row s_info_content">
                <div class="col-lg-1 col-md-1 s_info_content_left">订单状态：</div>
                <div class="col-lg-11 col-md-2" style="color: #0c0c0c;">{{$CommonPresenter->exchangeOrderStatus($data['orderInfo']['order_status'])}}</div>
            </div>
            <div class="d_o_info_item row s_info_content">
                <div class="col-lg-1 col-md-1 s_info_content_left">订单金额：</div>
                <div class="col-lg-11 col-md-2" style="color: #0c0c0c;">￥{{$data['orderInfo']['order_real_total']}}</div>
            </div>
            <div class="d_o_info_item row s_info_content">
                <div class="col-lg-1 col-md-1 s_info_content_left">运费：</div>
                <div class="col-lg-11 col-md-2" style="color: #0c0c0c;">￥{{$data['orderInfo']['order_exp_fee']}} 【{{$data['express_name']}}】</div>
            </div>
            <div class="d_o_info_item row s_info_content">
                <div class="col-lg-1 col-md-1 s_info_content_left">售后商品：</div>
                <div class="col-lg-11 col-md-2" style="color: #0c0c0c;">
                    <div id="d_table">
                        <table class="no-border-table">
                            <thead>
                            <tr class="table-head d_table-head">
                                <td>商品</td>
                                <td>属性</td>
                                <td>作品P数</td>
                                <td>数量</td>
                                <td>单价</td>
                                <td>金额</td>
                                <td>备注</td>
                            </tr>
                            </thead>
                            <tbody class="tbl-content">
                            @foreach($data['prod_info'] as $kk=>$vv)
                                <tr>
                                    <td class="d_o_attr_goods">
                                        <div class="d_o_works_info">
                                            <div class="d_o_works_img">
                                                <img src="{{$vv['prod_main_thumb']}}">
                                            </div>
                                            <div class="d_o_works_detail">
                                                <p class="d_o_works_name">{{$vv['prod_name']}}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="d_o_attr d_d_o_works_spec_info"> <p class="d_o_works_spec">{{$vv['prod_att_str']}}</p></td>
                                    <td class="d_o_attr d_o_attr_price" style="width: 8%;">{{$vv['prj_page_num']}}P</td>
                                    <td class="d_o_attr d_o_attr_num">{{$vv['prod_num']}}</td>
                                    <td class="d_o_attr d_o_attr_sale" style="width: 5%;">{{$vv['prod_sku_price']}}</td>

                                    <td class="d_o_attr d_o_attr_weight">
                                        {{$vv['prod_sale_price']}}
                                    </td>
                                    <td class="d_o_attr d_o_attr_other" style="width: 27%;">

                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="2">
                                <td>共计：</td>
                                <td>{{$data['nums']}}件</td>
                                <td></td>
                                <td>{{$data['orderInfo']['order_real_total']}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="d_well d_well-sm" style="margin-bottom: 20px" >
            <span class="d_well-img-c"><i class="fa fa-stop-circle-o"></i></span>
            <span class="d_well-font">售后处理</span>
        </div>
        <div class="d_o_info_line" style="flex-direction: column;">
            <div class="d_o_info_item row s_info_content">
                <div class="col-lg-1 col-md-1 s_info_content_left">责任认定：</div>
                <div class="col-lg-11 col-md-2" style="color: #0c0c0c;">{{$data['job_responsibility_text']}}</div>
            </div>
        </div>
        <div class="d_o_info_line" style="flex-direction: column;">
            <div class="d_o_info_item row s_info_content">
                <div class="col-lg-1 col-md-1 s_info_content_left">处理方式：</div>
                <div class="col-lg-11 col-md-2" style="color: #0c0c0c;">
                    {{$CommonPresenter->exchangeHandle($data['job_handle'])}}
                </div>
            </div>
        </div>
        <div class="d_o_info_line" style="flex-direction: column;">
            <div class="d_o_info_item row s_info_content">
                <div class="col-lg-1 col-md-1 s_info_content_left">处理人：</div>
                <div class="col-lg-11 col-md-2" style="color: #0c0c0c;">{{$data['handler']}}</div>
            </div>
        </div>
        <div class="d_o_info_line" style="flex-direction: column;">
            <div class="d_o_info_item row s_info_content">
                <div class="col-lg-1 col-md-1 s_info_content_left">处理时间：</div>
                <div class="col-lg-11 col-md-2" style="color: #0c0c0c;">{{$CommonPresenter->exchangeTime($data['handle_time'])}}</div>
            </div>
        </div>
        <div class="d_o_info_line" style="flex-direction: column;">
            <div class="d_o_info_item row s_info_content">
                <div class="col-lg-1 col-md-1 s_info_content_left">备注：</div>
                <div class="col-lg-11 col-md-2" style="color: #0c0c0c;">{{$data['job_remarks']}}</div>
            </div>
        </div>
        <div class="d_o_info_item row s_info_content">
            <div class="col-lg-1 col-md-1 s_info_content_left">处理凭证：</div>
            <div class="col-lg-11 col-md-2" >
                @foreach($data['job_handel_voucher'] as $key=>$val)
                    @if(!empty($val))
                        <img src="{{$val}}" width="80px" height="80px" style="margin-right: 10px;">
                    @endif
                @endforeach
            </div>
        </div>
        <input type="hidden" id="job_handle" value="{{$data['job_handle']}}">
        <input type="hidden" name="job_handel_voucher" value="@if(!empty($data['job_handel_voucher'][0])){{implode(',',$data['job_handel_voucher'])}} @endif">
        <input type="hidden" class="discount_inp" value="{{$data['discount_money']}}">
        <input type="hidden" class="amount_inp" value="{{$data['refund_money']}}">
        <input type="hidden" class="return_inp" value="{{$data['refund_order_no']}}">
        <input type="hidden" class="job_remarks" value="{{$data['job_remarks']}}">
        <input type="hidden" id="job_responsibility" value="{{$data['job_responsibility']}}">


        <div class="d_o_info_line">
            <div class="d_o_info_operate" >
                @if($data['job_status'] == ORDER_AFTER_STATUS_UNPROCESSED || $data['job_status'] == ORDER_AFTER_STATUS_PROCESSED)
                    <button class="btn od_receiving btn-operat btn-review"  data-title = "审核归档">审核归档</button>
                @endif
                <button class="btn od_return_service btn-operat" style="height: 32px;">返回</button>
            </div>
        </div>

    </div>


@endsection
@section("js-file")
    <!-- AdminLTE App -->
    <script src="{{ URL::asset('assets/daterangepicker/moment.min.js')}}"></script>
    <script src="{{ URL::asset('assets/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{ URL::asset('js/merchant/order/list.js')}}"></script>
    {{--<script type="text/javascript" src="/assets/plupload/plupload.full.min.js"></script>--}}
@endsection
<style>
    #d_table td{
        border-right: 1px solid #ffffff !important;
    }
    .d_table-head td{
        font-weight: normal !important;
    }
</style>
