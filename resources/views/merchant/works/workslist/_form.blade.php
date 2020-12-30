<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/works/workslist/save" onsubmit="return false;" autocomplete="off">
    <input type=hidden value="{{$row['prj_id']}}" name="id" id="id">
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 商户id：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="mch_id" class="form-control col-sm-5" name="mch_id" type="text" value="{{$row['mch_id']}}" placeholder="" data-rule="商户id:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="mch_id"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 克隆源id：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="clone_id" class="form-control col-sm-5" name="clone_id" type="text" value="{{$row['clone_id']}}" placeholder="" data-rule="克隆源id:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="clone_id"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 渠道id：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="cha_id" class="form-control col-sm-5" name="cha_id" type="text" value="{{$row['cha_id']}}" placeholder="" data-rule="渠道id:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="cha_id"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 用户id：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="user_id" class="form-control col-sm-5" name="user_id" type="text" value="{{$row['user_id']}}" placeholder="" data-rule="用户id:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="user_id"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 商品d：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="prod_id" class="form-control col-sm-5" name="prod_id" type="text" value="{{$row['prod_id']}}" placeholder="" data-rule="商品d:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="prod_id"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 货品id：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="sku_id" class="form-control col-sm-5" name="sku_id" type="text" value="{{$row['sku_id']}}" placeholder="" data-rule="货品id:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="sku_id"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 稿件id：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="manuscript_id" class="form-control col-sm-5" name="manuscript_id" type="text" value="{{$row['manuscript_id']}}" placeholder="" data-rule="稿件id:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="manuscript_id"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 封面缩略图：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="prj_image" class="form-control col-sm-5" name="prj_image" type="text" value="{{$row['prj_image']}}" placeholder="" data-rule="封面缩略图:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="prj_image"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 作品引用图片OSS存储根目录：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="prj_images_path" class="form-control col-sm-5" name="prj_images_path" type="text" value="{{$row['prj_images_path']}}" placeholder="" data-rule="作品引用图片OSS存储根目录:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="prj_images_path"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 文件路径：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="prj_file_path" class="form-control col-sm-5" name="prj_file_path" type="text" value="{{$row['prj_file_path']}}" placeholder="" data-rule="文件路径:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="prj_file_path"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 作品文件类型;1:diy,2:稿件：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="prj_file_type" class="form-control col-sm-5" name="prj_file_type" type="text" value="{{$row['prj_file_type']}}" placeholder="" data-rule="作品文件类型;1:diy,2:稿件:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="prj_file_type"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 作品p数：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="prj_page_num" class="form-control col-sm-5" name="prj_page_num" type="text" value="{{$row['prj_page_num']}}" placeholder="" data-rule="作品p数:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="prj_page_num"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 模板id：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="prj_tpl_id" class="form-control col-sm-5" name="prj_tpl_id" type="text" value="{{$row['prj_tpl_id']}}" placeholder="" data-rule="模板id:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="prj_tpl_id"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 是否为手机作品：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="is_mobile" class="form-control col-sm-5" name="is_mobile" type="text" value="{{$row['is_mobile']}}" placeholder="" data-rule="是否为手机作品:required">
                    <span class="col-sm-7 color-6A6969"> 请补充此字段的限制规则。</span>
                </div>
                <span class="msg-box" style="position:static;" for="is_mobile"></span>
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

