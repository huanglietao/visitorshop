<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>false])
    @slot('slot_main')
        <div class="search-item row" style="" >

                <div class="col-lg-6 form-group">
                    @component('component/search/label_input',['label'=>'角色名称', 'inp_name' => 'cms_group_name', 'inp_id' => 'cms_group_name'])
                    @endcomponent
                </div>

        </div>
    @endslot


@endcomponent
