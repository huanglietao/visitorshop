
    @foreach ($radio as $key=>$v)
        <div class="c_d_radio {{isset($radioclass[$key])?$radioclass[$key]:''}}">
            @php

                $uni_id = uniqid();
            @endphp
            <input type="radio" value="{{$key}}" name="{{$name}}" @if(isset($is_disabled)&&$is_disabled==1) disabled @endif @php if(isset($default_key)&&$default_key==$key) echo "checked"; @endphp   class="radio {{isset($classname[$key])?$classname[$key]:''}}" id="{{$uni_id}}" data-value="{{isset($data_value[$key])?$data_value[$key]:''}}">
            <label for="{{$uni_id}}" style="margin-top: 0;margin-left: {{isset($left_distance)?$left_distance:'0'}}px; margin-right: {{isset($right_distance)?$right_distance:'10'}}px;"></label>
            <span class="c_d_radio_text" style="vertical-align: top;">{{$v}}</span>
        </div>
    @endforeach


