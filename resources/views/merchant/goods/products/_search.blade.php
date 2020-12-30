<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>true])
    @slot('slot_main')
        <div class="search-item row" style="" >
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'商品名称', 'inp_name' => 'prod_name', 'inp_id' => 'prod_name'])
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
                    @component('component/search/label_input',['label'=>'商品条形码', 'inp_name' => 'prod_code', 'inp_id' => 'prod_code'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'商品编码', 'inp_name' => 'prod_sn', 'inp_id' => 'prod_sn'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    <div class="row">
                        <div class="col-xl-4 col-md-4" style="text-align: right">
                            <label class="control-label" style="font-weight: normal">商品分类:</label>
                        </div>
                        <div class="col-xl-8 col-md-8" style="padding-left: 0">
                            <select style="padding:4px 8px;color:rgb(106, 105, 105);font-size:12px;" class="form-control"  name="prod_cate_uid">
                                <option value=" ">请选择</option>
                                @foreach($categoryList as $k=>$v)
                                    <option value={{$k}}>{{$v[0]}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

            </div>





    @endslot
@endcomponent
