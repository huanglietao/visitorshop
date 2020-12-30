<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/marketing/coupon/save" onsubmit="return false;" autocomplete="off">
    <input type=hidden value="{{$row['cou_id']}}" name="cou_id" id="cou_id">
    <input type=hidden value="{{$row['mch_id']}}" name="mch_id" id="mch_id">
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 名称：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="cou_name" class="form-control col-sm-5" name="cou_name" type="text" value="{{$row['cou_name']}}" placeholder="" data-rule="名称:required">
                    <span class="col-sm-7 color-6A6969"> 优惠券的名称。</span>
                </div>
                <span class="msg-box" style="position:static;" for="cou_name"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">描述：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="cou_desc" class="form-control col-sm-5" name="cou_desc" type="text" value="{{$row['cou_desc']}}" placeholder="">
                    <span class="col-sm-7 color-6A6969"> 优惠券的说明。</span>
                </div>
                <span class="msg-box" style="position:static;" for="cou_desc"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 面额：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="cou_denomination" class="form-control col-sm-5" name="cou_denomination" type="text" value="{{$row['cou_denomination']}}" placeholder="" data-rule="面额:required">
                    <span class="col-sm-7 color-6A6969"> 优惠卷的金额。</span>
                </div>
                <span class="msg-box" style="position:static;" for="cou_denomination"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 类型：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div style="display: inline-block;padding-top: 5px;padding-left: 0" class="col-sm-5">
                        @component('component/radio',['radio'=>['1'=>'卡券','2'=>'优惠码'],'name'=>'cou_type','default_key'=>$row['cou_type']??1])
                        @endcomponent
                    </div>
                    <span class="col-sm-7 color-6A6969" style="padding-top: 1%"> 优惠券的类型。</span>
                </div>
                <span class="msg-box" style="position:static;" for="cou_type"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 所属子系统：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div style="display: inline-block;padding-top: 5px;padding-left: 0" class="col-sm-5">
                        @component('component/radio',['radio'=>$sales_chanel,'name'=>'sales_chanel_id','default_key'=>$row['sales_chanel_id']??1])
                        @endcomponent
                    </div>
                    {{--<input  id="sales_chanel_id" class="form-control col-sm-5" name="sales_chanel_id" type="text" value="{{$row['sales_chanel_id']}}" placeholder="" data-rule="所属子系统id:required">--}}
                    <span class="col-sm-7 color-6A6969"> 在选中的子系统中应用。</span>
                </div>
                <span class="msg-box" style="position:static;" for="sales_chanel_id"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 派送方式：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div style="display: inline-block;padding-top: 5px;padding-left: 0" class="col-sm-5">
                        @component('component/radio',['radio'=>['1'=>'用户领取','2'=>'后台发放','3'=>'注册发放','4'=>'积分兑换'],'name'=>'cou_distribution_method','default_key'=>$row['cou_distribution_method']??1])
                        @endcomponent
                    </div>
                    <span class="col-sm-7 color-6A6969" style="padding-top: 1%"> 优惠券的派送领取方式。</span>
                </div>
                <span class="msg-box" style="position:static;" for="cou_distribution_method"></span>
            </div>
        </div>
        <div id="input_score" class="form-group row form-item" style="display: none;">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 兑换时所需积分：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="cou_score" class="form-control col-sm-5" name="cou_score" type="text" value="{{$row['cou_score']}}" placeholder="">
                    <span class="col-sm-7 color-6A6969" style="padding-top: 1%"> 兑换优惠券时需要达到的积分。</span>
                </div>
                <span class="msg-box" style="position:static;" for="cou_score"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 使用范围：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div style="display: inline-block;padding-top: 5px;padding-left: 0" class="col-sm-5">
                        @component('component/radio',['radio'=>['1'=>'全部商品','2'=>'指定商品','3'=>'指定分类'],'name'=>'cou_use_limits','default_key'=>$row['cou_use_limits']??1])
                        @endcomponent
                    </div>
                    <span class="col-sm-7 color-6A6969" style="padding-top: 1%"> 优惠券的使用范围。</span>
                </div>
                <span class="msg-box" style="position:static;" for="cou_use_limits"></span>
            </div>
        </div>
        <div id="goods" class="form-group row" style="display: none;">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2"></label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div class="col-sm-5 add_contents">
                        <p style="color: #909090;font-size: 12px;margin-top: 2%;">购买以下商品可使用优惠券抵扣金额</p>
                        <input type="hidden" class="goods_category_id" name="goods_category_id" value="{{$row['goods_category_id']}}">
                        <input type="hidden" class="goods_id" name="goods_id" value="{{$row['goods_id']}}">
                        <input type="hidden" class="exist_ids" @if($row['cou_use_limits']==2) value="{{$row['goods_id']}}" @elseif($row['cou_use_limits']==3) value="{{$row['goods_category_id']}}" @endif>
                        <div style="width: 100%;color: #707070;font-size: 12px;background-color: #f7f7f7;padding-left: 2%;padding-top: 2%" class="checkbox_items">
                            @if($row['cou_use_limits']!=1)
                                @foreach($goods_or_category_list as $key =>$value)
                                    <div class="checkbox_item">
                                        <input type="checkbox" class="ui-checkbox" checked="checked">
                                        <label style="font-weight: normal;" data-type="3" class="ui-label" data-id="{{$key}}" onclick="is_checked(this)">
                                            {{$value}}
                                        </label>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="input_goods" class="form-group row form-item" style="display: none;">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                添加商品
            </label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div class="col-sm-3">
                        <select class="form-control goods">
                            @foreach($goods_list as $k=>$v)
                                <option value="{{$k}}">{{$v}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-2" style="padding-left: 0;">
                        <input onclick="add_category(this)" class="btn-dialog btn btn-primary btn-sm btn-3F51B5" type="button" value="添加"/>
                    </div>
                    {{--<div class="col-sm-5 add_contents">--}}
                        {{--<input id="c-images" type="hidden">--}}
                        {{--<span class="btn-dialog btn btn-primary btn-sm btn-3F51B5" data-area="['65%', '70%']" data-url="" data-title = "商品选择"  style="" ><i class="fa fa-plus" style="padding-right:5px"></i>商品选择</span>--}}
                        {{--<span class="msg-box n-right" for="c-images"></span>--}}
                        {{--<ul class="row list-inline plupload-preview" id="p-images"></ul>--}}
                    {{--</div>--}}
                </div>
            </div>
        </div>
        <div id="input_category" class="form-group row form-item" style="display: none;">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                添加分类
            </label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div class="col-sm-3">
                        <select class="form-control category">
                            @foreach($category_list as $k=>$v)
                                <option value="{{$k}}">{!! $v !!}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-2" style="padding-left: 0;">
                        <input onclick="add_category(this)" class="btn-dialog btn btn-primary btn-sm btn-3F51B5" type="button" value="添加"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 使用规则：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div style="display: inline-block;padding-top: 5px;padding-left: 0" class="col-sm-5">
                        @component('component/radio',['radio'=>['1'=>'无门槛','2'=>'满减'],'name'=>'cou_use_rule','default_key'=>$row['cou_use_rule']??1])
                        @endcomponent
                    </div>
                    <span class="col-sm-7 color-6A6969" style="padding-top: 1%"> 优惠券使用的规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="cou_use_rule"></span>
            </div>
        </div>
        <div id="input_min_consumption" class="form-group row form-item" style="display: none;">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 最低消费金额：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="cou_min_consumption" class="form-control col-sm-5" name="cou_min_consumption" type="text" value="{{$row['cou_min_consumption']}}" placeholder="">
                    <span class="col-sm-7 color-6A6969" style="padding-top: 1%"> 订单使用优惠券时需要达到的最低消费金额。</span>
                </div>
                <span class="msg-box" style="position:static;" for="cou_min_consumption"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 发放数量：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="cou_nums" @if(!$row['cou_nums']) data-rule="发放数量:required" @endif onblur="check_value(this,{{$row['cou_nums']}})" class="form-control col-sm-5" name="cou_nums" type="number" value="{{$row['cou_nums']}}" placeholder="">
                    <span class="col-sm-7 color-6A6969"> 优惠券的发放数量。</span>
                </div>
                <span class="msg-box" style="position:static;" for="cou_nums"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 优惠券有效时间：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div class="col-sm-5" style="padding-left: 0;padding-right: 0">
                        <input name="cou_time" @if($row['cou_start_time'])value="{{date("Y-m-d H:i:s",$row['cou_start_time'])."-".date("Y-m-d H:i:s",$row['cou_end_time'])}}" @endif type="text" style="font-size: 12px;width: 100%" id="reservationtime" class="form-control float-right date-picker datetimerange">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <span class="col-sm-7 color-6A6969"> 优惠券的生效日期和失效日期。</span>
                </div>
                <span class="msg-box" style="position:static;" for="cou_time"></span>
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

<style>
    .c_d_radio{
        margin-right: 5px;
    }
    .search-data-num {
        padding-top: 5px!important;
        margin-left: 13px;
        font-weight: normal;
        color: #6A6969;
        cursor: pointer;
    }
    .fa-calendar{
        color: #a9a9a9;
        position: absolute;
        vertical-align: middle;
        top: 31%;
        right: 0;
        padding-right: 13px;
    }
    .daterangepicker{
        z-index: 999999999;
    }

    .checkbox_item {
        display: block;
        float: none;
        margin-bottom: 2%;
    }
    .ui-checkbox {
        display: none !important;
    }
    .ui-label {
        padding-left: 20px;
        background: url('/images/input_checkbox.png') 2px 2px no-repeat;
    }
    .ui-checkbox:checked+label {
        background: url('/images/input_checked.png') 2px 2px no-repeat;
    }
</style>