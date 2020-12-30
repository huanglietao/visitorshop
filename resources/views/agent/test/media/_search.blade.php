<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>true])
    @slot('slot_main')
        <div class="search-item row" style="" >
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'商品id', 'inp_name' => 'prod_id', 'inp_id' => 'prod_id'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'存储路径', 'inp_name' => 'prod_md_path', 'inp_id' => 'prod_md_path'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'是否为主图', 'inp_name' => 'prod_md_ismain', 'inp_id' => 'prod_md_ismain'])
                    @endcomponent
                </div>

        </div>
    @endslot

    @slot('slot_hide')
            <div class="search-item row" >
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'类型', 'inp_name' => 'prod_md_type', 'inp_id' => 'prod_md_type'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'排序', 'inp_name' => 'sort', 'inp_id' => 'sort'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'创建时间', 'inp_name' => 'created_at', 'inp_id' => 'created_at'])
                    @endcomponent
                </div>

            </div>
            <div class="search-item row" >
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'更新时间', 'inp_name' => 'updated_at', 'inp_id' => 'updated_at'])
                    @endcomponent
                </div>

            </div>

    @endslot
@endcomponent
