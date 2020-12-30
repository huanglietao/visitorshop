<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>true])
    @slot('slot_main')
        <div class="search-item row" style="" >
            <div class="col-lg-4 form-group" style="text-align: left">
               @component('component/search/label_input',['label'=>'名称名称'])
               @endcomponent
            </div>
            <div class="col-lg-4 form-group">
                @component('component/search/label_input',['label'=>'名称'])
                @endcomponent
            </div>

            <div class="col-lg-4 form-group">
                @component('component/search/label_input',['label'=>'名称'])
                @endcomponent
            </div>
        </div>
    @endslot

    @slot('slot_hide')
        <div class="search-item row" >
            <div class="col-lg-4 form-group">
                @component('component/search/label_input',['label'=>'名称'])
                @endcomponent
            </div>
            <div class="col-lg-4 form-group">
                @component('component/search/label_input',['label'=>'名称'])
                @endcomponent
            </div>
            <div class="col-lg-4 form-group">
                @component('component/search/label_input',['label'=>'名称'])
                @endcomponent
            </div>




        </div>
        </div>
        <div style="display: flex;margin-top:5px" class="more search-line">
            <div class="search-item row"  >
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'名称'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'名称'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'名称'])
                    @endcomponent

                </div>


            </div>
    @endslot
@endcomponent