<!-- 收货人信息视图 -->
<div class="Checkes" style="margin-top:30px">
    <form class="form-horizontal" id="form-save" method="post" action="/order/reciver/{{$data['order_id']}}" autocomplete="off">
        @csrf
        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: #6A6969;" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span class="control-span" style="color: red">*</span>收货人：</label>
            <div class="col-lg-8 col-sm-8">
                <div class="row">
                    <input class="form-control col-lg-12 col-sm-12" data-rule="收货人:required" id="reciver" name="order_rcv_user" value="{{$data['order_rcv_user']}}">
                </div>
                <span class="msg-box" style="position:static;" for="reciver"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: #6A6969;" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span class="control-span" style="color: red">*</span>收货人手机：</label>
            <div class="col-lg-8 col-sm-8">
                <div class="row">
                    <input class="form-control col-lg-12 col-sm-12" data-rule="收货人手机:required" id="phone" name="order_rcv_phone" value="{{$data['order_rcv_phone']}}">
                </div>
                <span class="msg-box" style="position:static;" for="phone"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: #6A6969;" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span class="control-span" style="color: red">*</span>收货人地区：</label>
            <div class="col-lg-10 col-sm-10">
                <div class="col-xs-12 col-sm-128">
                    @component('component.areas',['province_value'=>$data['order_rcv_province'],'city_value'=>$data['order_rcv_city'],'areas_value'=>$data['order_rcv_area']])@endcomponent
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: #6A6969;" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span class="control-span" style="color: red">*</span>收货地址：</label>
            <div class="col-lg-8 col-sm-8">
                <div class="row">
                    <textarea class="form-control col-lg-12 col-sm-12" style="height: 100px;resize: none;" data-rule="收货地址:required" id="address" name="order_rcv_address">{{$data['order_rcv_address']}}</textarea>
                </div>
                <span class="msg-box" style="position:static;" for="address"></span>
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
