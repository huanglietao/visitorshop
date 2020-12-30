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
                        <label class="control-label" style="font-weight: normal">创建时间:</label>
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
                            <label class="control-label" style="font-weight: normal;padding-top: 4px;">商品货号:</label>
                        </div>
                        <div class="col-xl-8 col-md-9">
                            <input type="text" class="form-control" name="sku_sn">
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 form-group">
                    <div class="row">
                        <div class="col-xl-4 col-md-3" style="text-align: right">
                            <label class="control-label" style="font-weight: normal;padding-top: 4px;">收货人:</label>
                        </div>
                        <div class="col-xl-8 col-md-9">
                            <input type="text" class="form-control" name="order_rcv_user">
                        </div>
                    </div>
                </div>
                {{--<div class="col-lg-3 form-group">--}}
                    {{--<div class="row">--}}
                        {{--<div class="col-xl-4 col-md-4" style="text-align: right">--}}
                            {{--<label class="control-label" style="font-weight: normal;padding-top: 4px;">订单状态:</label>--}}
                        {{--</div>--}}
                        {{--<div class="col-xl-8 col-md-8">--}}
                            {{--<select class="order_status_search">--}}
                                {{--<option value="">全部</option>--}}
                                {{--<option value="">未支付，未确认，未发货</option>--}}
                                {{--<option value="">已支付，未确认，未发货</option>--}}
                                {{--<option value="">已支付，已确认，未发货</option>--}}
                                {{--<option value="">已支付，已确认，已发货</option>--}}
                                {{--<option value="">交易成功</option>--}}
                                {{--<option value="">交易关闭</option>--}}
                                {{--<option value="">退款中</option>--}}
                                {{--<option value="">已退款</option>--}}
                            {{--</select>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                <div class="col-lg-3 form-group">
                    <div class="row">
                        <div class="col-xl-4 col-md-4" style="text-align: right">
                            <label class="control-label" style="font-weight: normal;padding-top: 4px;">评价状态:</label>
                        </div>
                        <div class="col-xl-8 col-md-8">
                            <select class="order_status_search">
                                <option value="">全部</option>
                                {{--<option value="">需要我评价</option>--}}
                                {{--<option value="">我已评价</option>--}}
                                {{--<option value="">对方已评</option>--}}
                                {{--<option value="">双方已评</option>--}}
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 form-group">
                    <div class="row">
                        <div class="col-xl-4 col-md-4" style="text-align: right">
                            <label class="control-label" style="font-weight: normal;padding-top: 4px;">销售渠道:</label>
                        </div>
                        <div class="col-xl-8 col-md-8">
                            <select class="order_status_search" name="chanel">
                                <option value="all">全部</option>
                                @foreach($chanelList as $k=>$v)
                                    <option value={{$v['cha_id']}}>{{$v['cha_name']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div style="display: flex;margin-top:5px" class="more search-line">
            <div class="search-item row" style="width: 80%;" >
                <div class="col-lg-3 form-group">
                    <div class="row">
                        <div class="col-xl-4 col-md-3" style="text-align: right">
                            <label class="control-label" style="font-weight: normal;padding-top: 4px;">物流单号:</label>
                        </div>
                        <div class="col-xl-8 col-md-9">
                            <input type="text" class="form-control" name="delivery_code">
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 form-group">
                    <div class="row">
                        <div class="col-xl-4 col-md-3" style="text-align: right">
                            <label class="control-label" style="font-weight: normal;padding-top: 4px;">交易关联单号:</label>
                        </div>
                        <div class="col-xl-8 col-md-9">
                            <input type="text" class="form-control" name="order_relation_no">
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 form-group">
                    <div class="row">
                        <div class="col-xl-4 col-md-3" style="text-align: right">
                            <label class="control-label" style="font-weight: normal;padding-top: 4px;">收货人手机号:</label>
                        </div>
                        <div class="col-xl-8 col-md-9">
                            <input type="text" class="form-control" name="order_rcv_phone">
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 form-group">
                    <div class="row">
                        <div class="col-xl-4 col-md-3" style="text-align: right">
                            <label class="control-label" style="font-weight: normal;padding-top: 4px;">店铺来源:</label>
                        </div>
                        <div class="col-xl-8 col-md-9">
                            <select class="order_status_search" name="user_id">
                                <option value={{PUBLIC_NO}}>全部</option>
                                @foreach($shopList as $k=>$v)
                                    <option value={{$k}}>{{$v}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

            </div>
        </div>


    @endslot
@endcomponent
