@foreach($attrList as $k => $v)
        <div class="row product-sku-row  @if($v['attr_flag'] == PAGE_FLAG) is-page-class @endif">
        <div class="col-sm-1 pgc-attr-font attr-pid" data-id="{{$v['attr_id']}}" >
            <span class="attr_p_value">{{$v['attr_name']}}</span>
        </div>
        <div class="col-sm-11 attr-child">
            {{--每行放7个checkbox，超过三行则自动隐藏--}}
            @php $start = 1;  @endphp
            @foreach($v[$v['attr_id']] as $kk=>$vv)

                <div class="prod-sku-check prod-sku-check-{{$v['attr_id']}}  @if($start > 21) no-show-check @else show-check  @endif " >
                        @component('component/checkbox',['checkbox'=>[$vv['attr_val_id'] => $vv['attr_val_name']],'name'=>['attr_value'],'custom_class'=>'attr-value','right_distance' => '30'])
                        @endcomponent
                </div>
                @php $start++; @endphp
            @endforeach
            @if($start > 21)
                <div class="more-show" data-action="show"><span class="show-text">更多</span><i class="fa fa-angle-double-down"></i></div>
            @endif

        </div>
    </div>
    @endforeach
<div class="row product-sku-row ">
    <button class="btn btn-blue btn-attr-add" style="color: #ffffff;vertical-align: top;margin-left: 10px">生成货品</button>
</div>


