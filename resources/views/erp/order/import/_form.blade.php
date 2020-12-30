<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/import/save" onsubmit="return false;" autocomplete="off">
        <input type=hidden value="{{$row['id']}}" name="id" id="id">
        <input type=hidden value="{{$row['is_collect']}}" name="is_collect" id="is_collect">
        <input type=hidden value="{{$row['status']}}" name="status" id="status">
        <input type=hidden value="{{$row['partner_order_date']}}" name="partner_order_date" id="partner_order_date">
        <input type=hidden value="{{$row['is_hurry']}}" name="is_hurry" id="is_hurry">
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">客户单号：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="partner_number" class="form-control col-sm-5" name="partner_number" type="text" value="{{$row['partner_number']}}" placeholder="" >
                    <span class="col-sm-7 color-6A6969"> 请务必按照正确的格式填写。</span>
                </div>
                <span class="msg-box" style="position:static;" for="partner_number"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">客户简称：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="partner_real_name" class="form-control col-sm-5" name="partner_real_name" type="text" value="{{$row['partner_real_name']}}" placeholder="" >
                    <span class="col-sm-7 color-6A6969"> 请务必按照正确的格式填写。</span>
                </div>
                <span class="msg-box" style="position:static;" for="partner_real_name"></span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">产品名称：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="product_name" class="form-control col-sm-5" name="product_name" type="text" value="{{$row['product_name']}}" placeholder="" >
                    <span class="col-sm-7 color-6A6969"> 请务必按照正确的格式填写。</span>
                </div>
                <span class="msg-box" style="position:static;" for="product_name"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">订购数量：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="single_num" class="form-control col-sm-5" name="single_num" type="text" value="{{$row['single_num']}}" placeholder="">
                    <span class="col-sm-7 color-6A6969"> 请务必按照正确的格式填写。</span>
                </div>
                <span class="msg-box" style="position:static;" for="single_num"></span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">指定物流方式：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input name="assign_express_type"  id="assign_express" hidden type="text" class="form-control" value="{{$row['assign_express_type']}}">
                    <select style="padding:4px 8px;color:rgb(106, 105, 105);font-size:12px;" class="form-control col-sm-5" id="assignSelect" onchange="assign()">
                        <option value="">请选择</option>
                        <option value="yto" @if($row['assign_express_type']=='yto') selected @endif>圆通快递</option>
                        <option value="sto" @if($row['assign_express_type']=='sto') selected @endif>申通快递</option>
                        <option value="zto" @if($row['assign_express_type']=='zto') selected @endif>中通快递</option>
                        <option value="yunda" @if($row['assign_express_type']=='yunda') selected @endif>韵达快递</option>
                        <option value="best" @if($row['assign_express_type']=='best') selected @endif>百世快递</option>
                        <option value="sfj" @if($row['assign_express_type']=='sfj') selected @endif>顺丰寄</option>
                        <option value="sfd" @if($row['assign_express_type']=='sfd') selected @endif>顺丰到</option>
                        <option value="ems" @if($row['assign_express_type']=='ems') selected @endif>中国邮政快递包裹</option>
                        <option value="since" @if($row['assign_express_type']=='since') selected @endif>自提</option>
                        <option value="other" @if($row['assign_express_type']=='other') selected @endif>其他快递</option>
                    </select>
                    <script>
                        function assign() {
                            var obj = document.getElementById("assignSelect"); //定位id
                            var index = obj.selectedIndex; // 选中索引
                            var value = obj.options[index].value; // 选中值
                            document.getElementById('assign_express').value = value;

                        }
                    </script>
                    <span class="col-sm-7 color-6A6969"> 请务必按照正确的格式填写。</span>
                </div>
                <span class="msg-box" style="position:static;" for="assign_express_type"></span>
            </div>
        </div>


        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">收件人：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="recipient_person" class="form-control col-sm-5" name="recipient_person" type="text" value="{{$row['recipient_person']}}" placeholder="">
                    <span class="col-sm-7 color-6A6969"> 请务必按照正确的格式填写。</span>
                </div>
                <span class="msg-box" style="position:static;" for="recipient_person"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">收件人手机：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="recipient_phone" class="form-control col-sm-5" name="recipient_phone" type="text" value="{{$row['recipient_phone']}}" placeholder="">
                    <span class="col-sm-7 color-6A6969"> 请务必按照正确的格式填写。</span>
                </div>
                <span class="msg-box" style="position:static;" for="recipient_phone"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">收件人地址：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="recipient_address" class="form-control col-sm-5" name="recipient_address" type="text" value="{{$row['recipient_address']}}" placeholder="">
                    <span class="col-sm-7 color-6A6969"> 请务必按照正确的格式填写。</span>
                </div>
                <span class="msg-box" style="position:static;" for="recipient_address"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">发件人：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="sender_person" class="form-control col-sm-5" name="sender_person" type="text" value="{{$row['sender_person']}}" placeholder="">
                    <span class="col-sm-7 color-6A6969"> 请务必按照正确的格式填写。</span>
                </div>
                <span class="msg-box" style="position:static;" for="sender_person"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">发件人手机：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="sender_phone" class="form-control col-sm-5" name="sender_phone" type="text" value="{{$row['sender_phone']}}" placeholder="">
                    <span class="col-sm-7 color-6A6969"> 请务必按照正确的格式填写。</span>
                </div>
                <span class="msg-box" style="position:static;" for="sender_phone"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">发件人地址：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="sender_address" class="form-control col-sm-5" name="sender_address" type="text" value="{{$row['sender_address']}}" placeholder="">
                    <span class="col-sm-7 color-6A6969"> 请务必按照正确的格式填写。</span>
                </div>
                <span class="msg-box" style="position:static;" for="sender_address"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">备注：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <textarea id="note" class="form-control col-sm-5" name="note" placeholder="" style="height: 100px;resize: none;">{{$row['note']}}</textarea>
                    <span class="col-sm-7 color-6A6969"> 请不要超过200个字符。</span>
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
