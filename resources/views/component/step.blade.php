<div class="d_step">
    <div class="d_column" style="background: {{isset($default_color)?$default_color:'#bbb'}}">
@for($i = 1;$i<=$count;$i++)

            @php
                $inter = number_format(98/(intval($count)-1),1);
                    if ($i==1){
                       $left = "-1%";
                       $line_left = "0";
                       $inter = $inter+1;
                       $inter_val = "$inter"."%";
                    }else{
                        $left = $inter*($i-1);
                        $line_left = $left+1;
                        $left = "$left"."%";
                        $line_left = "$line_left"."%";
                        $inter_val = "$inter"."%";

                    }
            @endphp

            <span class="d_logistic_status d_logistic_status{{$i}}" style="left: {{$left}}">{{(isset($subtitle)&&isset($subtitle[$i]))?$subtitle[$i]:""}}</span>
            <div class="d_step_c_circle" style="left: {{$left}};background: {{isset($default_color)?"$default_color":"#bbb"}};" >
                @if(intval($now_step) > $i)
                    <div class="d_small_active_circle d_small_circle d_small_circle{{$i}}" style="background: {{isset($active_color)?"$active_color":"#259B24"}}">
                        <i class="fa fa-check d_check"></i>
                    </div>
                @elseif(intval($now_step) == $i)
                    <div class="d_small_active_circle d_small_circle d_small_circle{{$i}}" style="background: {{isset($active_color)?"$active_color":"#259B24"}}">
                        <span>{{$i}}</span>
                    </div>
                @else
                        <div class="d_small_circle d_small_circle{{$i}}">
                            <span>{{$i}}</span>
                        </div>
                @endif
            </div>
            <span class="d_logistic_time d_logistic_time{{$i}}" style="left: {{$left}}">{{(isset($bottom_title)&&isset($bottom_title[$i]))?$bottom_title[$i]:""}}</span>
            @if($i<intval($now_step))
                    <div class="line active_line line{{$i}}" style="width: {{$inter_val}};left:{{$line_left}};background: {{isset($active_color)?"$active_color":"#259B24"}} "></div>
            @endif





@endfor
    </div>
</div>

