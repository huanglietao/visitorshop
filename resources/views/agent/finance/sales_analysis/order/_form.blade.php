<!-- form表单视图 -->
@inject('CommonPresenter','App\Presenters\CommonPresenter')
<div style="margin-top:30px;margin-bottom: 30px">
    <form class="form-horizontal" id="form-save" method="post" action="/demo/save" autocomplete="off">
        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                订单号：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 5px">
                <span>{{$row['order_no']}}</span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                外部订单号：</label>
            <div class="col-xs-12 col-sm-10 account-recharge-five" style="padding-top: 5px">
                <span>{{$row['order_relation_no']}}</span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                下单日期：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 5px">
                <span>{{$CommonPresenter->exchangeTime($row['created_at'])}}</span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                收货人：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 5px">
                <span>{{$row['buyer_nickname']}}</span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                收货地址：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 5px">
                <span>{{$row['order_rcv_address']}}</span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                手机：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 5px">
                <span>{{$row['order_rcv_phone']}}</span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                订单金额：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 5px">
                <span>￥ {{$row['order_real_total']}}</span>
            </div>
        </div>


        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                支付方式：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 5px">
                <span>{{$row['pay_name']}}</span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                支付状态：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 5px">
                <span>{{$CommonPresenter->exchangePayStatus($row['order_pay_status'])}}</span>
            </div>
        </div>


        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                快递方式：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 5px">
                <span>{{$row['delivery_name']}}</span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                快递单号：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 5px">
                <span>{{$row['delivery_code']}}</span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                发货日期：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 5px">
                <span>{{$CommonPresenter->exchangeTime($row['order_shipping_time'])}}</span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: rgb(121, 119, 119)" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                商品信息：</label>
            <div class="col-xs-12 col-sm-8" style="padding-top: 5px">
                <span>{{$row['prod_info']}}</span>
            </div>
        </div>

    </form>
    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button id="del-cancel" type="button" class="btn btn-primary btn-3F51B5 btn-sure btn-confirm-close" style="position: static">确定</button>
        </div>
    </div>
</div>

