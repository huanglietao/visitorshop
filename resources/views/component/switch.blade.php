<!-- 通用面包屑 -->
<div class="switch">
    <div class="sw_button sw_r" id="sw_button-1" style="width: 40px!important;height: 17px">
        <input type="checkbox" class="sw_checkbox  {{isset($custom_class)?$custom_class:''}}" data-value = "{{isset($data_id)?$data_id:''}}" @if(isset($status)&&$status!=0) checked @endif>
        <div class="sw_knobs"></div>
        <div class="sw_layer"></div>
    </div>
</div>

