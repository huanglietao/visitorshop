<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>true])
    @slot('slot_main')
        <div class="search-item row" style="" >
            <div class="col-lg-4 form-group">
                @component('component/search/label_input',['label'=>'系统单号', 'inp_name' => 'order_id', 'inp_id' => 'order_id'])
                @endcomponent
            </div>
            <div class="col-lg-8 form-group">
                <div class="row">
                    <div class="col-xl-2 col-md-3" style="text-align: right">
                        <label class="control-label" style="font-weight: normal">回写时间:</label>
                    </div>
                    <div class="col-xl-9 col-md-9" style="padding-left: 0">
                        @component('component/rangedatapicker',['name'=>'back_time'])
                        @endcomponent
                    </div>
                </div>
            </div>

        </div>
    @endslot

    @slot('slot_hide')
        <div class="search-item row" >
            <div class="col-lg-4 form-group">
                <div class="row" >
                    <div class="col-xl-4 col-md-4" style="text-align: right" >
                        <label class="control-label" style="font-weight: normal">队列状态:</label>
                    </div>
                    <div class="col-xl-8 col-md-8">
                        <select name="delivery_push_status" style="padding:4px 8px;color:rgb(106, 105, 105);font-size:12px;" class="form-control">
                            @foreach ($queueStatusList as $k=>$v)
                                <option value={{$k}}>{!! $v !!}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

    @endslot
@endcomponent
