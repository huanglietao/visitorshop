<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/customcategory/save" onsubmit="return false;" autocomplete="off">
    <input type=hidden value="{{$row['cus_cate_id']}}" name="id" id="id">
        {{--<div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 父级分类：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <select   class="form-control col-sm-5" style="display: inline-block; margin-right: 1%; " id="cate_parent_id" name="cate_parent_id" class="form-control" >
                            <option value="0">无</option>
                            @foreach($categoryList as $k=>$v)
                                <option value={{$k}} @if($k == $row['cate_parent_id']) selected @endif >{!! $v !!}</option>
                            @endforeach
                    </select>
                </div>
                <span class="msg-box" style="position:static;" for="cate_parent_id"></span>
            </div>
        </div>--}}
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 分类名称：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="cate_name" class="form-control col-sm-5" name="cate_name" type="text" value="{{$row['cate_name']}}" placeholder="" data-rule="分类名称:required">
                    <span class="col-sm-7 color-6A6969"> 请填写该分类的名称</span>
                </div>
                <span class="msg-box" style="position:static;" for="cate_name"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">分类别名：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="cate_nickname" class="form-control col-sm-5" name="cate_nickname" type="text" value="{{$row['cate_nickname']}}" placeholder="">
                    <span class="col-sm-7 color-6A6969"> 请填写该分类的别名</span>
                </div>
                <span class="msg-box" style="position:static;" for="cate_nickname"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">分类单位：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="cate_unit" class="form-control col-sm-5" name="cate_unit" type="text" value="{{$row['cate_unit']}}" placeholder="">
                    <span class="col-sm-7 color-6A6969"> 请填写该分类的单位</span>
                </div>
                <span class="msg-box" style="position:static;" for="cate_unit"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">分类关键词：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="cate_keywords" class="form-control col-sm-5" name="cate_keywords" type="text" value="{{$row['cate_keywords']}}" placeholder="">
                    <span class="col-sm-7 color-6A6969"> 请填写该分类的关键词</span>
                </div>
                <span class="msg-box" style="position:static;" for="cate_keywords"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            分类描述：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="cate_desc" class="form-control col-sm-5" name="cate_desc" type="text" value="{{$row['cate_desc']}}" placeholder="" >
                    <span class="col-sm-7 color-6A6969">请填写该分类描述</span>
                </div>
                <span class="msg-box" style="position:static;" for="cate_desc"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 分类状态：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row radio">
                    <div style="display: inline-block;padding-top: 5px;padding-left: 0" class="col-sm-5">
                        @component('component/radio',['radio'=>['1'=>'启用','0'=>'禁用'],'name'=>'cate_status','default_key'=>$row['cate_status']??1])
                        @endcomponent
                    </div>
                    <span class="col-sm-7 color-6A6969"  style="margin-top: 3px"> 启用：正常；禁用：不能使用</span>

                </div>
                <span class="msg-box" style="position:static;" for="cate_status"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
             排序：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="sort" class="form-control col-sm-5" name="sort" type="number" value="{{$row['sort']}}" placeholder="" >
                    <span class="col-sm-7 color-6A6969">请填写排序，不填则默认为0 </span>
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

