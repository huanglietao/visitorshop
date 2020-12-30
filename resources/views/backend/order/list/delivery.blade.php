<!-- 订单发货视图 -->
<div class="Checkes" style="margin-top:20px;">
    <form class="form-horizontal" id="form-save" method="post" action="/order/delivery/{{$productData[0]['ord_id']}}" autocomplete="off">
        @csrf
        <div class="d_well d_well-sm exc_form_title" style="margin-bottom: 10px;margin-top: 20px;">
            <span class="d_well-img-c"><i class="fa fa-archive"></i></span>
            <span class="d_well-font s_info_span">商品信息</span>
        </div>
        @foreach($productData as $k=>$v)
            <div class="exc_form_title row exc_form_info_content">
                <div id="d_table" style="width: 100%;border-top: 1px solid #f5f5f5;">
                    <table class="no-border-table">
                        <thead>
                        <tr class="table-head d_table-head" style="background: white;">
                            <td>商品信息</td>
                            <td>作品信息</td>
                            <td>页数</td>
                            <td>单价</td>
                            <td>数量</td>
                        </tr>
                        </thead>
                        <tbody class="tbl-content">
                        <tr>
                            <td class="d_o_attr_goods" style="width: 38%;">
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
                            <td class="d_o_attr_goods" style="width: 38%;">
                                @if($v['prj_type'] == WORKS_FILE_TYPE_DIY)
                                    <div class="d_o_works_info">
                                        <div class="d_o_works_img">
                                            <img src="{{$v['prj_image']}}">
                                        </div>
                                        <div class="d_o_works_detail">
                                            <p class="d_o_works_name">{{$v['prj_name']}}</p>
                                            <p class="d_o_works_pnum" style="width: 100%;">作品号：{{$v['prj_sn']}}</p>
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td class="d_o_attr d_o_attr_price" style="vertical-align:middle;width: 7%;">{{$v['prod_pages']}}</td>
                            <td class="d_o_attr d_o_attr_num" style="vertical-align:middle;width: 10%;">￥{{$v['prod_sale_price']}}</td>
                            <td class="d_o_attr d_o_attr_weight" style="vertical-align:middle;width: 7%;">{{$v['prod_num']}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach

        <div class="d_well d_well-sm exc_form_title" style="margin-bottom: 10px;margin-top: 20px;">
            <span class="d_well-img-c"><img src="/images/mch-address-card.jpg" alt=""></span>
            <span class="d_well-font s_info_span">收件人信息</span>
        </div>

        <div class="exc_form_title row exc_form_info_content">
            <table class="table table-bordered" id="tab">
                <tbody>
                    <tr>
                        <td style="text-align: center; vertical-align: middle;">物流公司</td>
                        <td style="text-align: center; vertical-align: middle;">
                            <div class="col-xs-10 col-sm-10">
                                <select  class="form-control" id="order_delivery_id" name="order_delivery_id" class="form-control" data-rule="物流方式:required">
                                    @foreach($deliveryList as $k=>$v)
                                        <option value={{$v->express_id}} @if($v->express_id == $orderInfo['order_delivery_id']) selected @endif >{{$v->express_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </td>
                        <td style="text-align: center; vertical-align: middle;">配送单号</td>
                        <td>
                            <div class="row">
                                <div class="col-xs-10 col-sm-10">
                                    <input id="delivery_code" class="form-control" maxlength="30" name="delivery_code" type="text" data-rule="配送单号:required">
                                </div>
                            </div>
                            <span class="msg-box" style="position:static;" for="delivery_code"></span>
                        </td>
                    </tr>
                <tr>
                    <td style="text-align: center; vertical-align: middle;">配送费用</td>
                    <td style="text-align: center; vertical-align: middle;">
                        <div class="col-xs-10 col-sm-10">
                            <input type="hidden" value="{{$orderInfo['order_exp_fee']}}" name="order_exp_fee">
                            <input class="form-control" disabled="disabled" type="text" value="￥{{$orderInfo['order_exp_fee']}}">
                        </div>
                    </td>
                    <td style="text-align: center; vertical-align: middle;">发货时间</td>
                    <td style="text-align: center; vertical-align: middle;">
                        <div class="col-xs-10 col-sm-10">
                            <input type="hidden" value="{{ time() }}" name="order_shipping_time">
                            {{ date('Y-m-d H:i:s',time()) }}
                        </div>
                    </td>

                </tr>
                <tr>
                    <td style="text-align: center; vertical-align: middle;">收件人</td>
                    <td>
                        <div class="row">
                            <div class="col-xs-10 col-sm-10">
                                <input id="order_rcv_user" data-rule="收件人:required" class="form-control" name="order_rcv_user" type="text" value="{{$orderInfo['order_rcv_user']}}">
                            </div>
                        </div>
                        <span class="msg-box" style="position:static;" for="order_rcv_user"></span>
                    </td>
                    <td style="text-align: center; vertical-align: middle;">手机号码</td>
                    <td>
                        <div class="row">
                            <div class="col-xs-10 col-sm-10">
                                <input data-rule="手机号码:required" id="order_rcv_phone" class="form-control" maxlength="11" name="order_rcv_phone" type="text" value="{{$orderInfo['order_rcv_phone']}}">
                            </div>
                        </div>
                        <span class="msg-box" style="position:static;" for="order_rcv_phone"></span>
                    </td>
                </tr>

                <tr>
                    <td style="text-align: center; vertical-align: middle;">地区</td>
                    <td style="" colspan="4">
                        <div class="col-xs-12 col-sm-8">
                            @component('component.areas',['province_value'=>$orderInfo['order_rcv_province'],'city_value'=>$orderInfo['order_rcv_city'],'areas_value'=>$orderInfo['order_rcv_area']])@endcomponent
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center; vertical-align: middle;">详细地址</td>
                    <td colspan="4">
                        <div class="col-xs-12 col-sm-8">
                            <textarea data-rule="详细地址:required" id="order_rcv_address" class="form-control" style="resize:none" rows="3" name="order_rcv_address" cols="50">{{$orderInfo['order_rcv_address']}}</textarea>
                            <span class="msg-box" style="position:static;" for="order_rcv_address"></span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center; vertical-align: middle;">发货备注</td>
                    <td style="text-align: center; vertical-align: middle;" colspan="4">
                        <div class="col-xs-12 col-sm-8">
                            <textarea id="c-order_remark_admin" class="form-control " rows="3" name="order_remark_admin" style="resize:none" cols="50">{{$orderInfo['order_remark_admin']}}</textarea>
                        </div>
                    </td>
                </tr>
                </tbody></table>
        </div>

    </form>
    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button type="submit" class="btn btn-primary btn-3F51B5 btn-sure btn-submit">发货</button>
            &nbsp;&nbsp;&nbsp;&nbsp;
        </div>
    </div>

</div>

<style>
    .d_table-head td{
        font-weight: normal;
    }
</style>
