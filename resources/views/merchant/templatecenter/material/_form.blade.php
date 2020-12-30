<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/templatecenter/material/save" onsubmit="return false;" autocomplete="off">

        <input type=hidden value="{{$row['material_id']}}" name="id" id="id">
        <input type=hidden value="{{$mid}}" name="mch_id" id="mch_id">
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 所属分类：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    @if($matType=='background')
                        <select  class="form-control col-sm-5 mater-cate" id="material_cateid" name="material_cateid" data-rule="所属分类:required">
                            <option value="">请选择</option>
                            @foreach($cateList as $k=>$v)
                                <option value={{$k}} @if($k == $row['material_cateid']) selected @endif >{!! $v !!}</option>
                            @endforeach
                        </select>
                    @else
                        <select  class="form-control col-sm-3 mater-cate" id="material_parent_cateid" name="material_parent_cateid" data-rule="所属分类:required" style="margin-right: 10px" @if($row['material_id']) disabled @endif>
                            <option value="">请选择</option>
                            @foreach($cateList as $k=>$v)
                                <option value={{$k}} @if($k == $parentCate) selected @endif >{!! $v !!}</option>
                            @endforeach
                        </select>

                        <select  class="form-control col-sm-3" id="material_cateid" name="material_cateid" data-rule="所属二级分类:required">
                            @if($row['material_id'])
                                @foreach($childCateList as $k=>$v)
                                    <option value={{$k}} @if($k == $row['material_cateid']) selected @endif >{!! $v !!}</option>
                                @endforeach
                            @else
                                <option value="">请选择</option>
                            @endif
                        </select>
                    @endif
                    <span class="col-sm-5 color-6A6969"> 请选择该素材的所属分类</span>

                </div>
                <span class="msg-box col-sm-3" style="position:static;" for="material_parent_cateid"></span>
                <span class="msg-box" style="position:static;" for="material_cateid"></span>
            </div>
        </div>
        @if($matType=='background')
            <div class="form-group row form-item">
                <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                    <span style="color:red">*</span> 规格标签：</label>
                <div class="col-xs-12 col-sm-10">
                    <div class="row">
                        <select  class="form-control col-sm-5" id="specification_style" name="specification_style" data-rule="规格标签:required">
                            <option value="">请选择</option>
                            @foreach($specStyle as $k=>$v)
                                <option value={{$k}} @if($k == $row['specification_style']) selected @endif >{!! $v !!}</option>
                            @endforeach
                        </select>
                        <span class="col-sm-7 color-6A6969"> 所有尺寸相似的规格可以归属同一个规格标签</span>
                    </div>

                    <span class="msg-box" style="position:static;" for="specification_style"></span>
                </div>
            </div>
        @endif

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">名称：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="material_name" class="form-control col-sm-6" name="material_name" type="text" value="{{$row['material_name']}}" >
                    <span class="col-sm-6 color-6A6969"> 中英文名称，长度不能超过30字符，可为空。</span>
                </div>
                <span class="msg-box" style="position:static;" for="material_name"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">排序：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="material_sort" class="form-control col-sm-6" name="material_sort" type="text" value="{{$row['material_sort']}}" data-rule="排序:integer(+0);length(~5)">
                    <span class="col-sm-6 color-6A6969"> 填写正整数。</span>
                </div>
                <span class="msg-box" style="position:static;" for="material_sort"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">用途：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row radio">
                    <div style="display: inline-block;padding-top: 5px;padding-left: 0" class="col-sm-5">
                        @component('component/radio',['radio'=>['1'=>'通用','2'=>'内部'],'name'=>'material_use_type','default_key'=>$row['material_use_type']??ONE])
                        @endcomponent
                    </div>
                    <span class="col-sm-7 color-6A6969"  style="margin-top: 3px"> 通用：公共素材全部人可用；内部：只能设计师使用</span>

                </div>
                <span class="msg-box" style="position:static;" for="material_use_type"></span>
            </div>
        </div>

        @if(empty($row['material_id']))
            <div class="form-group row form-item material-upload" style="display: none">
                <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2 "><span style="color:red">*</span> 图片上传：</label>
                <div class="col-xs-12 col-sm-10">
                    <div class="row">
                        @if($matType=='background')
                            @component('component/imgUpload',['plid'=>"backgImg",'parms'=>'{"type":"background","uniqid":"'.$uniqid.'","m_type":0}','plurl'=>$apiurl.'/material/upload','name'=>'attachment_id','uploader'=>'bgoundUpoad','direction'=>1,'browse_btn'=>'btn-bground','content_class'=>'background','img_format'=>'gif,jpg,jpeg,png','num'=>100,'value'=>''])
                            @endcomponent
                        @else
                            <div style="padding-left: 0" class="col-sm-3" id="upload-decorate">
                                @component('component/imgUpload',['plid'=>"decorateImge",'parms'=>'{"type":"decorate","uniqid":"'.$uniqid.'","m_type":0}','plurl'=>$apiurl.'/material/upload','name'=>'attachment_id','uploader'=>'decorateUpoad','direction'=>1,'browse_btn'=>'btn-decorate','content_class'=>'decorate','img_format'=>'gif,jpg,jpeg,png','num'=>100,'value'=>''])
                                @endcomponent
                            </div>
                            <div style="padding-left: 0;display: none" class="col-sm-3" id="upload-frame">
                                @component('component/imgUpload',['plid'=>"frameImg",'parms'=>'{"type":"frame","uniqid":"'.$uniqid.'","m_type":0}','plurl'=>$apiurl.'/material/upload','name'=>'attachment_id','uploader'=>'frameUpoad','direction'=>1,'browse_btn'=>'btn-frame','content_class'=>'frame','img_format'=>'gif,jpg,jpeg,png','num'=>100,'value'=>''])
                                @endcomponent
                            </div>
                        @endif
                        <span class="col-sm-7 color-6A6969" style="color: red">@if($matType=='material')如果是画框上传时需成对上传，命名规则为1.png和1_mask.png算为一组 @endif 格式：gif / jpg / jpeg / png。</span>
                    </div>
                </div>
                <div class="plupload-images-box" style="width: 100%;padding-top: 15px;display: flex">
                    <label style=" font-weight: normal;line-height: 85px;" for="c-mch_name" class="control-label col-xs-12 col-sm-2"></label>
                    <div class="col-xs-12 col-sm-10 images-preview" style="padding-left: 8px"></div>
                </div>
            </div>
        @endif

    </form>
    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            @if($row['mch_id']==$mid || empty($row['material_id']))
            <button type="submit" class="btn btn-primary btn-3F51B5 btn-sure btn-submit">确定</button>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <button type="reset" class="btn btn-write btn-reset">重置</button>
            @else
                <button type="reset" class="btn btn-3F51B5 btn-primary btn-sure btn-close" onclick="layer.closeAll();">关闭</button>
            @endif
        </div>
    </div>
</div>

