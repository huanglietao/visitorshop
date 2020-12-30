<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>true])
    @slot('slot_main')
        <div class="search-item row" style="" >
            <div class="col-lg-5 form-group">
                <div class="row" >
                    <div class="col-xl-4 col-md-4" style="text-align: right" >
                        <label class="control-label" style="font-weight: normal">素材类型:</label>
                    </div>
                    <div class="col-xl-8 col-md-8">
                        <select name="material_cate_flag" style="padding:4px 8px;color:rgb(106, 105, 105);font-size:12px;" class="form-control">
                            @foreach ($materCateType as $k=>$v)
                                <option value={{$k}}>{{$v}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>
            <div class="col-lg-5 form-group">
                <div class="row" >
                    <div class="col-xl-4 col-md-4" style="text-align: right" >
                        <label class="control-label" style="font-weight: normal">所属分类:</label>
                    </div>
                    <div class="col-xl-8 col-md-8">
                        <select name="material_cateid" style="padding:4px 8px;color:rgb(106, 105, 105);font-size:12px;" class="form-control">
                            @foreach ($materCate as $k=>$v)
                                <option value={{$k}}>{!! $v !!}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

        </div>
    @endslot

    @slot('slot_hide')
        <div class="search-item row" >
            <div class="col-lg-8 form-group">
                <div class="row">
                    <div class="col-xl-2 col-md-3" style="text-align: right">
                        <label class="control-label" style="font-weight: normal">创建时间:</label>
                    </div>
                    <div class="col-xl-9 col-md-9" style="padding-left: 0">
                        @component('component/rangedatapicker',['name'=>'created_at'])
                        @endcomponent
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" value="{{$materType}}" name="material_type" class="mtype_f">
    @endslot
@endcomponent
