<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/custom_print/print/info-save" onsubmit="return false;" autocomplete="off">

        <input type=hidden value="{{$row['cus_pri_id']}}" name="id" id="id">
        <input id="pri_type" name="pri_type" type="hidden" value="{{$type}}">


        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="pri-rece-username" class="control-label col-xs-12 col-sm-2">
               @if($type == 'consignee')收件人@else寄件人@endif：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="pri-rece-username" class="form-control col-sm-10" name="@if($type == 'consignee')pri_rece_username @else pri_send_username @endif" type="text" value="@if($type == 'consignee'){{$row['pri_rece_username']}}@else{{$row['pri_send_username']}}@endif " placeholder="">
                </div>
                <span class="msg-box" style="position:static;" for="pri-rece-username"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="pri-rece-mobile" class="control-label col-xs-12 col-sm-2">
                @if($type == 'consignee')收件人@else寄件人@endif电话：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="pri-rece-mobile" class="form-control col-sm-10" name="@if($type == 'consignee')pri_rece_mobile @else pri_send_mobile @endif" type="text" value="@if($type == 'consignee'){{$row['pri_rece_mobile']}}@else{{$row['pri_send_mobile']}}@endif" placeholder="">
                </div>
                <span class="msg-box" style="position:static;" for="pri-rece-mobile"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="pri-rece-tel" class="control-label col-xs-12 col-sm-2">
                @if($type == 'consignee')收件人@else寄件人@endif手机：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="pri-rece-tel" class="form-control col-sm-10" name="@if($type == 'consignee')pri_rece_tel @else pri_send_tel @endif" type="text" value="@if($type == 'consignee'){{$row['pri_rece_tel']}}@else{{$row['pri_send_tel']}}@endif" placeholder="">
                </div>
                <span class="msg-box" style="position:static;" for="pri-rece-tel"></span>
            </div>
        </div>

        @if($type == 'consignee')
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                收货地址：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div style="padding-left: 0;padding-right: 0" class="col-sm-10">
                        @component('component.areas',['province_value'=>$row['rece_prov_code'],'city_value'=>$row['rece_city_code'],'areas_value'=>$row['rece_area_code']])@endcomponent
                    </div>
                </div>
                <span class="msg-box" style="position:static;" for="rece_address">{{$row['pri_rece_province']}}&nbsp;{{$row['pri_rece_city']}}&nbsp;{{$row['pri_rece_area']}} @if(isset($row['address_msg']))({{$row['address_msg']}})@endif</span>
            </div>
        </div>
            <div class="form-group row form-item">
                <label style=" font-weight: normal" for="pri_rece_address" class="control-label col-xs-12 col-sm-2">
                    详细地址：</label>
                <div class="col-xs-12 col-sm-10">
                    <div class="row">
                        <input  id="pri-rece-tel" class="form-control col-sm-10" name="pri_rece_address" type="text" value="{{$row['pri_rece_address']}}" placeholder="">
                    </div>
                    <span class="msg-box" style="position:static;" for="pri_rece_address"></span>
                </div>
            </div>
        @endif

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

