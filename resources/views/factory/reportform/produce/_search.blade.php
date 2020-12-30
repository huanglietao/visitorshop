<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>false])
@slot('slot_main')
    <div class="search-item row" style="" >
        <div class="col-lg-4 form-group .first_o_group">
            @component('component/search/label_input',['label'=>'订单号','inp_name'=>'order_no'])
            @endcomponent
        </div>
        <div class="col-lg-8 form-group row search-row">

                <div class="col-xl-2 col-md-4 duration-search-label" style="text-align: right">
                    <label class="control-label" style="font-weight: normal;padding-top: 0px!important;">
                        交易时间
                    </label>
            </div>
            <div class="col-xl-9 col-md-8 form-group duration-search ">
                @component('component/rangedatapicker',['name'=>'send_time'])

                @endcomponent
            </div>
        </div>

    </div>
@endslot
@slot('slot_hide')
    <div class="search-item row">

    </div>
@endslot

@endcomponent
