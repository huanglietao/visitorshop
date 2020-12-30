{{--作品订购页面--}}
<style>
    .settlement{
        list-style-type:none;
    }
    .butdiv{
        padding-top: 15px;
    }
    .settlement-z{
        font-size: small;
    }
    #goods_price, #freight,#total_price{color: red;}
    .areas{width: 120px;}
    td  {
        vertical-align: middle;
        text-align: center;
    }
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }

    .thc{
        text-align: center; vertical-align: middle;
    }
    .label1{
        padding-top: 35px;
        float: none;
        display: block;
        margin-left: auto;
        margin-right: auto;

    }
    textarea{outline:none;resize:none;}
</style>
<link rel="stylesheet" href="{{URL::asset('css/agent/works/works.css')}}">
<div style="padding: 20px;">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/works/orderSave" onsubmit="return false;" autocomplete="off">
        <input type="hidden" id="ids" class="ids" name="ids" value="{{$ids}}">
        <input type="hidden" id="is_open_pay" class="is_open_pay"  value="{{$is_open_pay}}">
        {{--<input type="hidden"  class="deliTempids" value="{{$projectInfo[0]['prod_express_tpl_id']}}">--}}
        <div class="well well-sm">作品信息</div>
        <table class="table table-bordered table-hover" id="tab">
            <thead>
            <tr>
                <th class="thc">作品编号</th>
                <th class="thc">基础信息</th>
                <th class="thc">属性</th>
                <th class="thc">货号</th>
                <th class="thc">重量</th>
                <th class="thc">单价</th>
                <th class="thc">数量</th>
                <th class="thc">小计</th>
            </tr>
            </thead>
            <tbody>
            @foreach($projectInfo as $key=>$value)
                <input type="hidden"  class="temp_id" value="{{$value['prod']['prod_express_tpl_id']}}">
                <tr class="goods_row">
                    <td>
                        <div class="label1">
                            {{$value['prj_sn']}}
                            {{--<input type="hidden" name="row[works_id][]" value="{$vo.tempid}">--}}
                        </div>
                    </td>
                    <td>
                        @if($value['prj_image'])
                        <div style="display: inline-block">
                            <img style="width:80px" src="{{$value['prj_image']}}"><br>
                            <label>{{$value['prj_name']}}</label>
                        </div>
                        @else
                        <div class="label1">
                            {{$value['prj_name']}}
                        </div>
                        @endif

                    </td>
                    <td>
                        <div class="label1">
                            @foreach($value['sku_attr'] as $k=>$v)
                            {{$v}}<br>
                            @endforeach
                        </div>
                    </td>
                    <td><div class="label1">{{$value['prod_sku']['prod_sku_sn']}}</div></td>
                    <td>
                        <div class="label1">{{$value['sku_weight']}}克</div>
                        <input hidden id="sku_weight{{$value['prj_id']}}" value="{{$value['sku_weight']}}"/>
                    </td>
                    <td>
                        <div class="label1">￥{{$value['sku_price']}}</div>
                        <input type="hidden" class="sku_price" id="sku_price" name="sku_price[]" value="{{$value['sku_price']}}">
                    </td>
                    <td>
                        <div class="label1">
                            <input class="min" name="" style="width: 15px" type="button" value="-" />
                            <input class="changenum text_box" @if(!empty($value['prj_temp'])) value="{{$value['prj_temp']['ord_quantity']}}" @else value="1" @endif id="numchange{{$value['prj_id']}}"  min="1"  max="100" name="number[]" type="number" style="text-align: center;width: 50px;"  autocomplete="off" />
                            <input class="add" name="" style="width: 15px" type="button" value="+" />
                        </div>
                    </td>
                    <td>
                        <div class="label1">
                            ￥{{$value['total_price']}}
                        </div>
                        <input type="hidden" id="cot_prices" name="cot_prices[]" class="cot_prices" value="{{$value['total_price']}}" >
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="well well-sm">收货人信息</div>
        <div class="row">
            <div class="col-md-12" >
                <div class="form-group row" style="padding: 0 0 10px 0">
                    <label style="width: 87px;text-align: left;" for="telephone" class="control-label col-xs-12 col-sm-1">关联单号:</label>
                    <div class="col-xs-12 col-sm-9">
                        <input type="text" class="form-control" id="order_id" name="order_no" autocomplete="off" @if(!empty($projectInfo[0]['prj_temp'])) value="{{$projectInfo[0]['prj_temp']['order_no']}}" @endif />
                    </div>
                </div>
                <div class="form-group row" style="padding: 10px 0">
                    <label style="width: 87px;text-align: left;" for="receiver_name" class="control-label col-xs-12 col-sm-1"><span style="color:red">*</span>收货人:</label>
                    <div class="col-xs-12 col-sm-4">
                        <input type="text" class="form-control" id="receiver_name" name="order_rcv_user" autocomplete="off" @if(!empty($projectInfo[0]['prj_temp'])) value="{{$projectInfo[0]['prj_temp']['prj_rcv_user']}}" @endif/>
                    </div>

                    <label style="width: 87px;text-align: left;" for="zip_code" class="control-label col-xs-12 col-sm-1">邮编:</label>
                    <div class="col-xs-12 col-sm-4">
                        <input type="text" class="form-control" id="zip_code" name="zip_code" autocomplete="off" value="" />
                    </div>
                </div>
                <div class="form-group row" style="padding: 10px 0">
                    <label style="width: 87px;text-align: left;" for="phone" class="control-label col-xs-12 col-sm-1"><span style="color:red">*</span>手机号:</label>
                    <div class="col-xs-12 col-sm-4">
                        <input type="text" class="form-control" id="receiver_phone" name="order_rcv_phone" autocomplete="off" @if(!empty($projectInfo[0]['prj_temp'])) value="{{$projectInfo[0]['prj_temp']['prj_rcv_phone']}}" @endif/>
                    </div>

                    <label style="width: 87px;text-align: left;" for="telephone" class="control-label col-xs-12 col-sm-1">电话号码:</label>
                    <div class="col-xs-12 col-sm-4">
                        <input type="text" class="form-control" id="telephone" name="telephone" autocomplete="off" value=""  />
                    </div>
                </div>
                <div class="form-group row" style="padding: 10px 0">
                    <label style="width: 87px;text-align: left;" class="control-label col-xs-12 col-sm-1"><span style="color:red">*</span>地区选择:</label>
                    <div class="col-xs-12 col-sm-9">
                        @if(!empty($projectInfo[0]['prj_temp']))
                            @component('component.areas',['province_value'=>$projectInfo[0]['prj_temp']['prj_province'],'city_value'=>$projectInfo[0]['prj_temp']['prj_city'],'areas_value'=>$projectInfo[0]['prj_temp']['prj_district']])
                            @endcomponent
                        @else
                            @component('component.areas')
                            @endcomponent
                        @endif
                    </div>
                </div>
                <div class="form-group row" style="padding: 10px 0">
                    <label style="width: 87px;text-align: left;" for="receiver_address" class="control-label col-xs-12 col-sm-1"><span style="color:red">*</span>收货地址:</label>
                    <div class="col-xs-12 col-sm-9">
                        <input type="text" class="form-control" id="receiver_address" name="prj_rcv_address" autocomplete="off" @if(!empty($projectInfo[0]['prj_temp'])) value="{{$projectInfo[0]['prj_temp']['prj_rcv_address']}}" @endif/>
                    </div>
                </div>
                <div class="form-group row" style="padding: 10px 0 20px 0">
                    <label style="width: 87px;text-align: left;" for="ext_info" class="control-label col-xs-12 col-sm-1">备注:</label>
                    <div class="col-xs-12 col-sm-9">
                        <textarea id="buyer_memo" class="form-control" name="buyer_memo" rows="3"></textarea>
                    </div>
                </div>

            </div>

            <div class="col-md-4" ></div>
        </div>

        <div class="well well-sm">配送信息</div>

        <table class="table table-bordered table-hover table-delivery" style="display: none;white-space: pre;">
            {{--<thead>--}}
            {{--<tr>--}}
                {{--<th class="thc">快递名称</th>--}}
                {{--<th class="thc">快递方式</th>--}}
                {{--<th class="thc">描述</th>--}}
                {{--<th class="thc">价格</th>--}}
                {{--<th class="thc">选择</th>--}}
            {{--</tr>--}}
            {{--</thead>--}}
            {{--<tbody>--}}
            {{--</tbody>--}}
        </table>
        <div class="well well-sm">支付方式</div>
        <div class="radios">
            @foreach($payment as $pay => $ment)
                @if(str_contains($ment['pay_class_name'],"balance")==true)
                    <div style="display: block">
                        @component('component/radio',['radio'=>[$pay=>$ment['pay_name']],'name'=>'order_pay_id','data_value'=>[$pay=>$ment['pay_class_name']],'default_key'=>$pay])
                        @endcomponent
                        <div style="display: inline-block;vertical-align: top;color: red;padding-right: 5px">
                            ￥{{$now_balance}}
                            <a href="/#finance/recharge/index" target="_blank">立即充值</a>
                        </div>
                    </div>

                @else
                    <div style="display: block">
                        @component('component/radio',['radio'=>[$pay=>$ment['pay_name']],'name'=>'order_pay_id','data_value'=>[$pay=>$ment['pay_class_name']]])
                        @endcomponent
                    </div>
                @endif
            @endforeach
        </div>

        <div class="col-xs-12 col-sm-12" style="margin:0">
            <hr style="margin: 5px 0"/>
        </div>

        <div class="row">
            <div class="col-md-9"></div>
            <div class="col-md-3">
                <ul class="list-group">
                    <li class="settlement row">
                        <label class="settlement-z col-md-6">商品总额: </label>
                        <label class="settlement-z col-md-6">
                            <span id="goods_price">￥{{$totalprice}}元</span>
                        </label>
                        <input type="hidden" id="goodsprice" name="goodsprice" value="{{$totalprice}}"/>
                    </li>
                    <li class="settlement row">
                        {{--<input type="hidden" name="row[custom]" id="custom" value="succ">--}}
                        <label class="settlement-z col-md-6">运费:  &nbsp;&nbsp;&nbsp;+</label>
                        <label class="settlement-z col-md-6"><span id="freight">￥0.00元</span></label>
                        <input type="hidden" name="order_exp_fee" id="firstprice" value="0.00">
                    </li>
                    <li class="settlement row">
                        <label class="settlement-z col-md-6">优惠: &nbsp;&nbsp;&nbsp; -</label>
                        <label class="settlement-z col-md-6" style="color: red;"><span id="discount">￥0.00元</span></label>
                    </li>
                    <li class="settlement row">
                        <label class="settlement-z col-md-6">应付总额:  </label>
                        <label class=" settlement-z col-md-6"><span id="total_price">￥{{$totalprice}}元</span></label>
                        <input type="hidden" name="order_real_total" id="totalprice" value="{{$totalprice}}">
                    </li>
                </ul>

            </div>
        </div>
    </form>

    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button type="submit" class="btn btn-primary btn-3F51B5 btn-sure btn-click">确定</button>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <button type="reset" class="btn btn-write btn-reset">重置</button>
        </div>
    </div>
</div>
<style>
    .imgclick {
        border: 2px solid rgb(59, 78, 255);
        border-radius: 9px;
    }
    .imgalipay {
        /*margin-top: 8%;*/
        /* box-shadow: rgb(170, 170, 170) 0px 0px 10px 0px;*/
        border-radius: 5px;
        margin-left: 45px;

    }
    .imgdivdiv{
        width: 100%;
        height: 10.5em;
    }
    .areas-one{
        width: 100%;
        display: flex;
        justify-content: space-between;
    }
    .areas-province,.areas-city,.areas-area{
        height: 30px !important;
        width: 32%;
    }

    @media screen and (max-width:1200px){
        .imgdivdiv{
            width: 100%;
            height: 9.1em;
        }
    }
    @media screen and (max-width:1000px){
        .imgdivdiv{
            width: 100%;
            height: 7.1em;
        }
    }
    @media screen and (max-width:500px){
        .imgdivdiv{
            width: 100%;
            height: 4.5em;
        }
    }
</style>