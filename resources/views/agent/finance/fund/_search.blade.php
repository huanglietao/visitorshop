<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>true])
@slot('slot_main')
    <div class="search-item row" style="" >
        <div class="col-lg-4 form-group first_o_group" style="white-space: nowrap">
            @component('component/search/label_input',['label'=>'业务订单号', 'inp_name' => 'cus_balance_business_no'])
            @endcomponent
        </div>
        <div class="col-lg-8 form-group row search-row">

                <div class="col-xl-2 col-md-4 duration-search-label" style="text-align: right">
                    <label class="control-label" style="font-weight: normal">发生时间:</label>
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
                <div class="row" >
                    <div class="col-xl-4 col-md-4" style="text-align: right" >
                        <label class="control-label" style="font-weight: normal">变动类型:</label>
                    </div>
                    <div class="col-xl-8 col-md-8" style="white-space: nowrap;margin-top: 3px">
                        <select class="fund-search-select" name="cus_balance_type_detail">
                            <option value="">全部</option>
                            @foreach(config('finance.finance_fund_type') as $k=>$v)
                                <option value="{{$k}}">{{$v}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 form-group">
                <div class="row" >
                    <div class="col-xl-4 col-md-4" style="text-align: right" >
                        <label class="control-label" style="font-weight: normal">金额范围:</label>
                    </div>
                    <div class="col-xl-8 col-md-8" style="white-space: nowrap;margin-top: 3px;display: flex">

                            <input name="amount_min" id="" type="text" class="form-control" style="width: 40%">
                            <span>&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;</span>
                            <input type="text" name="amount_max" id="" class="form-control"  style="width: 40%">


                    </div>
                </div>
            </div>





        </div>

</div>
<div style="display: flex;margin-top:5px" class="more search-line">


@endslot
@endcomponent