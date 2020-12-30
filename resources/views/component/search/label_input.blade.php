<!-- 搜索中 常用 标签+表单形式   -->
<div class="row" >
    <div class="col-xl-4 col-md-4" style="text-align: right" >
        <label class="control-label" style="font-weight: normal">{{$label}}:</label>
    </div>
    <div class="col-xl-8 col-md-8">
        <input name="{{isset($inp_name)?$inp_name:''}}" id="{{isset($inp_id)?$inp_id:''}}" type="text" class="form-control">
    </div>
</div>

<!--搜索中 常用 标签+表单形式end  -->