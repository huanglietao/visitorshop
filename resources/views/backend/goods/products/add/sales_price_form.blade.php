{{--填写渠道定价 start--}}
<div class="main-channel">
    <!--  提示组件 start -->
    @component('component/tips')
    <p>商品渠道定价是针对单货品在多种渠道，不同组别下销售价格不同而进行的设置</p>
    <p>设置价格后对应渠道将按下列价格政策，属性页价格则不启用。</p>
    <p>不同渠道不同组别分别有二种定价方式：一种是渠道组别一口价，二种是渠道组别阶梯价。</p>
    @endcomponent
<!--  提示组件 end -->
    <input type="hidden" class="lay_index" value="{{$list['layer_index']}}">
    <div class="form-group row form-item">
        <label style=" font-weight: normal" class="control-label col-xs-12 col-sm-2 pgc-font">
            商品名称：</label>
        <div class="col-xs-12 col-sm-10">
            <div class="row">
                <span class="col-sm-5 control-label pi-cate-name" style="text-align: left!important;"> {{$list['prod_name']}}</span>
            </div>
            <span class="msg-box" style="position:static;" for="prod_name"></span>
        </div>
    </div>
    <div class="form-group row form-item">
        <label style=" font-weight: normal" class="control-label col-xs-12 col-sm-2 pgc-font">
            商品货号：</label>
        <div class="col-xs-12 col-sm-10">
            <div class="row">
                <span class="col-sm-5 control-label pi-cate-name" style="text-align: left!important;"> {{$list['prod_sn']}}</span>
            </div>
            <span class="msg-box" style="position:static;" for="prod_name"></span>
        </div>
    </div>
    <input type="hidden" name="set_is_spu" value="{{$list['is_spu']}}">
    <input type="hidden" name="layer_index" value="{{$list['layer_index']}}">
    <input type="hidden" name="form_type" value="sale_channel">
    @if($list['is_spu'] == '2')
        <div class="form-group row form-item">
            <input type="hidden" name="attr_id" value="{{$list['prod_attr_id']}}">
            <label style=" font-weight: normal" class="control-label col-xs-12 col-sm-2 pgc-font">
                商品属性：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <span class="col-sm-5 control-label pi-cate-name" style="text-align: left!important;"> {{$list['prod_attr_value']}}</span>
                </div>
                <span class="msg-box" style="position:static;" for="prod_attr_value"></span>
            </div>
        </div>
        <input type="hidden" name="prod_attr_id" value="{{$list['prod_attr_id']}}">
    @endif
    <div class="form-group row form-item">
        <label style=" font-weight: normal" class="control-label col-xs-12 col-sm-2 pgc-font">
            定价方式：</label>
        <div class="col-xs-12 col-sm-10">
            <div class="row" style="    margin-bottom: .5rem;">
                @component('component/radio',['radio'=>['1'=>'一口价'],'default_key'=>1,'name'=>'set_price_type'])
                @endcomponent
            </div>
            <span class="msg-box" style="position:static;" for="set_price_type"></span>
        </div>
    </div>
    <div class="form-group row form-item">
        <label style=" font-weight: normal" class="control-label col-xs-12 col-sm-2 pgc-font">
        </label>
        <div class="col-xs-12 col-sm-10">
            <div class="row" style="    margin-bottom: .5rem;">
                <table width="100%" >

                    <thead>
                    <tr class="price-table-head">
                        <td width=" @if(isset($list['is_add_page'])&&$list['is_add_page'] == '1') 24% @else 33% @endif">渠道</td>
                        <td width=" @if(isset($list['is_add_page'])&&$list['is_add_page'] == '1') 24% @else 33% @endif">组别</td>
                        <td width=" @if(isset($list['is_add_page'])&&$list['is_add_page'] == '1') 24% @else 33% @endif">@if(isset($list['is_add_page'])&&$list['is_add_page'] == '1') {{$list['min_p']}}p价格  @else  价格 @endif</td>
                        @if(isset($list['is_add_page'])&&$list['is_add_page'] == '1') <td width="24%">每{{$list['p_rule']}}p价格</td> @endif
                    </tr>
                    <tr class="s_header_tr"></tr>

                    </thead>
                    <tbody class="price-table-content">
                    @foreach($channleList as $k=>$v)
                        <tr>
                            <td>{{$v['channle_name']}}</td>
                            <td>
                                @foreach($v['customer'] as $kk=>$vv)
                                    <span class="channle_name_span channle_child">{{$vv}}</span><br>
                                @endforeach
                            </td>
                            <td>
                                @foreach ($v['customer'] as $kk=>$vv)
                                    <input type="number" name="customer-group" class="channle_name_input channle_child" data-type ='start-price' value="@if(isset($v['price'])&&isset($v['price'][$kk])){{$v['price'][$kk]}}@endif" data-price-id="@if(isset($v['price_id'])&&isset($v['price_id'][$kk])){{$v['price_id'][$kk]}}@endif" data-customer-id ={{$kk}} data-channle-id={{$k}}><br>

                                @endforeach
                            </td>
                            @if(isset($list['is_add_page'])&&$list['is_add_page'] == '1')
                                <td>
                                    @foreach ($v['customer'] as $kk=>$vv)
                                        <input type="number" name="customer-group" class="channle_name_input channle_child" data-type ='add-price' value="@if(isset($v['add_price'])&&isset($v['add_price'][$kk])){{$v['add_price'][$kk]}}@endif" data-price-id="@if(isset($v['price_id'])&&isset($v['price_id'][$kk])){{$v['price_id'][$kk]}}@endif" data-customer-id ={{$kk}} data-channle-id={{$k}}><br>

                                    @endforeach
                                </td>
                            @endif

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>






    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <span class="btn btn-primary btn-3F51B5 btn-sure btn-set-channle-price" data-value = "{{$list['layer_index']}}" data-flag = "{{$list['only_flag']}}" style="line-height: 26px!important;">确定</span>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <span  class="btn btn-write cancel-set-price"  data-value = "{{$list['layer_index']}}" data-flag = "{{$list['only_flag']}}"  style="line-height: 26px!important;width: 80px;height: 30px;">取消</span>
        </div>
    </div>

</div>


{{--填写渠道定价 end--}}