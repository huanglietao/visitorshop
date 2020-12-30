<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>true])
    @slot('slot_main')
        <div class="search-item row" style="" >
            <div class="col-lg-4 form-group">
                @component('component/search/label_input',['label'=>'名称', 'inp_name' => 'cou_name', 'inp_id' => 'cou_name'])
                @endcomponent
            </div>
            <div class="col-lg-8 form-group">
                <div class="row">
                    <div class="col-xl-2 col-md-3" style="text-align: right">
                        <label class="control-label" style="font-weight: normal">优惠券有效时间:</label>
                    </div>
                    <div class="col-xl-9 col-md-9">
                        @component('component/rangedatapicker',['name'=>'cou_time'])
                        @endcomponent
                    </div>
                </div>
            </div>
        </div>
    @endslot
    @slot('slot_hide')
        <div class="search-item row" style="" >
            <div class="col-lg-4 form-group">
                <div class="row" >
                    <div class="col-xl-4 col-md-4" style="text-align: right" >
                        <label class="control-label" style="font-weight: normal">类型:</label>
                    </div>
                    <div class="col-xl-8 col-md-8">
                        <select name="cou_type" style="padding:4px 8px;color:rgb(106, 105, 105);font-size:12px;" class="form-control" >
                            <option value="">请选择</option>
                            <option value="1">卡券</option>
                            <option value="2">优惠码</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 form-group">
                <div class="row" >
                    <div class="col-xl-4 col-md-4" style="text-align: right" >
                        <label class="control-label" style="font-weight: normal">所属子系统:</label>
                    </div>
                    <div class="col-xl-8 col-md-8">
                        <select name="sales_chanel_id" style="padding:4px 8px;color:rgb(106, 105, 105);font-size:12px;" class="form-control" >
                            @foreach($sales_chanel as $k=>$v)
                                <option value="{{$k}}">{{$v}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    @endslot
@endcomponent
