<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>true])
    @slot('slot_main')
        <div class="search-item row" style="" >
            <div class="col-lg-4 form-group">
                @component('component/search/label_input',['label'=>'模板名称', 'inp_name' => 'inner_temp_name', 'inp_id' => 'inner_temp_name'])
                @endcomponent
            </div>
            <div class="col-lg-4 form-group">
                <div class="row" >
                    <div class="col-xl-4 col-md-4" style="text-align: right" >
                        <label class="control-label" style="font-weight: normal">模板分类:</label>
                    </div>
                    <div class="col-xl-8 col-md-8">
                        <select name="inner_temp_theme_id" style="padding:4px 8px;color:rgb(106, 105, 105);font-size:12px;" class="form-control">
                            @foreach ($tempThemeList as $k=>$v)
                                <option value={{$k}}>{{$v}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 form-group">
                <div class="row" >
                    <div class="col-xl-4 col-md-4" style="text-align: right" >
                        <label class="control-label" style="font-weight: normal">所属规格:</label>
                    </div>
                    <div class="col-xl-8 col-md-8">
                        <select name="specifications_id" style="padding:4px 8px;color:rgb(106, 105, 105);font-size:12px;" class="form-control">
                            @foreach ($specList as $k=>$v)
                                <option value={{$k}}>{{$v}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

        </div>
    @endslot

    @slot('slot_hide')
            <div class="search-item row" >
                <div class="col-lg-4 form-group">
                    <div class="row" >
                        <div class="col-xl-4 col-md-4" style="text-align: right" >
                            <label class="control-label" style="font-weight: normal">审核状态:</label>
                        </div>
                        <div class="col-xl-8 col-md-8">
                            <select name="inner_temp_check_status" style="padding:4px 8px;color:rgb(106, 105, 105);font-size:12px;" class="form-control">
                                @foreach ($checkStatus as $k=>$v)
                                    <option value={{$k}}>{{$v}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

            </div>

    @endslot
@endcomponent
