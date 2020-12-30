<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>true])
    @slot('slot_main')
        <div class="search-item row" style="" >
            <div class="col-lg-4 form-group">
                @component('component/search/label_input',['label'=>'供应商名称', 'inp_name' => 'sup_name', 'inp_id' => 'sup_name'])
                @endcomponent
            </div>
            <div class="col-lg-8 form-group">
                <div class="row">
                    <div class="col-xl-2 col-md-3" style="text-align: right">
                        <label class="control-label" style="font-weight: normal">创建时间:</label>
                    </div>
                    <div class="col-xl-9 col-md-9">
                        @component('component/rangedatapicker',['name'=>'created_at'])
                        @endcomponent
                    </div>
                </div>
            </div>
        </div>
    @endslot

    @slot('slot_hide')
            <div class="search-item row" >
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'供应商编号', 'inp_name' => 'sup_code', 'inp_id' => 'sup_code'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'联系人', 'inp_name' => 'sup_contacts', 'inp_id' => 'sup_contacts'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'供应商产能', 'inp_name' => 'sup_capacity', 'inp_id' => 'sup_capacity'])
                    @endcomponent
                </div>
            </div>
            <div class="search-item row" >
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'订单分配量', 'inp_name' => 'sup_allocation_quantity', 'inp_id' => 'sup_allocation_quantity'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'产能单位', 'inp_name' => 'sup_capacity_unit', 'inp_id' => 'sup_capacity_unit'])
                    @endcomponent
                </div>
            </div>

    @endslot
@endcomponent
