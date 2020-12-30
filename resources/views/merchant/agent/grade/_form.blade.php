<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/agent/grade/save" onsubmit="return false;" autocomplete="off">
    <input type=hidden value="{{$row['cust_lv_id']}}" name="cust_lv_id" id="id">
    <input type=hidden value="{{$row['mch_id']}}" name="mch_id" id="mch_id">
    <input type=hidden value="{{$row['cust_lv_type']}}" name="cust_lv_type" id="cust_lv_type">
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 等级名称：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="cust_lv_name" class="form-control col-sm-5" name="cust_lv_name" type="text" value="{{$row['cust_lv_name']}}" placeholder="" data-rule="等级名称:required">
                    <span class="col-sm-7 color-6A6969"> 等级名称，如：青铜组。</span>
                </div>
                <span class="msg-box" style="position:static;" for="cust_lv_name"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 等级折扣：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="cust_lv_discount" class="form-control col-sm-5" name="cust_lv_discount" type="text" value="{{$row['cust_lv_discount']}}" placeholder="" data-rule="等级折扣:required">
                    <span class="col-sm-7 color-6A6969"> 该等级可以得到的折扣，50代表打5折，100代表不打折。</span>
                </div>
                <span class="msg-box" style="position:static;" for="cust_lv_discount"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 等级描述：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <textarea id="cust_lv_desc" rows="5"  class="form-control col-sm-5" name="cust_lv_desc" type="text" placeholder="" data-rule="等级描述:required">{{$row['cust_lv_desc']}}</textarea>
                    {{--<input  id="cust_lv_desc" class="form-control col-sm-5" name="cust_lv_desc" type="text" value="{{$row['cust_lv_desc']}}" placeholder="" data-rule="等级描述:required">--}}
                    <span class="col-sm-7 color-6A6969"> 等级的说明，如：达到多少经验值或者要求就可达到该等级。</span>
                </div>
                <span class="msg-box" style="position:static;" for="cust_lv_desc"></span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">排序：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="sort" class="form-control col-sm-5" name="sort" type="text" value="{{$row['sort']}}" placeholder="" >
                    <span class="col-sm-7 color-6A6969"> 请填写排序，不填则默认为0。</span>
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

