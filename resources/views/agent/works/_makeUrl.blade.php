<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal" id="form-save" method="post" action="/demo/save" autocomplete="off">
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">电脑链接：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="pc_url" readonly  class="form-control col-sm-7" name="dms_adm_username" type="text" value="{{$row['pc_url']}}" placeholder="" data-rule="用户名:required">
                    <button id="btn-url" onclick="copyTxt('pc_url')" style="margin-left: 20px" type="button" class="btn btn-primary btn-3F51B5 btn-sure">复制</button>
                </div>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">手机链接：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="mob_url" readonly  class="form-control col-sm-7" name="dms_adm_username" type="text" value="{{$row['mob_url']}}" placeholder="" data-rule="用户名:required">
                    <button id="btn-url" onclick="copyTxt('mob_url')" style="margin-left: 20px" type="button" class="btn btn-primary btn-3F51B5 btn-sure">复制</button>
                </div>
            </div>
        </div>

    </form>
    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button id="del-cancel" type="button" class="btn btn-primary btn-3F51B5 btn-sure">确定</button>
        </div>
    </div>
</div>
<script>
    function copyTxt(con) {
        const range = document.createRange();
        range.selectNode(document.getElementById(con));
        const selection = window.getSelection();
        if(selection.rangeCount > 0) selection.removeAllRanges();
        selection.addRange(range);
        document.execCommand('copy');
        layer.msg("复制成功！");
    }
</script>