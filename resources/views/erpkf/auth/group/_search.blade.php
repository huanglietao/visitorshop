<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>true])
    @slot('slot_main')
        <div class="search-item row" style="" >
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'父组别', 'inp_name' => 'pid', 'inp_id' => 'pid'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'组名', 'inp_name' => 'name', 'inp_id' => 'name'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'规则ID', 'inp_name' => 'rules', 'inp_id' => 'rules'])
                    @endcomponent
                </div>

        </div>
    @endslot

    @slot('slot_hide')
            <div class="search-item row" >
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'状态', 'inp_name' => 'status', 'inp_id' => 'status'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'状态', 'inp_name' => 'deleted_at', 'inp_id' => 'deleted_at'])
                    @endcomponent
                </div>

            </div>

    @endslot
@endcomponent
