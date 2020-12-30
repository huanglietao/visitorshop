<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>true])
    @slot('slot_main')
        <div class="search-item row" style="" >
            <div class="col-lg-4 form-group" style="text-align: left">
                @component('component/search/label_input',['label'=>'所属系统','inp_name'=>'sys'])
                @endcomponent
            </div>
            <div class="col-lg-4 form-group">
                @component('component/search/label_input',['label'=>'所属模块','inp_name'=>'modules'])
                @endcomponent
            </div>

            <div class="col-lg-4 form-group">
                @component('component/search/label_input',['label'=>'错误编码','inp_name'=>'code'])
                @endcomponent
            </div>
        </div>
    @endslot

    @slot('slot_hide')
            <div class="search-item row" >

            </div>

    @endslot
@endcomponent
