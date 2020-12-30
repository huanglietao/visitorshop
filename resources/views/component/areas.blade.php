<!-- 省市区组件 宽度:410px,高度:22px-->

<div class="areas-one">
    <select class="areas-province" name="{{isset($province_name)?$province_name:'province'}}" data-value="{{isset($province_value)?$province_value:''}}">
        <option value="0">省</option>
    </select>
    <select class="areas-city" name="{{isset($city_name)?$city_name:'city'}}" data-value="{{isset($city_value)?$city_value:''}}">
        <option value="0">市</option>
    </select>
    <select class="areas-area" name="{{isset($district_name)?$district_name:'district'}}" data-value="{{isset($areas_value)?$areas_value:''}}">
        <option value="0">区</option>
    </select>
</div>


