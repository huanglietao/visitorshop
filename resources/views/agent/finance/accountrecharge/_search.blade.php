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
                        <select class="search-time" name="search_type">
                            <option value="1">创建时间</option>
                            <option value="2">到账时间</option>
                        </select>
                    </label>
                </div>
                <div class="col-xl-9 col-md-8 form-group duration-search ">
                    @component('component/rangedatapicker',['name'=>'search_time'])

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
                        <label class="control-label" style="font-weight: normal">支付类型:</label>
                    </div>
                    <div class="col-xl-8 col-md-8">
                        <select class="order_status_search" name="pay_type">
                            <option value="">请选择</option>
                            <option value="1">支付宝</option>
                            <option value="2">微信</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 form-group">
                <div class="row">
                    <div class="col-xl-4 col-md-4" style="text-align: right">
                        <label class="control-label" style="font-weight: normal">状态:</label>
                    </div>
                    <div class="col-xl-8 col-md-8">
                        <select class="order_status_search" name="capital_change_status">
                            <option value="">请选择</option>
                            <option value="0">未入账</option>
                            <option value="1">已入账</option>
                        </select>
                    </div>
                </div>
            </div>
            {{--<div class="col-lg-4 form-group">--}}
                {{--<div class="row">--}}
                    {{--<div class="col-xl-3 col-md-3" style="text-align: right">--}}
                        {{--<label class="control-label" style="font-weight: normal;white-space: nowrap">金额范围:</label>--}}
                    {{--</div>--}}
                    {{--<div class="col-xl-4 col-md-4" style="text-align: right">--}}
                        {{--<input type="text" class="form-control">--}}
                    {{--</div>--}}
                    {{--<div class="col-xl-1 col-md-1" style="text-align: center">--}}
                        {{-----}}
                    {{--</div>--}}
                    {{--<div class="col-xl-4 col-md-4" style="text-align: right">--}}
                        {{--<input type="text" class="form-control">--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        </div>


    @endslot
@endcomponent

