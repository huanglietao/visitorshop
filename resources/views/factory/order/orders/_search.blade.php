<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>true])
    @slot('slot_main')
        <div class="search-item row" style="" >
            <div class="col-lg-4 form-group">
                @component('component/search/label_input',['label'=>'订单编号', 'inp_name' => 'order_no', 'inp_id' => 'order_no'])
                @endcomponent
            </div>
            <div class="col-lg-8 form-group">
                <div class="row">
                    <div class="col-xl-2 col-md-2" style="text-align: right">
                        <label class="control-label" style="font-weight: normal">交易时间:</label>
                    </div>
                    <div class="col-xl-8 col-md-8">
                        @component('component/rangedatapicker',['name'=>'created_at'])

                        @endcomponent
                    </div>
                </div>
            </div>
        </div>
    @endslot

    @slot('slot_hide')
        <div style="display: flex;margin-top:5px" class="more search-line">
            <div class="search-item row" style="width: 80%;" >
                <div class="col-lg-3 form-group">
                    <div class="row">
                        <div class="col-xl-4 col-md-3" style="text-align: right">
                            <label class="control-label" style="font-weight: normal;padding-top: 4px;"> 订单项目号:</label>
                        </div>
                        <div class="col-xl-8 col-md-9">
                            <input type="text" class="form-control" name="ord_prj_no">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endslot

@endcomponent
