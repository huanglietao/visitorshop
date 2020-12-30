<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>false])
    @slot('slot_main')
        <div class="search-item row" style="" >
            <div class="col-lg-4 form-group">
                @component('component/search/label_input',['label'=>'版式名称', 'inp_name' => 'temp_layout_type_name', 'inp_id' => 'temp_layout_type_name'])
                @endcomponent
            </div>
            <div class="col-lg-4 form-group">
                @component('component/search/label_input',['label'=>'创建时间', 'inp_name' => 'created_at', 'inp_id' => 'created_at'])
                @endcomponent
            </div>
        </div>
    @endslot

    @slot('slot_hide')


    @endslot
@endcomponent
