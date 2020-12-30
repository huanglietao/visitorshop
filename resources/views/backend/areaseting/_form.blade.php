<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/areaseting/save" onsubmit="return false;" autocomplete="off">
    <input type=hidden value="{{$row['area_id']}}" name="id" id="id">
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 区域名称：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="area_name" class="form-control col-sm-5" name="area_name" type="text" value="{{$row['area_name']}}" placeholder="" data-rule="区域名称:required">
                    <span class="col-sm-7 color-6A6969"> 请填写20个字符内的中文名称</span>
                </div>
                <span class="msg-box" style="position:static;" for="area_name"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 所属上级：</label>
            <div class="col-xs-12 col-sm-10">
                @component('component/areas')
                @endcomponent
                    <span class="col-sm-7 color-6A6969">如添加第3级地区需要选择省市，第2级需选择省,第1级全部不选</span>
                <span class="msg-box" style="position:static;" for="pid"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
             区域简称：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="short_name" class="form-control col-sm-5" name="short_name" type="text" value="{{$row['short_name']}}" placeholder="" data-rule="">
                    <span class="col-sm-7 color-6A6969"> 即区域名称简称，为20个字符内的中文</span>
                </div>
                <span class="msg-box" style="position:static;" for="sname"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 区域等级：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="level" class="form-control col-sm-5" name="level" type="text" value="{{$row['level']}}" placeholder="" data-rule="区域等级:required;integer(+);length(~1)">
                    <span class="col-sm-7 color-6A6969"> 填写正整数，如添加省级为1，市级为2，以此类推</span>
                </div>
                <span class="msg-box" style="position:static;" for="level"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
             区域编码：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="area_code" class="form-control col-sm-5" name="area_code" type="text" value="{{$row['area_code']}}" placeholder="" data-rule="">
                    <span class="col-sm-7 color-6A6969"> 每个地区对应的编码，填写数字，不能为负数</span>
                </div>
                <span class="msg-box" style="position:static;" for="citycode"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            邮政编码：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="zip_code" class="form-control col-sm-5" name="zip_code" type="text" value="{{$row['zip_code']}}" placeholder="" data-rule="integer(+);length(~8)">
                    <span class="col-sm-7 color-6A6969"> 请填写正整数或0，不能为负数</span>
                </div>
                <span class="msg-box" style="position:static;" for="yzcode"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
             组合名称：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="comb_name" class="form-control col-sm-5" name="comb_name" type="text" value="{{$row['comb_name']}}" placeholder="" data-rule="">
                    <span class="col-sm-7 color-6A6969"> 如：中国,北京,北京市,东城区</span>
                </div>
                <span class="msg-box" style="position:static;" for="mername"></span>
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

