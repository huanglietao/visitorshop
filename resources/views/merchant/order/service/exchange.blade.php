<!-- 换货单视图 -->
<div class="Checkes" style="margin-top:30px">
    <form class="form-horizontal" id="form-save" method="post" action="/order/service/exchange/{{$data['job_id']}}" autocomplete="off" onsubmit="return false;">
        @csrf
        <input type="hidden" value="service" class="service_exchange">
        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: #6A6969;" for="c-mch_name" class="control-label col-xs-12 col-sm-2">订单编号：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 7px">
                <div class="row">
                    <span class="col-lg-5 col-sm-5">{{$data['order_no']}}</span>
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: #6A6969;" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span class="control-span" style="color: red">*</span>收货人：</label>
            <div class="col-lg-10 col-sm-10">
                <div class="row">
                    <div class="col-xs-4 col-sm-4">
                        <div class="row">
                            <input class="form-control col-lg-12 col-sm-12" data-rule="收货人:required" id="reciver" name="order_rcv_user" value="{{$data['orderInfo']['order_rcv_user']}}">
                        </div>
                        <span class="msg-box" style="position:static;" for="reciver"></span>
                    </div>
                    <div class="col-xs-2 col-sm-2">
                        <label style=" font-weight: normal;color: #6A6969;" for="c-mch_name" class="control-label col-xs-12 col-sm-12">邮政编码：</label>
                    </div>
                    <div class="col-xs-4 col-sm-4">
                        <div class="row">
                            <input class="form-control col-lg-12 col-sm-12" name="order_rcv_zipcode" value="{{$data['orderInfo']['order_rcv_zipcode']}}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: #6A6969;" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span class="control-span" style="color: red">*</span>收货人手机：</label>
            <div class="col-lg-10 col-sm-10">
                <div class="row">
                    <div class="col-xs-4 col-sm-4">
                        <div class="row">
                            <input class="form-control col-lg-12 col-sm-12" data-rule="收货人手机:required" id="phone" value="{{$data['orderInfo']['order_rcv_phone']}}" name="order_rcv_phone">
                        </div>
                        <span class="msg-box" style="position:static;" for="phone"></span>
                    </div>
                    <div class="col-xs-2 col-sm-2">
                        <label style=" font-weight: normal;color: #6A6969;" for="c-mch_name" class="control-label col-xs-12 col-sm-12">座机电话：</label>
                    </div>
                    <div class="col-xs-4 col-sm-4">
                        <div class="row">
                            <input class="form-control col-lg-12 col-sm-12">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: #6A6969;" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span class="control-span" style="color: red">*</span>收货人地区：</label>
            <div class="col-lg-10 col-sm-10">
                <div class="row">
                    <div class="col-xs-8 col-sm-8">
                        @component('component.areas',['province_value'=>$data['orderInfo']['order_rcv_province'],'city_value'=>$data['orderInfo']['order_rcv_city'],'areas_value'=>$data['orderInfo']['order_rcv_area']])@endcomponent
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: #6A6969;" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span class="control-span" style="color: red">*</span>详细地址：</label>
            <div class="col-lg-10 col-sm-10">
                <div class="row">
                    <div class="col-xs-10 col-sm-10">
                        <input class="form-control col-lg-12 col-sm-12" data-rule="详细地址:required" id="address" name="order_rcv_address" value="{{$data['orderInfo']['order_rcv_address']}}">
                    </div>
                </div>
                <span class="msg-box" style="position:static;" for="address"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: #6A6969;line-height: 100px;" for="c-mch_name" class="control-label col-xs-2 col-sm-2">换货商品：</label>
            <div class="col-lg-10 col-sm-10">
                <div class="row">
                    @foreach($data['prod_info'] as $k=>$v)
                        <div id="d_table" class="col-xs-12 col-sm-12">
                            <table class="no-border-table">
                                <thead>
                                <tr class="table-head d_table-head">
                                    <td>
                                        <div>@component('component/checkbox',['checkbox'=>[$v['ord_prod_id']=>''],'name'=>['exchange_item[]'],'right_distance'=>0])@endcomponent商品</div>
                                    </td>
                                    <td>属性</td>
                                    @if($v['prj_type'] == WORKS_FILE_TYPE_DIY)
                                        <td>作品信息</td>
                                    @endif
                                    <td>数量</td>
                                    <td>结算价</td>
                                    <td>金额</td>
                                </tr>
                                </thead>
                                <tbody class="tbl-content">
                                <tr>
                                    <td class="d_o_attr_goods">
                                        <div class="d_o_works_info">
                                            <div class="d_o_works_img">
                                                <img src="{{$v['prod_main_thumb']}}" style="width: 48px;height: 48px;">
                                            </div>
                                            <div class="d_o_works_detail">
                                                <p class="d_o_works_name">{{$v['prod_name']}}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="d_o_attr d_d_o_works_spec_info" style="vertical-align: middle;width: 25%;">
                                        <p class="d_o_works_spec" style="width: 100%;">{{$v['prod_att_str']}}</p>
                                    </td>
                                    @if($v['prj_type'] == WORKS_FILE_TYPE_DIY)
                                        <td class="d_o_attr d_o_attr_other" style="width: 35%;">
                                            <div class="d_o_works_info">
                                                <div class="d_o_works_img">
                                                    <img src="{{$v['prj_image']}}" style="width: 48px;height: 48px;">
                                                </div>
                                                <div class="d_o_works_detail" style="display: flex;flex-direction: column;">
                                                    <span class="d_o_works_name">{{$v['prj_name']}}</span>
                                                    <span class="d_o_works_name">作品号：{{$v['prj_sn']}}</span>
                                                    <span class="d_o_works_name">{{$v['prj_page_num']}}P</span>
                                                </div>
                                            </div>
                                        </td>
                                    @endif
                                    <td class="d_o_attr d_o_attr_num">
                                        <input type="text" class="exchange_num" value="{{$v['prod_num']}}" style="width: 40px;" onkeyup="value=value.replace(/^0(0+)|[^\d]+/g,'')">
                                        <input type="hidden" name="item_num[]" value="{{json_encode([$v['ord_prod_id']=>$v['prod_num']])}}" style="width: 40px;">
                                    </td>
                                    <td class="d_o_attr d_o_attr_sale" style="width: 8%;">{{$v['prod_sku_price']}}</td>
                                    <td class="d_o_attr d_o_attr_weight">{{$v['prod_sale_price']}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: #6A6969;" for="c-mch_name" class="control-label col-xs-12 col-sm-2">运费：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 7px">
                <div class="row">
                    <span class="col-lg-5 col-sm-5">￥{{$data['orderInfo']['order_exp_fee']}}【{{$data['express_name']}}】</span>
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: #6A6969;" for="c-mch_name" class="control-label col-xs-12 col-sm-2">订单总金额：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 7px">
                <div class="row">
                    <span class="col-lg-5 col-sm-5">￥{{$data['orderInfo']['order_real_total']}}</span>
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: #6A6969;" for="c-mch_name" class="control-label col-xs-12 col-sm-2">订单成本：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 7px">
                <div class="row">
                    <span class="col-lg-5 col-sm-5">￥{{$data['prod_cost']}}</span>
                </div>
            </div>
        </div>
        <div class="form-group row form-item" style="margin-bottom: 40px;">
            <label style=" font-weight: normal;color: #6A6969;" for="c-mch_name" class="control-label col-xs-12 col-sm-2">备注：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div class="col-lg-8 col-sm-8">
                        <textarea class="form-control col-lg-12 col-sm-12" style="height: 100px;resize: none;" name="bart_explain"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button type="submit" class="btn btn-primary btn-3F51B5 btn-sure btn-submit">确定</button>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <button type="reset" class="btn btn-write btn-reset">重置</button>
        </div>
    </div>
</div>

