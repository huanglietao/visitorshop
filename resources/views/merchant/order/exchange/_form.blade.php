<!-- form表单视图 -->
@inject('CommonPresenter','App\Presenters\CommonPresenter')
<div class="Checkes" style="margin-top:20px;">

    <div class="d_well d_well-sm exc_form_title" style="margin-bottom: 10px;">
        <span class="d_well-img-c"><img src="/images/mch-clipboard.jpg" alt=""></span>
        <span class="d_well-font s_info_span">工单信息</span>
    </div>

    <div class="exc_form_title row exc_form_info_content">
        <div class="col-lg-2 col-md-2 exc_form_info_content_first">订单号：</div>
        <div class="col-lg-4 col-md-4 exc_form_info_content_two">{{$row['order_no']}}</div>
        <div class="col-lg-2 col-md-2 exc_form_info_content_first">当前状态：</div>
        <div class="col-lg-4 col-md-4 exc_form_info_content_two">{{$CommonPresenter->exchangeOrderStatus($row['orderInfo']['order_status'])}}</div>
    </div>
    <div class="exc_form_title row exc_form_info_content">
        <div class="col-lg-2 col-md-2 exc_form_info_content_first">订单金额：</div>
        <div class="col-lg-4 col-md-4 exc_form_info_content_two">￥{{$row['orderInfo']['order_real_total']}}</div>
        <div class="col-lg-2 col-md-2 exc_form_info_content_first">订单来源：</div>
        <div class="col-lg-4 col-md-4 exc_form_info_content_two">{{$row['orderInfo']['cha_name']}}</div>
    </div>
    <div class="exc_form_title row exc_form_info_content">
        <div class="col-lg-2 col-md-2 exc_form_info_content_first">处理方式：</div>
        <div class="col-lg-4 col-md-4 exc_form_info_content_two">{{$CommonPresenter->exchangeHandle($row['job_handle'])}}	</div>
        <div class="col-lg-2 col-md-2 exc_form_info_content_first">优惠金额：</div>
        <div class="col-lg-4 col-md-4 exc_form_info_content_two">@if(!empty($row['discount_money']))￥{{$row['discount_money']}}@endif</div>
    </div>
    <div class="exc_form_title row exc_form_info_content">
        <div class="col-lg-2 col-md-2 exc_form_info_content_first">换货单号：</div>
        <div class="col-lg-4 col-md-4 exc_form_info_content_two">{{$row['exchange_order_no']}}</div>
        <div class="col-lg-2 col-md-2 exc_form_info_content_first">同步单号：</div>
        <div class="col-lg-4 col-md-4 exc_form_info_content_two">{{$row['sync_order_no']}}</div>
    </div>
    <div class="exc_form_title row exc_form_info_content">
        <div class="col-lg-2 col-md-2 exc_form_info_content_first">责任方及原因：</div>
        <div class="col-lg-10 col-md-10 exc_form_info_content_two">{{$row['job_reason_text']}}</div>
    </div>
    <div class="exc_form_title row exc_form_info_content">
        <div class="col-lg-2 col-md-2 exc_form_info_content_first">处理说明：</div>
        <div class="col-lg-10 col-md-10 exc_form_info_content_two">{{$row['job_remarks']}}</div>
    </div>

    <div class="d_well d_well-sm exc_form_title" style="margin-bottom: 10px;margin-top: 20px;">
        <span class="d_well-img-c"><i class="fa fa-archive"></i></span>
        <span class="d_well-font s_info_span">商品信息</span>
    </div>

    <div class="exc_form_title row exc_form_info_content">
        @foreach($row['prod_info'] as $k=>$v)
            <div id="d_table" style="width: 100%;border-top: 1px solid #f5f5f5;">
            <table class="no-border-table">
                <thead>
                <tr class="table-head d_table-head" style="background: white;">
                    <td>商品信息</td>
                    @if($v['prj_type'] == WORKS_FILE_TYPE_DIY)
                        <td>作品信息</td>
                    @endif
                    <td>页数</td>
                    <td>单价</td>
                    <td>数量</td>
                    <td>小计</td>
                </tr>
                </thead>
                <tbody class="tbl-content">
                <tr>
                    <td class="d_o_attr_goods" style="width: 35%;">
                        <div class="d_o_works_info">
                            <div class="d_o_works_img">
                                <img src="{{$v['prod_main_thumb']}}">
                            </div>
                            <div class="d_o_works_detail">
                                <p class="d_o_works_name">{{$v['prod_name']}}</p>
                                <p class="d_o_works_pnum">商品货号：{{$v['prod_sku_sn']}}</p>
                            </div>
                        </div>
                    </td>
                    @if($v['prj_type'] == WORKS_FILE_TYPE_DIY)
                        <td class="d_o_attr_goods" style="width: 35%;">
                            <div class="d_o_works_info">
                                <div class="d_o_works_img">
                                    <img src="{{$v['prj_image']}}">
                                </div>
                                <div class="d_o_works_detail">
                                    <p class="d_o_works_name">{{$v['prj_name']}}</p>
                                    <p class="d_o_works_pnum" style="width: 100%;">作品号：{{$v['prj_sn']}}</p>
                                </div>
                            </div>
                        </td>
                    @endif
                    <td class="d_o_attr d_o_attr_price" style="vertical-align:middle;width: 6%;">{{$v['prod_pages']}}</td>
                    <td class="d_o_attr d_o_attr_num" style="vertical-align:middle;width: 9%;">￥{{$v['prod_sku_price']}}</td>
                    <td class="d_o_attr d_o_attr_weight" style="vertical-align:middle;width: 6%;">{{$v['prod_num']}}</td>
                    <td class="d_o_attr d_o_attr_count" style="vertical-align:middle;width: 9%;">￥{{$v['prod_sale_price']}}</td>
                </tr>
                </tbody>
            </table>
        </div>
        @endforeach
    </div>


    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
        </div>
    </div>

</div>

<style>
    .d_table-head td{
        font-weight: normal;
    }
</style>
