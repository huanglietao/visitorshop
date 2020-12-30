<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>true])
    @slot('slot_main')
        <div class="search-item row" style="" >
            <div class="col-lg-4 form-group">
                <div class="row" >
                    <div class="col-xl-4 col-md-4" style="text-align: right" >
                        <label class="control-label" style="font-weight: normal">交易类型:</label>
                    </div>
                    <div class="col-xl-8 col-md-8">
                        <select name="money_type" style="padding:4px 8px;color:rgb(106, 105, 105);font-size:12px;" class="form-control">
                            <option value=" ">请选择</option>
                            <option value="1">消费</option>
                            <option value="2">充值</option>
                        </select>
                    </div>
                </div>
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
                @component('component/search/label_input',['label'=>'交易流水号', 'inp_name' => 'recharge_no', 'inp_id' => 'recharge_no'])
                @endcomponent
            </div>
            <div class="col-lg-4 form-group">
                @component('component/search/label_input',['label'=>'第三方交易流水号', 'inp_name' => 'trade_no', 'inp_id' => 'trade_no'])
                @endcomponent
            </div>
        </div>
    @endslot
@endcomponent
