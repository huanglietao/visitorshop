<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>true])
    @slot('slot_main')
        <div class="search-item row" style="" >
            <div class="col-lg-4 form-group first_o_group">
                <div class="row" >
                    <div class="col-xl-4 col-md-4" style="text-align: right" >
                        <label class="control-label" style="font-weight: normal">物流公司:</label>
                    </div>
                    <div class="col-xl-8 col-md-8">
                        <select  class="form-select form-control" name="order_delivery_id" style="padding: 4px 8px" >
                            <option value="">请选择</option>
                            @foreach($deliData as $k=>$v)
                                <option value="{{$k}}" @if($k==$str) selected @endif>{{$v}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 form-group row search-row">
                <div class="col-xl-2 col-md-4 duration-search-label" style="text-align: right">
                    <label class="control-label" style="font-weight: normal;padding-top: 0px!important;">
                        <select class="search-time" name="prod_time">
                            <option value="1">发货时间</option>
                            <option value="2">下单时间</option>
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
        <div class="search-item row" >
            <div class="col-lg-4 form-group">
                <div class="row" >
                    <div class="col-xl-4 col-md-4" style="text-align: right" >
                        <label class="control-label" style="font-weight: normal">订单状态:</label>
                    </div>
                    <input hidden name="order_status" type="text"/>
                    <div class="col-xl-8 col-md-8 check" style="white-space: nowrap;margin-top: 3px">
                        @component('component/checkbox',['checkbox'=>['1'=>'待确认','2'=>'待付款','3'=>'待生产','4'=>'待发货','5'=>'待收货','6'=>'交易取消','7'=>'售后','10'=>'已收货'],'name'=>['o_status','o_status','o_status','o_status','o_status','o_status','o_status','o_status'],'right_distance'=>10])
                        @endcomponent
                    </div>
                </div>
            </div>
        </div>

        </div>
        <div style="display: flex;margin-top:5px" class="more search-line">
            <div class="search-item row" >
                <div class="col-lg-4 form-group">
                    <div class="row" >
                        <div class="col-xl-4 col-md-4" style="text-align: right" >
                            <label class="control-label" style="font-weight: normal">物流状态:</label>
                        </div>
                        <input hidden name="deli_status" type="text"/>
                        <div class="col-xl-8 col-md-8 check" style="white-space: nowrap;margin-top: 3px">
                            @component('component/checkbox',['checkbox'=>['0'=>'未发货','1'=>'已发货','2'=>'已收货','3'=>'备货中'],'name'=>['l_status','l_status','l_status','l_status'],'right_distance'=>10])
                            @endcomponent
                        </div>
                    </div>
                </div>
            </div>

    @endslot
@endcomponent