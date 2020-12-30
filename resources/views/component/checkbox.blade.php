<!-- checkbox组件-->

@foreach ($checkbox as $key=>$item)
    @php
       $unquid = uniqid();
    @endphp
    <input id="{{$unquid}}"  class="btn-checkbox {{isset($custom_class)?$custom_class:''}}" @if(isset($is_disabled)&&$is_disabled==1) disabled @endif value="{{$key}}" type="checkbox" name="{{$name[$loop->index]}}" data-value="{{isset($data_value)?$data_value:''}}" @if(isset($checked)&&($checked==$key|| (is_array($checked) && in_array($key,$checked)))) checked @endif/>
    <label for="{{$unquid}}"></label>
    <span style="margin-left: {{isset($left_distance)?$left_distance:'25'}}px;margin-right: {{isset($right_distance)?$right_distance:'30'}}px;">{{$item}}</span>
@endforeach
