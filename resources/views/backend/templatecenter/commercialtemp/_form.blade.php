<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/templatecenter/commercialtemp/save" onsubmit="return false;" autocomplete="off">
        <input type=hidden value="{{$tid}}" name="id" id="id">
        <input type=hidden value="{{$cid}}" name="cid" id="cid">

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 所属规格：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <select  class="form-control col-sm-6" id="size_id" name="size_id" data-rule="">
                        <option value="">请选择</option>
                        @foreach($specList as $k=>$v)
                            <option value={{$k}} @if($k == $sizeid) selected @endif >{!! $v !!}</option>
                        @endforeach
                    </select>
                    <span class="col-sm-5 color-6A6969"> @if($sizeid!=-1)<span style="color: red" >已绑定对应规格</span>@else选择需要绑定的规格@endif</span>
                </div>
                <span class="msg-box" style="position:static;" for="size_id"></span>
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

