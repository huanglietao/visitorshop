<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>false])
    @slot('slot_main')
        <div class="search-item row" style="" >
            <div class="col-lg-4 form-group">
                @component('component/search/label_input',['label'=>'售后原因', 'inp_name' => 'reason', 'inp_id' => 'reason'])
                @endcomponent
            </div>
            <div class="col-lg-8 form-group">
                <div class="row">
                    <div class="col-xl-2 col-md-2" style="text-align: right">
                        <label class="control-label" style="font-weight: normal">创建时间:</label>
                    </div>
                    <div class="col-xl-8 col-md-8">
                        @component('component/rangedatapicker',['name'=>'created_at'])

                        @endcomponent
                    </div>
                </div>
            </div>
        </div>

    @endslot
@endcomponent


