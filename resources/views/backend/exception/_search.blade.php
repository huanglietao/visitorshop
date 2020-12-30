<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>true])
    @slot('slot_main')
        <div class="search-item row" style="" >
            <div class="col-lg-4 form-group" style="text-align: left">
                @component('component/search/label_input',['label'=>'异常码','inp_name'=>'exception'])
                @endcomponent
            </div>
        </div>
    @endslot

    @slot('slot_hide')
            <div class="search-item row" >

            </div>

    @endslot
@endcomponent
