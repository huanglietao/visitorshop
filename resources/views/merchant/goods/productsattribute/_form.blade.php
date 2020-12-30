
<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/goods/products_attribute/save" onsubmit="return false;" autocomplete="off">
    <input type=hidden value="{{$row['attr_id']}}" name="id" id="id">
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 所属分类：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <select   class="form-control col-sm-5"  style="display: inline-block; margin-right: 1%; " id="cate_id" name="cate_id" class="form-control" data-rule="所属分类:required" data-msg="请先选择所属分类">
                        @if(empty($categoryList))
                            <option value="">无</option>
                        @else
                            <option value="">无</option>
                            @foreach($categoryList as $k=>$v)
                                <option value={{$v['cate_id']}} @if($v['cate_id'] == $row['cate_id']) selected @endif >{!! $v['cate_name'] !!}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <span class="msg-box" style="position:static;" for="cate_parent_id"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-attr_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 属性名称：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="cate_name" class="form-control col-sm-5" name="attr_name" type="text" value="{{$row['attr_name']}}" placeholder="" data-rule="分类名称:required">
                    <span class="col-sm-7 color-6A6969 attr-span"> 请填写该属性的名称</span>
                </div>
                <span class="msg-box" style="position:static;" for="cate_name"></span>
            </div>
        </div>



        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-attr_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 属性值：</label>
            <div class="col-xs-12 col-sm-10" style="padding-top: 5px;">
                    <a href="javascript:;" class="btn btn-attr-add" title="添加" ><i class="fa fa-plus"></i> 添加</a>
            </div>
        </div>



        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-attr_name" class="control-label col-xs-12 col-sm-2"></label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div id="attr-table" class="col-sm-5" style="padding-left: 0!important; ">
                        <table class="border_table" style="width:100%;text-align:center">
                            <thead>
                            <tr>
                                <th><span style="color:red">*</span>值名称</th>
                                <th>示意图</th>

                                <th>排序</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($attrValues as $k=>$v)
                                <tr>
                                    <td style="display: none"><input type="hidden" name="attrValue[attr_val_id][]" value="{{$v['attr_val_id']}}" class="attrid"></td>
                                    <td><input  type="text" name="attrValue[attr_val_name][]" value="{{$v['attr_val_name']}}"> </td>
                                    <td>
                                        <input type='hidden' name='attrValue[attr_val_icon][]'  value="{{$v['attr_val_icon']}}">
                                        <img style="width:30px" src="{{$v['attr_val_icon']}}"/>
                                        <input style='width:140px;display: inline-block' type='file' onchange='upload(this,0)'>
                                    </td>
                                    <td><input type="text" name="attrValue[sort][]" value="{{$v['sort']}}"></td>
                                    <td><a href="javascript:;" class="btn btn-xs btn-danger btn-attr-del" title="删除"><i class="fa fa-trash"></i></a></td>
                                </tr>
                             @endforeach
                            </tbody>
                        </table>
                    </div>
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

