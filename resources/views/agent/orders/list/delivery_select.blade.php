@foreach($deliveryList as $k=>$v)
    <div class="d_o_info_line">
        <div class="c_d_info">
            <input type="hidden" class="del_temp_id" value="{{$v['del_temp_id']}}">
            @component('component/radio',['radio'=>[$v['delivery_id']=>''],'name' => 'delivery_id','left_distance'=>5])
            @endcomponent
            {{--  <div class="c_d_radio">
                  <input type="radio" name="radio" id="radio1" class="radio">
                  <label for="radio1" style="margin-left: 5px" ></label>
              </div>--}}
            {{-- <div class="c_d_img">
                 <img  src="/images/yuantong.jpg" alt="">
             </div>--}}
            <div class="c_d_name">
                {{$v['delivery_name']}}
            </div>
            <div class="c_d_describe">
               {{$v['delivery_desc']}}
            </div>
            <div class="c_d_price">
                <input type="hidden" class="deli_price" value="{{$v['deli_price']}}">
                ￥ {{$v['deli_price']}}
            </div>
        </div>

    </div>
@endforeach