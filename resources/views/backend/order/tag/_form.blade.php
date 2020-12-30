<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/order/tag/save" onsubmit="return false;" autocomplete="off">
        <input type=hidden value="{{$row['tag_id']}}" name="tag_id" id="tag_id">

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 标签名称：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="tag_name" class="form-control col-sm-7" name="tag_name" type="text" value="{{$row['tag_name']}}" data-rule="标签名称:required">
                </div>
                <span class="msg-box" style="position:static;" for="tag_name"></span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">描述：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="tag_description" class="form-control col-sm-7" name="tag_description" type="text" value="{{$row['tag_description']}}">
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

