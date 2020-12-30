<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>true])
@slot('slot_main')
<div class="search-item row" style="" >
    <div class="col-lg-4 form-group  first_o_group" style="white-space: nowrap">
        @component('component/search/label_input',['label'=>'售后单号', 'inp_name' => 'service_order_no'])
        @endcomponent
    </div>
    <div class="col-lg-8 form-group row search-row">

        <div class="col-xl-2 col-md-4 duration-search-label" style="text-align: right">
            <label class="control-label" style="font-weight: normal;padding-top: 0px!important;">
                <select class="search-time" name="time">
                    <option value="apply">申请时间</option>
                    <option value="handle">处理时间</option>
                </select>
            </label>
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
        @component('component/search/label_input',['label'=>'订单编号', 'inp_name' => 'order_no'])
        @endcomponent
    </div>
    <div class="col-lg-4 form-group">
        <div class="row">
            <div class="col-xl-4 col-md-4" style="text-align: right">
                <label class="control-label" style="font-weight: normal">状态:</label>
            </div>
            <div class="col-xl-8 col-md-8">
                <select class="order_status_search" name="status">
                    <option value="">全部</option>
                    @foreach($jobStatusList as $k=>$v)
                        <option value="{{$k}}">{{$v}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>



</div>


    @endslot
    @endcomponent

