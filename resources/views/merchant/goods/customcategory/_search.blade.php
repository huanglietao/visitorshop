<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>true])
    @slot('slot_main')
<div class="search-item row" style="" >
    <div class="col-lg-4 form-group">
        @component('component/search/label_input',['label'=>'分类名称', 'inp_name' => 'cate_name', 'inp_id' => 'cate_name'])
        @endcomponent
    </div>

    <div class="col-lg-8 form-group row search-row">

        <div class="col-xl-2 col-md-4 duration-search-label" style="text-align: right">
            <label class="control-label" style="font-weight: normal;padding-top: 0px!important;">
                <select class="search-time" style="padding: 4px 8px;color: #6A6969;height: 26px;background: #ffffff">
                    <option value="">创建时间</option>
                </select>
            </label>
        </div>
        <div class="col-xl-9 col-md-8 form-group duration-search ">
            @component('component/rangedatapicker',['name'=>'created_at'])

            @endcomponent
        </div>
    </div>

</div>
    @endslot

    @slot('slot_hide')
            <div class="search-item row" >

            </div>

    @endslot
@endcomponent
