<!-- form表单视图 -->

<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/queue/compoundqueue/save" onsubmit="return false;" autocomplete="off">

        <input type=hidden value="{{$row['comp_queue_id']}}" name="id" id="id">
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 所属服务器：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <select  class="form-control col-sm-7" id="comp_queue_serv_id" name="comp_queue_serv_id" data-rule="所属服务器:required">
                        @foreach($sevice as $k=>$v)
                            <option value={{$k}} @if($k == $row['comp_queue_serv_id']) selected @endif >{!! $v !!}</option>
                        @endforeach
                    </select>
                    <span class="col-sm-5 color-6A6969"></span>
                </div>
                <span class="msg-box" style="position:static;" for="comp_queue_serv_id"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 合成状态：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <select  class="form-control col-sm-7" id="comp_queue_status" name="comp_queue_status" data-rule="合成状态:required">
                        @foreach($queueStatusList as $k=>$v)
                            <option value={{$k}} @if($k == $row['comp_queue_status']) selected @endif >{!! $v !!}</option>
                        @endforeach
                    </select>
                    <span class="col-sm-5 color-6A6969"></span>
                </div>
                <span class="msg-box" style="position:static;" for="comp_queue_status"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 错误信息：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="art_author" class="form-control col-sm-7" name="error_msg" type="text" value="{{$row['error_msg']}}">
                    <span class="col-sm-5 color-6A6969"> </span>
                </div>
                <span class="msg-box" style="position:static;" for="error_msg"></span>
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

