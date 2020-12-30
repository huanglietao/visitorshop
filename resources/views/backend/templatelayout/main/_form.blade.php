<link rel="stylesheet" href="{{ URL::asset('css/backend/template/tempmain.css') }}">
<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/templatelayout/main/save" onsubmit="return false;" autocomplete="off">
    <input type=hidden value="{{$row['temp_layout_id']}}" name="id" id="id">

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 布局名称：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="temp_layout_name" class="form-control col-sm-7" name="temp_layout_name" type="text" value="{{$row['temp_layout_name']}}" placeholder="" data-rule="名称:required;length(~30)">
                    <span class="col-sm-5 color-6A6969"> 支持中英文，长度不超过30字符。</span>
                </div>
                <span class="msg-box" style="position:static;" for="temp_layout_name"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 布局版式：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <select  class="form-control col-sm-7" id="temp_layout_type" name="temp_layout_type" data-rule="布局版式:required">
                        @foreach($layoutType as $k=>$v)
                            <option  value={{$k}} @if($k == $row['temp_layout_type']) selected @endif >{!! $v !!}</option>
                        @endforeach
                    </select>
                    <span class="col-sm-5 color-6A6969"> 请选择所属布局版式</span>
                </div>
                <span class="msg-box" style="position:static;" for="temp_layout_type"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 商品分类：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <select  class="form-control col-sm-7" id="goods_type_id" name="goods_type_id" @if(!$row['temp_layout_id']) data-rule="商品分类:required" @endif  @if($row['temp_layout_id']) disabled @endif >
                        <option value="">请选择</option>
                        @foreach($goodsCateType as $k=>$v)
                            <option  value={{$k}} @if($k == $row['goods_type_id']) selected @endif >{!! $v !!}</option>
                        @endforeach
                    </select>
                    @if($row['temp_layout_id'])
                        <input type=hidden value="{{$row['goods_type_id']}}" name="goods_type_id" >
                    @endif
                    <span class="col-sm-5 color-6A6969">选择所属商品分类</span>
                </div>
                <span class="msg-box" style="position:static;" for="goods_type_id"></span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 产品规格：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <select  class="form-control col-sm-7 product-size" id="specifications_id" name="specifications_id" @if(!$row['temp_layout_id']) data-rule="产品规格:required" @endif >
                        <option value="">请选择</option>
                        @if($row['temp_layout_id'])
                            @foreach($specLink as $k=>$v)
                                <option value={{$k}} @if($k == $row['specifications_id']) selected @endif >{!! $v !!}</option>
                            @endforeach
                        @endif
                    </select>
                   {{-- @if($row['temp_layout_id'])
                        <input type=hidden value="{{$row['specifications_id']}}" name="specifications_id" >
                    @endif--}}
                    <span class="col-sm-5 color-6A6969"> 选择所属产品规格，如为空请先添加该商品分类的所属规格</span>
                </div>
                <span class="msg-box" style="position:static;" for="specifications_id"></span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">规格标签：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  class="form-control col-sm-7"  type="text" id="layout_spec_style" value="@if($row['layout_spec_style']==ZERO)无@else {{$sizeType[$row['layout_spec_style']]}}@endif">
                    <input  id="layout_spec_style" class="form-control col-sm-7" name="layout_spec_style" type="hidden" value="{{$row['layout_spec_style']}}">
                    <span class="col-sm-5 color-6A6969"> </span>
                </div>
             {{--   <div class="row">
                    <select  class="form-control col-sm-7 size-type" id="layout_spec_style" name="layout_spec_style">
                        <option value="">请选择</option>
                        @if($row['temp_layout_id'])
                        @foreach($sizeType as $k=>$v)
                            <option value={{$k}} @if($k == $row['layout_spec_style']) selected @endif >{!! $v !!}</option>
                        @endforeach
                        @endif
                    </select>
                    @if($row['temp_layout_id'])
                    <input type=hidden value="{{$row['layout_spec_style']}}" name="layout_spec_style" >
                    @endif
                    <span class="col-sm-5 color-6A6969"> 选择所属规格标签</span>
                </div>--}}
                <span class="msg-box" style="position:static;" for="layout_spec_style"></span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">排序：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="temp_layout_sort" class="form-control col-sm-7" name="temp_layout_sort" type="text" value="{{$row['temp_layout_sort']}}" placeholder="" data-rule="integer(+0);length(~5)">
                    <span class="col-sm-5 color-6A6969"> 填写正整数，如：1</span>
                </div>
                <span class="msg-box" style="position:static;" for="temp_layout_sort"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">DPI：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="layout_dpi" class="form-control col-sm-7" name="layout_dpi" type="text" value="{{$row['layout_dpi']}}" placeholder="" data-rule="integer(+);length(~5)">
                    <span class="col-sm-5 color-6A6969"> DPI为正整数</span>
                </div>
                <span class="msg-box" style="position:static;" for="layout_dpi"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">子页尺寸：</label>
            <div class="spec-table temp-page-spec" style="@if($row['temp_layout_id'])border:1px solid #d2d6de @else @endif border: none">

                <table  class="table table-striped page-table">
                    @if($row['temp_layout_id'])
                    <tr>
                        <td style="width:80px;" align="right">设计区尺寸：</td>
                        <td style="width:120px;">宽:{{$specInfo['size_design_w']}}mm </td><td>高:{{$specInfo['size_design_h']}}mm</td>
                    </tr>
                    <tr>
                        <td style="width:80px;" align="right">设计区定位：</td>
                        <td style="width:120px;">上:{{$specInfo['size_location_top']}}mm </td> <td>左:{{$specInfo['size_location_left']}}mm</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td><td style="width:80px;"> 下:{{$specInfo['size_location_bottom']}}mm </td><td>右:{{$specInfo['size_location_right']}}mm</td></tr>
                    <tr>
                        <td style="width:80px;" align="right">提示线：</td>
                        <td style="width:120px;">上:{{$specInfo['size_tip_top']}}mm </td> <td>左:{{$specInfo['size_tip_left']}}mm</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td><td style="width:120px;"> 下:{{$specInfo['size_tip_bottom']}}mm </td><td>右:{{$specInfo['size_tip_right']}}mm</td></tr>
                    <tr>
                        <td style="width:80px;" align="right">出血位：</td>
                        <td style="width:120px;">上:{{$specInfo['size_cut_top']}}mm </td> <td>左:{{$specInfo['size_cut_left']}}mm</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td><td style="width:120px;"> 下:{{$specInfo['size_cut_bottom']}}mm </td><td>右:{{$specInfo['size_cut_right']}}mm</td></tr>
                    <tr>
                        <td style="width:80px;font-weight:bold" align="right">页面特性：</td>
                        <td style="width:120px;"></td><td></td>
                    </tr>
                    <tr>
                        <td style="width:80px;" align="right">单/双页印刷：</td>
                        <td style="width:120px;">@if($specInfo['size_is_2faced']==ZERO) 单页@else双页 @endif</td><td></td>
                    </tr>
                    <tr>
                        <td style="width:80px;" align="right">能否合成：</td>
                        <td style="width:120px;">@if($specInfo['size_is_output']==ZERO) 否@else是 @endif</td><td></td>
                    </tr>
                    <tr>
                        <td style="width:80px;" align="right">能否编辑：</td>
                        <td style="width:120px;">@if($specInfo['size_is_locked']==ZERO) 否@else是 @endif</td><td></td>
                    </tr>
                    <tr>
                        <td style="width:80px;" align="right">能否显示：</td>
                        <td style="width:120px;">@if($specInfo['size_is_display']==ZERO) 否@else是 @endif</td><td></td>
                    </tr>
                    <tr>
                        <td style="width:80px;" align="right">是否跨页：</td>
                        <td style="width:120px;">@if($specInfo['size_is_cross']==ZERO) 不跨页@else 跨页 @endif</td><td></td>
                    </tr>
                        @endif
                </table>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal;line-height: 85px;" for="c-mch_name" class="control-label col-xs-12 col-sm-2">示意图：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div style="padding-left: 0;height: 108px" class="col-sm-7">
                        @component('component/image_upload',['name'=>'temp_layout_thumb','uploader'=>'templateLayout','direction'=>1,'browse_btn'=>'form-layout','content_class'=>'tempLayout','img_format'=>'gif,jpg,jpeg,png','num'=>1,'value'=>$row['temp_layout_thumb']])
                        @endcomponent
                    </div>
                    <span class="col-sm-5 color-6A6969"  style="margin-top: 30px">建议尺寸:300*300，大小：10M。格式： jpg / jpeg / png</span>
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

