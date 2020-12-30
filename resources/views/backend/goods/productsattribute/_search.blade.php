<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>true])
    @slot('slot_main')
        <div class="search-item row" style="" >
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'属性名称', 'inp_name' => 'attr_name', 'inp_id' => 'attr_name'])
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
                <div class="col-lg-4 form-group">

                    <div class="row" >
                        <div class="col-xl-4 col-md-4" style="text-align: right" >
                            <label class="control-label" style="font-weight: normal">所属分类:</label>
                        </div>
                        <div class="col-xl-8 col-md-8">
                            <select name="cate_id" style="padding:4px 8px;color:rgb(106, 105, 105);font-size:12px;" class="form-control">
                                <option value="">无</option>
                                @foreach ($categoryList as $k=>$v)
                                    <option value={{$v['cate_id']}}>{{$v['cate_name']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

            </div>

    @endslot
@endcomponent
