<!-- 订单标签视图 -->
<div class="Checkes" style="margin-top:30px">
    <form class="form-horizontal" id="form-save" method="post" action="/order/list/tag/{{$order_id}}" autocomplete="off">
        @csrf
        <div class="form-group row form-item">
            <label style=" font-weight: normal;color: #6A6969;" for="c-mch_name" class="control-label col-xs-12 col-sm-2">订单标签：</label>
            <div class="col-lg-10 col-sm-10">
                <div class="row" style="line-height: 7px;padding-top: 13px;">
                    @if(!empty($tag_list[0]))
                        @foreach($tag_list[0] as $k=>$v)
                            @php if (in_array($k,isset($tag)?$tag:[])) {$checked = $k;}else{$checked = 0;}  @endphp
                            @component('component/checkbox',['checkbox'=>[$k=>$v],'name'=>[$tag_list[1][$loop->index]],'checked'=>$checked])@endcomponent
                        @endforeach
                    @endif
                    {{--@component('component/checkbox',['checkbox'=>$tag_list[0],'name'=>$tag_list[1],'checked'=>[1,2]])--}}
                    {{--@endcomponent--}}
                </div>
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
