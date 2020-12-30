<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/test/media/save" onsubmit="return false;" autocomplete="off">
    <input type=hidden value="{{$row['prod_md_id']}}" name="id" id="id">
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 商品id：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="prod_id" class="form-control col-sm-5" name="prod_id" type="text" value="{{$row['prod_id']}}" placeholder="" data-rule="商品id:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="prod_id"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 存储路径：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="prod_md_path" class="form-control col-sm-5" name="prod_md_path" type="text" value="{{$row['prod_md_path']}}" placeholder="" data-rule="存储路径:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="prod_md_path"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 是否为主图：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="prod_md_ismain" class="form-control col-sm-5" name="prod_md_ismain" type="text" value="{{$row['prod_md_ismain']}}" placeholder="" data-rule="是否为主图:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="prod_md_ismain"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 类型：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="prod_md_type" class="form-control col-sm-5" name="prod_md_type" type="text" value="{{$row['prod_md_type']}}" placeholder="" data-rule="类型:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="prod_md_type"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 排序：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="sort" class="form-control col-sm-5" name="sort" type="text" value="{{$row['sort']}}" placeholder="" data-rule="排序:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="sort"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 创建时间：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="created_at" class="form-control col-sm-5" name="created_at" type="text" value="{{$row['created_at']}}" placeholder="" data-rule="创建时间:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="created_at"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 更新时间：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="updated_at" class="form-control col-sm-5" name="updated_at" type="text" value="{{$row['updated_at']}}" placeholder="" data-rule="更新时间:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="updated_at"></span>
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

