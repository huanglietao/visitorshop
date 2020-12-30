<!-- form表单视图 -->
<div class="Checkes" style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/order/service/save" autocomplete="off">
        @csrf
        <input type="hidden" value="{{$row['job_id']}}" name="job_id">
        @if(isset($row['service_order_no']))
            <div class="form-group row form-item">
                <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                    售后单号：</label>
                <div class="col-xs-12 col-sm-10" style="padding-top: 7px">
                    <span class="color-6A6969"> {{$row['service_order_no']}}</span>

                </div>
            </div>
        @endif

        <div class="form-group row form-item">
            <label style=" font-weight: normal;line-height: 28px;" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                @if(!isset($row['order_no']))<span class="control-span" style="color: red">*</span>@endif订单编号：
            </label>
            <div class="col-xs-10 col-sm-10" style="padding-top: 7px">
                <div class="row">
                    @if(isset($row['order_no']))
                        <span class="color-6A6969"> {{$row['order_no']}}</span>
                        <input type="hidden" value="{{$row['order_no']}}" name="order_no">
                    @else
                        <input id="order_no" class="form-control col-sm-7 form_order_no" value="{{$row['order_no']}}" name="order_no" type="text" data-rule="订单编号:required">
                    @endif
                </div>
                <span class="msg-box" style="position:static;" for="order_no"></span>
                {{--<span class=" color-6A6969 a_add_goods"> <a style="padding-left: 10px!important;text-decoration: underline!important;color:#007bff;cursor: pointer ">添加商品</a></span>--}}

            </div>
        </div>
        {{--<div class="form-group row form-item">--}}
            {{--<label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">--}}
                {{--<span class="control-span" style="color: red">*</span> 商品名称：</label>--}}
            {{--<div class="col-xs-12 col-sm-10">--}}
                {{--<div class="o_works_info"  style="margin-left: 0!important;">--}}
                    {{--<div class="o_works_img">--}}
                        {{--<img src="/images/1.jpg">--}}
                    {{--</div>--}}
                    {{--<div class="o_works_detail" style="margin-left: 10px;max-width: 300px;">--}}
                        {{--<p class="o_works_name">经典对裱纪念册12寸竖（16/22/30/40P）</p>--}}
                        {{--<p class="o_works_spec">颜色分类：时尚款24P，约装30~50张照片尺寸：12寸(210 * 285MM)页数：24P以上</p>--}}
                    {{--</div>--}}

                {{--</div>--}}

            {{--</div>--}}
        {{--</div>--}}

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">售后类型：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row radios">
                    <div style="display: inline-block;padding-left: 0;" class="col-sm-7">
                        <!-- 单选框组件start  -->
                    @component('component/radio',['radio'=>config('order.service_type'),'default_key'=>$row['job_type']??ORDER_AFTER_TYPE_REFUND,'name' => 'job_type','classname'=>[ORDER_AFTER_TYPE_REFUND=>'ao_service_type',ORDER_AFTER_TYPE_GOOD_REFUND=>'ao_service_type refund'],'data_value'=>[ORDER_AFTER_TYPE_REFUND=>'dd',ORDER_AFTER_TYPE_GOOD_REFUND=>'ii']])
                    @endcomponent
                    <!-- end  -->
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row form-item ao_refund_address" style="display: none">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
               退货地址：
            </label>
            <div class="col-xs-12 col-sm-10">
                <div class="row radios">
                    <div style="display: inline-block;color: #6A6969;padding-top: 7px" class="col-sm-7 color-6A6969">
                        <span>收货人【售后服务部】</span><br>
                        <span>联系电话【138-0013-8000】</span><br>
                        <span>寄件地址【广东省广州市天河区天盈创意园D1033】</span><br>
                    </div>
                    <span class="col-sm-5 color-6A6969" style="display: flex;align-items: center"> 退件时邮费必须不能到付，请联系客服处理运费事宜</span>
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
               物品状态：
            </label>
            <div class="col-xs-12 col-sm-10">
                <div class="row radios">
                    <div style="display: inline-block;padding-left: 0;" class="col-sm-7">
                        <!-- 单选框组件start  -->
                            @component('component/radio',['radio'=>config('order.service_good_status'),'name'=>'job_good_status','default_key'=>$row['job_good_status']??ORDER_AFTER_GOOD_STATUS_NOT_RECEIVER])
                            @endcomponent
                        <!-- 单选框组件end  -->
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span class="control-span" style="color: red">*</span>申请原因：
            </label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <select name="job_reason" data-rule="申请原因:required" id="job_reason" class="form-control col-sm-7">
                        <option value="" @if(empty($row['job_reason'])) selected @endif>请选择</option>
                        @foreach($reasonList as $k=>$v)
                            <optgroup label="{{$parentList[$k]}}">
                                @foreach($v as $key=>$val)
                                    <option value={{$val['service_reason_id']}} @if($val['service_reason_id'] == $row['job_reason']) selected @endif>{{$val['reason']}}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
                <span class="msg-box" style="position:static;" for="job_reason"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span class="control-span" style="color: red">*</span> 退款金额：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="refund_money" class="form-control col-sm-7"  name="refund_money" type="text" value="{{$row['refund_money']}}" data-rule="退款金额:required" >
                    @if(!isset($row['refund_money']))
                        <span class="col-sm-5 color-6A6969" style="margin-top: 3px"> 最多<span class="order_real_total">0.00</span>元，含物流费<span class="order_exp_fee">0.00</span>元</span>
                    @endif
                </div>
                <span class="msg-box" style="position:static;" for="refund_money"></span>
            </div>
        </div>


        <div class="form-group row form-item">
            <label style=" font-weight: normal;line-height: 120px;" for="c-mch_name" class="control-label col-xs-12 col-sm-2">售后说明：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <textarea class="form-control col-sm-7" style="height: 150px;resize: none;" name="job_note">{{$row['job_note']}}</textarea>
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal;line-height: 85px" for="c-mch_name" class="control-label col-xs-12 col-sm-2">上传凭证：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div style="display: inline-block" class="col-sm-7">
                        @component('component/image_upload',['name'=>'job_service_voucher','direction'=>0,'browse_btn'=>'test','content_class'=>'upload_list','img_format'=>'jpg,jpeg,png','num'=>5  ,'img_size'=>'8000kb','value'=>$row['job_service_voucher'],'uploader'=>'uploader'])
                        @endcomponent
                    </div>
                    <span class="col-sm-5" style="color: rgba(0, 0, 0, 0.45);white-space: nowrap;line-height: 90px">最多五张，支持格式：jpg/jpeg/png,大小200k以内</span>
                </div>
            </div>
        </div>
    </form>
    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            @if($row['job_status'] == ORDER_AFTER_STATUS_UNPROCESSED || empty($row['job_status']))
                <button type="submit" class="btn btn-primary btn-3F51B5 btn-sure btn-submit">确定</button>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <button type="reset" class="btn btn-write btn-reset">重置</button>
            @endif
        </div>
    </div>
</div>
