@component('component/search/search_full',['has_more'=>false])
    @slot('slot_main')
        <div class="search-item row" style="" >
            <div class="col-lg-4 form-group" style="white-space: nowrap">
                @component('component/search/label_input',['label'=>'操作员账号'])
                @endcomponent
            </div>
            <div class="col-lg-8 form-group row search-row">

                <div class="col-xl-2 col-md-4 duration-search-label" style="text-align: right">
                    <label class="control-label" style="font-weight: normal;">
                        创建时间:
                    </label>
                </div>
                <div class="col-xl-9 col-md-8 form-group duration-search ">
                    @component('component/rangedatapicker')

                    @endcomponent
                </div>
            </div>

        </div>
    @endslot

@endcomponent
