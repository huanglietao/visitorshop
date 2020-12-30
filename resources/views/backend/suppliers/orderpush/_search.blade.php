<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>false])
    @slot('slot_main')
        <div class="search-item row" style="" >
            {{--<div class="col-lg-4 form-group">--}}
                {{--@component('component/search/label_input',['label'=>'订单id', 'inp_name' => 'order_id', 'inp_id' => 'order_id'])--}}
                {{--@endcomponent--}}
            {{--</div>--}}
            <div class="col-lg-4 form-group">
                @component('component/search/label_input',['label'=>'订单推送状态', 'inp_name' => 'order_push_status', 'inp_id' => 'order_push_status'])
                @endcomponent
            </div>
            <div class="col-lg-8 form-group">
                <div class="row">
                    <div class="col-xl-2 col-md-3" style="text-align: right">
                        <label class="control-label" style="font-weight: normal">时间范围:</label>
                    </div>
                    <div class="col-xl-9 col-md-9">
                        @component('component/rangedatapicker',['name'=>'order_time'])
                        @endcomponent
                    </div>
                </div>
            </div>

        </div>
    @endslot

@endcomponent
