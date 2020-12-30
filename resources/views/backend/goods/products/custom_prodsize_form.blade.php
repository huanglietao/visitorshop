<!-- form表单视图 -->

<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/goods/custom_products_size/save" onsubmit="return false;" autocomplete="off">

        <input type="hidden" name="prod_id" value="{{$prod_id}}">
        <input type="hidden" name="size_id" value="{{$row['size_id']}}">
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
                <label class="box-label col-sm-2">单/双页:</label>
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
    @if($action == 'edit')
    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button type="submit" class="btn btn-primary btn-3F51B5 btn-sure btn-custom-size-save">确定</button>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <button type="reset" class="btn btn-write btn-reset">重置</button>
        </div>
    </div>
    @endif
</div>

