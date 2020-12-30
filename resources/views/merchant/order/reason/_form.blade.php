<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/order/reason/save" onsubmit="return false;" autocomplete="off">
        <input type=hidden value="{{$row['service_reason_id']}}" name="service_reason_id" id="id">

        @if($row['reason_pid'] != ZERO || !isset($row['service_reason_id']))
            <div class="form-group row form-item">
                <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">问题分类：</label>
                <div class="col-xs-12 col-sm-10">
                    <div class="row">
                        <select  class="form-control col-sm-7" name="reason_pid" class="form-control">
                            <option value="0">-请选择-</option>
                            @foreach($typeList as $k=>$v)
                            <option value={{$k}} @if($k == $row['reason_pid']) selected @endif >{{$v}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        @endif

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 售后原因：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="reason" class="form-control col-sm-7" name="reason" type="text" value="{{$row['reason']}}" data-rule="售后原因:required">
                </div>
                <span class="msg-box" style="position:static;" for="reason"></span>
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



