<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>false])
    @slot('slot_main')
        <div class="search-item row" style="" >
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'商户账号', 'inp_name' => 'oms_adm_username', 'inp_id' => 'oms_adm_username'])
                    @endcomponent
                </div>
        </div>

    @endslot
@endcomponent
