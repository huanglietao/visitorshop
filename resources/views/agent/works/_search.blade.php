<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>true])
    @slot('slot_main')
        <div class="search-item row" style="">
            <div class="col-lg-4 form-group">
                @component('component/search/label_input',['label'=>'作品编号', 'inp_name' => 'prj_sn', 'inp_id' => 'prj_sn'])
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
        <div class="search-item row" style="" >
            <div class="col-lg-4 form-group">
                @component('component/search/label_input',['label'=>'作品名称', 'inp_name' => 'prj_name', 'inp_id' => 'prj_name'])
                @endcomponent
            </div>
            <div class="col-lg-4 form-group">
                @component('component/search/label_input',['label'=>'作者名称', 'inp_name' => 'prj_outer_account', 'inp_id' => 'prj_outer_account'])
                @endcomponent
            </div>
            <div class="col-lg-4 form-group">
                @component('component/search/label_input',['label'=>'手机号', 'inp_name' => 'prj_rcv_phone', 'inp_id' => 'prj_rcv_phone'])
                @endcomponent
            </div>
        </div>
        <div class="search-item row" style="" >
            <div class="col-lg-4 form-group">
                @component('component/search/label_input',['label'=>'商品货号', 'inp_name' => 'prod_sku_sn', 'inp_id' => 'prod_sku_sn'])
                @endcomponent
            </div>
            <div class="col-lg-4 form-group">
                <div class="row">
                    <div class="col-xl-4 col-md-4" style="text-align: right">
                        <label class="control-label" style="font-weight: normal">作品标签:</label>
                    </div>
                    <div class="col-xl-8 col-md-8">
                        <select  style="padding:4px 8px;color:rgb(106, 105, 105);font-size:12px;" class="form-control"  name="prj_label">
                            @foreach($prjLabel as $key=>$val)
                                <option value="{{$key}}">{{$val}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 form-group">
                @component('component/search/label_input',['label'=>'关联单号', 'inp_name' => 'order_no', 'inp_id' => 'order_no'])
                @endcomponent
            </div>
        </div>
    @endslot
@endcomponent
