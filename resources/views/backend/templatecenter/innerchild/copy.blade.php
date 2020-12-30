<div style="margin-top:50px" >
    <form class="form-horizontal common-form" id="form-save" method="post" action="/templatecenter/innerchild/docopy" onsubmit="return false;" autocomplete="off">

        <input id="c-id"  class="form-control" name="pageid" type="hidden" value="{{$pageid}}">
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="inner_page_name" class="control-label col-xs-12 col-sm-3">
                <span style="color:red">*</span> 克隆数量：</label>
            <div class="col-xs-12 col-sm-9">
                <div class="row">
                    <input  id="clone_num" class="form-control col-sm-7" name="clone_num" type="text" value="1" data-rule="克隆数量:required;integer(+)">
                    <span class="col-sm-5 color-6A6969"> 填写正整数</span>
                </div>
                <span class="msg-box" style="position:static;" for="clone_num"></span>
            </div>
        </div>
    </form>
    <div class="form-group layer-footer" style="">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button type="submit" class="btn btn-primary btn-3F51B5 btn-sure btn-submit">确定</button>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <button type="reset" class="btn btn-write btn-reset">重置</button>
        </div>
    </div>
</div>