<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>true])
    @slot('slot_main')
        <div class="search-item row" style="" >
                <div class="col-lg-4 form-group" >
                    @component('component/search/label_input',['label'=>'区域名称', 'inp_name' => 'area_name', 'inp_id' => 'area_name'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'区域等级', 'inp_name' => 'level', 'inp_id' => 'level'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group" style="display: none">
                    @component('component/search/label_input',['label'=>'所属上级', 'inp_name' => 'pid', 'inp_id' => 'pid'])
                    @endcomponent
                </div>
        </div>
    @endslot

    @slot('slot_hide')
            <div class="search-item row" >
                <div class="col-lg-4 form-group" >
                    @component('component/search/label_input',['label'=>'区域编码', 'inp_name' => 'area_code', 'inp_id' => 'area_code'])
                    @endcomponent
                </div>
            </div>


    @endslot
@endcomponent
