<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/goods/products_size/save" onsubmit="return false;" autocomplete="off">
    <input type=hidden value="{{$row['size_id']}}" name="id" id="id">
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 所属分类：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <select   class="form-control col-sm-5"  style="display: inline-block; margin-right: 1%; " id="size_cate_id" name="size_cate_id" class="form-control" data-rule="所属分类:required" data-msg="请先选择所属分类">
                        @if(empty($categoryList))
                            <option value="">无</option>
                        @else
                            <option value="">无</option>
                            @foreach($categoryList as $k=>$v)
                                <option value={{$v['cate_id']}} @if($v['cate_id'] == $row['size_cate_id']) selected @endif >{!! $v['cate_name'] !!}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <span class="msg-box" style="position:static;" for="size_cate_id"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-size_type" class="control-label col-xs-12 col-sm-2">规格标签：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <select   class="form-control col-sm-5"  style="display: inline-block; margin-right: 1%; " id="size_type" name="size_type" class="form-control">
                        @if(empty($sizeTypeList))
                            <option value="">无</option>
                        @else
                            <option value="">无</option>
                            @foreach($sizeTypeList as $k=>$v)
                                <option value={{$k}} @if($k == $row['size_type']) selected @endif >{!! $v !!}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <span class="msg-box" style="position:static;" for="size_type"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-size_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 规格名称：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="size_name" class="form-control col-sm-5" name="size_name" type="text" value="{{$row['size_name']}}" placeholder="" data-rule="规格名称:required">
                    <span class="col-sm-7 color-6A6969 attr-span"> 请填写该规格的名称</span>
                </div>
                <span class="msg-box" style="position:static;" for="size_name"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-size_dpi" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> Dpi：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="size_dpi" class="form-control col-sm-5" name="size_dpi" type="text" value="{{$row['size_dpi']}}" placeholder="" data-rule="规格dpi:required">
                    <span class="col-sm-7 color-6A6969 attr-span"> 请填写该规格的Dpi</span>
                </div>
                <span class="msg-box" style="position:static;" for="size_dpi"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="prod_title" class="control-label col-xs-12 col-sm-2">
                规格描述：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <textarea class="form-control col-sm-5" name="size_desc" id="size_desc" cols="30" rows="5" style="resize: none;">{{$row['size_desc']}}</textarea>
                    <span class="col-sm-7 color-6A6969 attr-span"> 描述不能超过200个字符</span>
                </div>
                <span class="msg-box" style="position:static;" for="prod_title"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
               示意图：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div style="padding-left: 0;height: 108px" class="col-sm-5">
                        @component('component/image_upload',['name'=>'size_icon','uploader'=>'uploader1','num'=>1,'value'=>$row['size_icon'],'direction'=>1,'browse_btn'=>'form-avatar','content_class'=>'background','img_format'=>'jpg,jpeg,png'])
                        @endcomponent
                    </div>
                    <span class="col-sm-7 color-6A6969 attr-span"  style="margin-top: 3px">推荐：150 * 50 PX，大小：100K。格式： jpg / jpeg / png</span>
                </div>
                <span class="msg-box" style="position:static;" for="express_logo"></span>
            </div>
        </div>
        {{--<div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">状态：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row radio">
                    <div style="display: inline-block;padding-top: 5px;padding-left: 0" class="col-sm-5">
                        @component('component/radio',['radio'=>['1'=>'启用','0'=>'禁用'],'name'=>'size_status','default_key'=>$row['size_status']??1])
                        @endcomponent
                    </div>
                    <span class="col-sm-7 color-6A6969 attr-span"> 启用：正常；禁用：不能使用</span>
                </div>
                <span class="msg-box" style="position:static;" for="express_status"></span>
            </div>
        </div>--}}
        @if(empty($row))
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-page-type" class="control-label col-xs-12 col-sm-2">参数定义：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">

                    <div style="display: inline-block;padding-top: 5px;padding-left: 0" class="col-sm-12">
                        @foreach($pageType as $kk=>$vv)
                            @component('component/checkbox',['checkbox'=>[$kk=>$vv],'name'=>['page_type[]'],'custom_class'=>'page_type_checkbox','right_distance' => '30'])
                            @endcomponent
                        @endforeach
                    </div>
                    <span class="col-sm-7 color-6A6969 attr-span"></span>
                </div>
                <span class="msg-box" style="position:static;" for="express_status"></span>
            </div>
        </div>
        @endif



        @foreach($pageType as $k=>$v)
        <div class="divbox item-{{$k}}" attr-type="{{$k}}" id="face" @if(empty($row))style="display: none" @endif>

            @if(!empty($sizeInfoArr))
            <input type="hidden" name='page_type[]' value="{{$k}}">
            @endif
            <input type="hidden" value="@if(!empty($sizeInfoArr)){{$sizeInfoArr[$k]['size_info_id']}}@endif" name="specificationsParam[size_info_id][{{$k}}]">
            <div class="borderbox border-{$k}">{{$v}}尺寸（不含书脊）</div>
            <div class="maindiv" style="margin: 40px 0 0 75px">
                <div class="row">
                    <label class="box-label col-sm-2"><span style="color:red">*</span>设计区尺寸:</label>
                    <div class="col-sm-10 box-div">
                        <span>宽:</span>
                        <input class="boxin" value="@if(!empty($sizeInfoArr)){{$sizeInfoArr[$k]['size_design_w']}}@endif"  name="specificationsParam[size_design_w][{{$k}}]" type="text">
                        <span>mm</span>
                        <span>高:</span>
                        <input class="boxin" value="@if(!empty($sizeInfoArr)){{$sizeInfoArr[$k]['size_design_h']}}@endif"   name="specificationsParam[size_design_h][{{$k}}]" type="text">
                        <span>mm</span>
                    </div>
                </div>
            </div>
            <div class="maindiv">
                <div class="row">
                <label class="box-label col-sm-2"><span style="color:red">*</span>设计区定位:</label>
                <div class="col-sm-10 box-div">
                <span>上:</span>
                <input class="boxin" value="@if(!empty($sizeInfoArr)){{$sizeInfoArr[$k]['size_location_top']}}@endif"  name="specificationsParam[size_location_top][{{$k}}]" type="text">
                <span>mm</span>
                <span>左:</span>
                <input class="boxin" value="@if(!empty($sizeInfoArr)){{$sizeInfoArr[$k]['size_location_left']}}@endif"  name="specificationsParam[size_location_left][{{$k}}]" type="text">
                <span>mm</span>
                </div>
                </div>
            </div>
            <div class="maindiv">
                <div class="row">
                    <label class="box-label col-sm-2"></label>
                    <div class="col-sm-10 box-div">
                        <span>下:</span>
                        <input class="boxin" value="@if(!empty($sizeInfoArr)){{$sizeInfoArr[$k]['size_location_bottom']}}@endif"  name="specificationsParam[size_location_bottom][{{$k}}]" type="text">
                        <span>mm</span>
                        <span>右:</span>
                        <input class="boxin" value="@if(!empty($sizeInfoArr)){{$sizeInfoArr[$k]['size_location_right']}}@endif" name="specificationsParam[size_location_right][{{$k}}]" type="text">
                        <span>mm</span>
                    </div>
                </div>

            </div>
            <div class="maindiv">
                <div class="row">
                <label class="box-label col-sm-2"><span style="color:red">*</span>提示线定位:</label>
                <div class="col-sm-10 box-div">
                <span>上:</span>
                <input class="boxin" value="@if(!empty($sizeInfoArr)){{$sizeInfoArr[$k]['size_tip_top']}}@endif"  name="specificationsParam[size_tip_top][{{$k}}]" type="text">
                <span>mm</span>
                <span>左:</span>
                <input class="boxin" value="@if(!empty($sizeInfoArr)){{$sizeInfoArr[$k]['size_tip_left']}}@endif"  name="specificationsParam[size_tip_left][{{$k}}]" type="text">
                <span>mm</span>
                </div>
                </div>
            </div>
            <div class="maindiv">
                <div class="row">
                <label class="box-label col-sm-2"></label>
                <div class="col-sm-10 box-div">
                <span>下:</span>
                <input class="boxin" value="@if(!empty($sizeInfoArr)){{$sizeInfoArr[$k]['size_tip_bottom']}}@endif"  name="specificationsParam[size_tip_bottom][{{$k}}]" type="text">
                <span>mm</span>
                <span>右:</span>
                <input class="boxin" value="@if(!empty($sizeInfoArr)){{$sizeInfoArr[$k]['size_tip_right']}}@endif"  name="specificationsParam[size_tip_right][{{$k}}]" type="text">
                <span>mm</span>
                </div>
                </div>
            </div>
            <div class="maindiv">
                <div class="row">
                <label class="box-label col-sm-2"><span style="color:red">*</span>出血位/包边:</label>
                <div class="col-sm-10 box-div">
                <span>上:</span>
                <input class="boxin" value="@if(!empty($sizeInfoArr)){{$sizeInfoArr[$k]['size_cut_top']}}@endif"  name="specificationsParam[size_cut_top][{{$k}}]" type="text">
                <span>mm</span>
                <span>左:</span>
                <input class="boxin" value="@if(!empty($sizeInfoArr)){{$sizeInfoArr[$k]['size_cut_left']}}@endif"  name="specificationsParam[size_cut_left][{{$k}}]" type="text">
                <span>mm</span>
                </div>
                </div>
            </div>
            <div class="maindiv">
                <div class="row">
                <label class="box-label col-sm-2"></label>
                <div class="col-sm-10 box-div">
                <span>下:</span>
                <input class="boxin" value="@if(!empty($sizeInfoArr)){{$sizeInfoArr[$k]['size_cut_bottom']}}@endif"  name="specificationsParam[size_cut_bottom][{{$k}}]" type="text">
                <span>mm</span>
                <span>右:</span>
                <input class="boxin" value="@if(!empty($sizeInfoArr)){{$sizeInfoArr[$k]['size_cut_right']}}@endif"  name="specificationsParam[size_cut_right][{{$k}}]" type="text">
                <span>mm</span>
                </div>
                </div>
            </div>
            <div class="maindiv">
                <div class="row">
                    <label class="box-label col-sm-2">页面特性:</label>
                    <div class="col-sm-10 box-div"></div>
                </div>

            </div>

            <div class="maindiv">
                <div class="row">
                <label class="box-label col-sm-2">单/双面印刷:</label>
                <div class="col-sm-10 box-div">
                    @php if (!empty($sizeInfoArr)) {$faced_key = $sizeInfoArr[$k]['size_is_2faced']; }else{$faced_key = 0;}@endphp

                    @component('component/radio',['radio'=>$isTurn,'name'=>'specificationsParam[size_is_2faced]['.$k.']','default_key'=>$faced_key])
                    @endcomponent
                </div>
                </div>
            </div>
            <div class="maindiv">
                <div class="row">
                    <label class="box-label col-sm-2">是否跨页:</label>
                    <div class="col-sm-10 box-div">
                        @php if (!empty($sizeInfoArr)) {$cross_key = $sizeInfoArr[$k]['size_is_cross']; }else{$cross_key = 1;}@endphp
                        @component('component/radio',['radio'=>['1'=>'是','0'=>'否'],'name'=>'specificationsParam[size_is_cross]['.$k.']','default_key'=>$cross_key])
                        @endcomponent
                    </div>
                </div>
            </div>
            <div class="maindiv">
                <div class="row">
                <label class="box-label col-sm-2" >是否显示:  &nbsp;</label>
                <div class="col-sm-10 box-div">
                    @php if (!empty($sizeInfoArr)) {$display_key = $sizeInfoArr[$k]['size_is_display']; }else{$display_key = 1;}@endphp
                    @component('component/radio',['radio'=>['1'=>'是','0'=>'否'],'name'=>'specificationsParam[size_is_display]['.$k.']','default_key'=>$display_key])
                    @endcomponent
                </div>
                </div>
            </div>
            <div class="maindiv">
                <div class="row">
                <label class="box-label col-sm-2">可否合成: &nbsp;</label>
                    <div class="col-sm-10 box-div">
                        @php if (!empty($sizeInfoArr)) {$output_key = $sizeInfoArr[$k]['size_is_output']; }else{$output_key = 1;}@endphp
                        @component('component/radio',['radio'=>['1'=>'是','0'=>'否'],'name'=>'specificationsParam[size_is_output]['.$k.']','default_key'=>1])
                        @endcomponent
                    </div>
                </div>
            </div>
            <div class="maindiv">
                <div class="row">
                <label class="box-label col-sm-2">可否编辑: &nbsp;</label>
                    <div class="col-sm-10 box-div">
                        @php if (!empty($sizeInfoArr)) {$locked_key = $sizeInfoArr[$k]['size_is_locked']; }else{$locked_key = 1;}@endphp
                        @component('component/radio',['radio'=>['1'=>'是','0'=>'否'],'name'=>'specificationsParam[size_is_locked]['.$k.']','default_key'=>$locked_key])
                        @endcomponent
                    </div>
                </div>
            </div>
        </div>

        @endforeach


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

