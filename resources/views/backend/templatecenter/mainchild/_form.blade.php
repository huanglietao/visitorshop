<!-- form表单视图 -->
<link rel="stylesheet" href="{{ URL::asset('css/backend/template/tempmain.css') }}">
<div style="margin-top:30px" >
    <form class="form-horizontal common-form" id="form-save" method="post" action="/templatecenter/mainchild/save" onsubmit="return false;" autocomplete="off">
        <input type=hidden value="{{$row['main_temp_page_id']}}" name="id" id="id">
        <input id="specifications_id"  class="form-control" name="specifications_id" type="hidden" value="{{$specId}}">
        <input id="main_temp_page_tid"  class="form-control" name="main_temp_page_tid" type="hidden" value="{{$tid}}">
        <input id="spec_info_id"  class="form-control" name="spec_info_id" type="hidden" value="{{$specInfo['size_info_id']}}">
        <input id="main_temp_page_dpi"  class="form-control" name="main_temp_page_dpi" type="hidden" value="{{$specInfo['size_info_dpi']}}">

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 子页类型：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <select  class="form-control col-sm-7" id="main_temp_page_type" name="main_temp_page_type" data-rule="子页类型:required" >
                        @foreach($pageType as $k=>$v)
                            <option value={{$k}} @if($k == $row['main_temp_page_type']) selected @endif >{!! $configPage[$v] !!}</option>
                        @endforeach
                    </select>
                    <span class="col-sm-5 color-6A6969"> 选择正确的子页类型</span>
                </div>
                <span class="msg-box" style="position:static;" for="main_temp_page_type"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 子页名称：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="main_temp_page_name" class="form-control col-sm-7" name="main_temp_page_name" type="text" value="{{$row['main_temp_page_name']}}" data-rule="子页名称:required">
                    <span class="col-sm-5 color-6A6969"> 支持中英文，长度不超过30字符。</span>
                </div>
                <span class="msg-box" style="position:static;" for="main_temp_page_name"></span>
            </div>
        </div>

        @if($goodsCate->cate_flag==$tgl)
            <div class="form-group row form-item">
                <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">年份：</label>
                <div class="col-xs-12 col-sm-10">
                    <div class="row">
                        <select  class="form-control col-sm-7" id="main_temp_page_year" name="main_temp_page_year" >
                            @foreach($yearList as $k=>$v)
                                <option value={{$k}} @if($k == $row['main_temp_page_year']) selected @endif >{!! $v !!}</option>
                            @endforeach
                        </select>
                        <span class="col-sm-5 color-6A6969"> 选择年份</span>
                    </div>
                    <span class="msg-box" style="position:static;" for="main_temp_page_year"></span>
                </div>
            </div>
        @endif
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">排序：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="main_temp_page_sort" class="form-control col-sm-7" name="main_temp_page_sort" type="text" value="{{$row['main_temp_page_sort']}}" data-rule="integer(+0);length(~5)">
                    <span class="col-sm-5 color-6A6969"> 填写正整数，如：1</span>
                </div>
                <span class="msg-box" style="position:static;" for="main_temp_page_sort"></span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">子页尺寸：</label>
            <div class="spec-table temp-page-spec">

                <table  class="table table-striped page-table">
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
                        <td style="width:120px;">{{$isTurn[$specInfo['size_is_2faced']]}}</td><td></td>
                    </tr>
                    <tr>
                        <td style="width:80px;" align="right">能否合成：</td>
                        <td style="width:120px;">{{$yn[$specInfo['size_is_output']]}}</td><td></td>
                    </tr>
                    <tr>
                        <td style="width:80px;" align="right">能否编辑：</td>
                        <td style="width:120px;">{{$yn[$specInfo['size_is_locked']]}}</td><td></td>
                    </tr>
                    <tr>
                        <td style="width:80px;" align="right">能否显示：</td>
                        <td style="width:120px;">{{$yn[$specInfo['size_is_display']]}}</td><td></td>
                    </tr>
                    <tr>
                        <td style="width:80px;" align="right">是否跨页：</td>
                        <td style="width:120px;">{{$isCross[$specInfo['size_is_cross']]}}</td><td></td>
                    </tr>
                </table>
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

@section("js-file")
    <script src="{{ URL::asset('js/backend/tempcenter/maintemp.js')}}"></script>
@endsection
