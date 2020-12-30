<!-- form表单视图 -->
<div class="Checkes" style="margin-top:30px">
    <form class="form-horizontal" id="form-save" method="post" action="/order/service/save" autocomplete="off">
        @csrf
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span class="control-span" style="color: red">*</span>订单编号：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 7px">
                <div class="row">
                    <input name="order_no" id="order_no" class="form-control col-lg-7 col-sm-7 form_order_no" data-rule="订单编号:required">
                </div>
                <span class="msg-box" style="position:static;" for="order_no"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">售后类型：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row radios">
                    <div style="display: inline-block;padding-left: 0;" class="col-sm-7">
                        <!-- 单选框组件start  -->
                    @component('component/radio',['radio'=>[ORDER_AFTER_TYPE_REFUND=>'仅退款',ORDER_AFTER_TYPE_GOOD_REFUND=>'退货退款'],'default_key'=>ORDER_AFTER_TYPE_REFUND,'name' => 'job_type','classname'=>[ORDER_AFTER_TYPE_REFUND=>'ao_service_type',ORDER_AFTER_TYPE_GOOD_REFUND=>'ao_service_type refund'],'data_value'=>[ORDER_AFTER_TYPE_REFUND=>'dd',ORDER_AFTER_TYPE_GOOD_REFUND=>'ii']])
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
                    <span class="col-sm-7 color-6A6969" style="display: flex;align-items: center"> 退件时邮费必须不能到付，请联系客服处理运费事宜</span>
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
                    @component('component/radio',['radio'=>config('order.service_good_status'),'name'=>'job_good_status','default_key'=>ORDER_AFTER_GOOD_STATUS_NOT_RECEIVER])
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
                    <input  id="refund_money" class="form-control col-sm-7"  name="refund_money" type="text" value="" data-rule="退款金额:required" >
                    <span class="col-sm-5 color-6A6969" style="margin-top: 3px"> 最多<span class="order_real_total">0.00</span>元，含物流费<span class="order_exp_fee">0.00</span>元</span>
                </div>
                <span class="msg-box" style="position:static;" for="refund_money"></span>
            </div>
        </div>


        <div class="form-group row form-item">
            <label style=" font-weight: normal;line-height: 120px;" for="c-mch_name" class="control-label col-xs-12 col-sm-2">售后说明：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <textarea class="form-control col-sm-7" style="height: 150px;resize: none;" name="job_note"></textarea>
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal;line-height: 85px" for="c-mch_name" class="control-label col-xs-12 col-sm-2">上传凭证：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div style="display: inline-block" class="col-sm-7">
                        @component('component/image_upload',['name'=>'job_service_voucher','direction'=>0,'browse_btn'=>'test','content_class'=>'upload_list','img_format'=>'jpg,jpeg,png','num'=>5 ,'value'=>'','uploader'=>'uploader'])
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
            <button type="submit" class="btn btn-primary btn-3F51B5 btn-sure btn-submit">确定</button>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <button type="reset" class="btn btn-write btn-reset">重置</button>
        </div>
    </div>
</div>