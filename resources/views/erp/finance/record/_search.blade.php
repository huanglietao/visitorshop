<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>true])
    @slot('slot_main')
        <div class="search-item row" style="" >
                <div class="col-lg-4 form-group" style="white-space: nowrap">
                    @component('component/search/label_input',['label'=>'充值订单号','inp_name'=>'recharge_no'])
                    @endcomponent
                </div>
            <div class="col-lg-8 form-group row search-row">

                <div class="col-xl-2 col-md-4 duration-search-label" style="text-align: right">
                    <label class="control-label" style="font-weight: normal;padding-top: 0px!important;">
                        <select class="search-time" style="padding: 4px 8px;color: #6A6969;height: 26px;background: #ffffff">
                            <option value="">充值时间</option>
                        </select>
                    </label>
                </div>
                <div class="col-xl-9 col-md-8 form-group duration-search ">
                    @component('component/rangedatapicker',['name'=>'createtime'])

                    @endcomponent
                </div>
            </div>




        </div>
    @endslot


    @slot('slot_hide')
        <div class="search-item row">
            <div class="col-lg-4 form-group">
                <div class="row" >
                    <div class="col-xl-4 col-md-4" style="text-align: right" >
                        <label class="control-label" style="font-weight: normal">充值类型:</label>
                    </div>
                    <div class="col-xl-8 col-md-8" style="white-space: nowrap;margin-top: 3px">
                        @component('component/checkbox',['checkbox'=>['支付宝'],'name'=>['pay_type'],'right_distance'=>10])
                        @endcomponent
                    </div>
                </div>
            </div>
            <div class="col-lg-4 form-group">
                <div class="row">
                    <div class="col-xl-4 col-md-4" style="text-align: right">
                        <label class="control-label" style="font-weight: normal">状态:</label>
                    </div>
                    <div class="col-xl-8 col-md-8">
                        <select name="capital_change_status" class="order_status_search" style="padding:4px 8px;color: #6A6969;background: #ffffff;width: 98%;height: 26px;">
                            <option value="2">全部</option>
                            <option value="1">成功</option>
                            <option value="0">失败</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 form-group">
                <div class="row">
                    <div class="col-xl-3 col-md-3" style="text-align: right">
                        <label class="control-label" style="font-weight: normal;white-space: nowrap">金额范围:</label>
                    </div>
                    <div class="col-xl-4 col-md-4" style="text-align: right">
                        <input type="text" class="form-control" name="amount_min">
                    </div>
                    <div class="col-xl-1 col-md-1" style="text-align: center">
                        -
                    </div>
                    <div class="col-xl-4 col-md-4" style="text-align: right">
                        <input type="text" class="form-control" name="amount_max">
                    </div>
                </div>
            </div>
            <div class="col-lg-4 form-group" style="white-space: nowrap">
                @component('component/search/label_input',['label'=>'支付流水号','inp_name'=>'trade_no'])
                @endcomponent
            </div>
        </div>


    @endslot
@endcomponent

