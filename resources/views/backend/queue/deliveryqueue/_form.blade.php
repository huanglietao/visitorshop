<!-- form表单视图 -->

<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/queue/deliveryqueue/save" onsubmit="return false;" autocomplete="off">

        <input type=hidden value="{{$row['delivery_push_id']}}" name="id" id="id">

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 队列状态：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <select  class="form-control col-sm-7" id="delivery_push_status" name="delivery_push_status" data-rule="队列状态:required">
                        @foreach($queueStatusList as $k=>$v)
                            <option value={{$k}} @if($k == $row['delivery_push_status']) selected @endif >{!! $v !!}</option>
                        @endforeach
                    </select>
                    <span class="col-sm-5 color-6A6969"></span>
                </div>
                <span class="msg-box" style="position:static;" for="delivery_push_status"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">快递简称：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="delivery_name" class="form-control col-sm-7" name="delivery_name" type="text" value="{{$row['delivery_name']}}">
                    <span class="col-sm-5 color-6A6969"> </span>
                </div>
                <span class="msg-box" style="position:static;" for="delivery_name"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">运单号：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="delivery_code" class="form-control col-sm-7" name="delivery_code" type="text" value="{{$row['delivery_code']}}">
                    <span class="col-sm-5 color-6A6969"> </span>
                </div>
                <span class="msg-box" style="position:static;" for="delivery_code"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">错误信息：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="error_msg" class="form-control col-sm-7" name="error_msg" type="text" value="{{$row['error_msg']}}">
                    <span class="col-sm-5 color-6A6969"> </span>
                </div>
                <span class="msg-box" style="position:static;" for="produce_queue_err_msg"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">分销商编号：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="agent_code" class="form-control col-sm-7" name="agent_code" type="text" value="{{$row['agent_code']}}">
                    <span class="col-sm-5 color-6A6969"> </span>
                </div>
                <span class="msg-box" style="position:static;" for="agent_code"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">处理次数：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="times" class="form-control col-sm-7" name="times" type="text" value="{{$row['times']}}">
                    <span class="col-sm-5 color-6A6969"> </span>
                </div>
                <span class="msg-box" style="position:static;" for="times"></span>
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

