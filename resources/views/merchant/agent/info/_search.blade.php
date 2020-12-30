<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>true])
    @slot('slot_main')
        <div class="search-item row" style="" >
            <div class="col-lg-4 form-group">
                @component('component/search/label_input',['label'=>'店铺名称', 'inp_name' => 'agent_name', 'inp_id' => 'agent_name'])
                @endcomponent
            </div>
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
    @endslot

    @slot('slot_hide')
        <div class="search-item row">
            <div class="col-lg-4 form-group">
                <div class="row">
                    <div class="col-xl-4 col-md-4" style="text-align: right">
                        <label class="control-label" style="font-weight: normal">店铺类型:</label>
                    </div>
                    <div class="col-xl-8 col-md-8" style="padding-left: 0">
                        <select  style="padding:4px 8px;color:rgb(106, 105, 105);font-size:12px;" class="form-control"  name="agent_type">
                            @foreach($shop_type as $key=>$value)
                                <option value="{{$key}}">{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 form-group">
                @component('component/search/label_input',['label'=>'商家账号', 'inp_name' => 'mch_name', 'inp_id' => 'mch_name'])
                @endcomponent
            </div>
        </div>

    @endslot
@endcomponent
