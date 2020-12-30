<!-- 物流方式视图 -->
<div class="Checkes" style="margin-top:30px">
    <form class="form-horizontal" id="form-save" method="post" action="/order/change_delivery/{{$data['order_id']}}" autocomplete="off">
        @csrf
        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: #6A6969;" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span class="control-span" style="color: red">*</span>物流方式：</label>
            <div class="col-lg-10 col-sm-10">
                <div class="row">
                    <select  class="form-control col-sm-7" id="order_delivery_id" name="order_delivery_id" class="form-control" data-rule="物流方式:required">
                        @foreach($deliveryList as $k=>$v)
                            <option value={{$v->delivery_id}} @if($v->delivery_id == $data['order_delivery_id']) selected @endif >{{$v->delivery_name}}</option>
                        @endforeach
                    </select>
                </div>
                <span class="msg-box" style="position:static;" for="oms_adm_group_id"></span>
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
