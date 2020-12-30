<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>false])
    @slot('slot_main')
        <div class="search-item row" style="" >
            <div class="col-lg-4 form-group">
                @component('component/search/label_input',['label'=>'等级名称', 'inp_name' => 'cust_lv_name', 'inp_id' => 'cust_lv_name'])
                @endcomponent
            </div>
            <div class="col-lg-4 form-group">
                @component('component/search/label_input',['label'=>'等级折扣', 'inp_name' => 'cust_lv_discount', 'inp_id' => 'cust_lv_discount'])
                @endcomponent
            </div>
        </div>
    @endslot
@endcomponent
