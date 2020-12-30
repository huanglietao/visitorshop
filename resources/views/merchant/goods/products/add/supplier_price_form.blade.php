{{--填写渠道定价 start--}}
<div class="main-channel">
    <!--  提示组件 start -->
    @component('component/tips')
    <p>供货定价是针对该商品有多个供货商，但供货商对商品供货成本价不统一时，进行的设定。</p>
    <p>供货价格设置的精准，将直接影响销售数据统计的准确性。</p>
    @endcomponent
<!--  提示组件 end -->
    <div class="form-group row form-item">
        <label style=" font-weight: normal" class="control-label col-xs-12 col-sm-2 pgc-font">
            商品名称：</label>
        <div class="col-xs-12 col-sm-10">
            <div class="row">
                <span class="col-sm-5 control-label pi-cate-name" style="text-align: left!important;"> {{$list['prod_name']}} @if(isset($list['is_add_page'])&&$list['is_add_page']=='1') (可加减p) @elseif(!isset($list['is_add_page'])) @else  (不可加减p)  @endif</span>
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
    <input type="hidden" name="form_type" value="supplier_price">
    @if($list['is_spu'] == '2')
        <input type="hidden" name="attr_id" value="{{$list['prod_attr_id']}}">
        <div class="form-group row form-item">
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
            供货商列表：</label>
    </div>

    <div class="form-group row form-item">
        <label style=" font-weight: normal" class="control-label col-xs-12 col-sm-2 pgc-font">
        </label>
        <div class="col-xs-12 col-sm-10">
            <div class="row">
                <table width="100%" >
                    <thead>
                    <tr class="price-table-head">
                        <td width=" @if(isset($list['is_add_page'])&&$list['is_add_page'] == '1') 33% @else 48% @endif">供货商</td>
                        <td width=" @if(isset($list['is_add_page'])&&$list['is_add_page'] == '1') 33% @else 48% @endif">@if(isset($list['is_add_page'])&&$list['is_add_page'] == '1') {{$list['min_p']}}p价格  @else  价格 @endif</td>
                        @if(isset($list['is_add_page'])&&$list['is_add_page'] == '1') <td width="33%">每{{$list['p_rule']}}p价格</td> @endif

                    </tr>
                    <tr class="s_header_tr"></tr>
                    </thead>
                    <tbody class="price-table-content">
                    @foreach($list['supplier'] as $k=>$v)
                        <tr>
                            <td>{{$v['sup_name']}}</td>
                            <td>
                                <input type="number" name="supplier_price" class="channle_name_input channle_child" data-type ='start-price' value="@if(isset($v['start_price'])){{$v['start_price']}}@endif" data-price-id="@if(isset($v['sup_price_id'])){{$v['sup_price_id']}}@endif" data-suplier-id ={{$v['sup_id']}}>
                            </td>
                            @if(isset($list['is_add_page'])&&$list['is_add_page'] == '1')
                                <td>
                                    <input type="number" name="supplier_price" class="channle_name_input channle_child" data-type ='add-price' value="@if(isset($v['add_price'])&&isset($v['add_price'])){{$v['add_price']}}@endif" data-price-id="@if(isset($v['sup_price_id'])){{$v['sup_price_id']}}@endif" data-suplier-id ={{$v['sup_id']}} >
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
            <span class="btn btn-primary btn-3F51B5 btn-sure btn-set-supplier-price" data-value = "{{$list['layer_index']}}" data-flag = "{{$list['only_flag']}}" style="line-height: 26px!important;">确定</span>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <span  class="btn btn-write cancel-set-price"  data-value = "{{$list['layer_index']}}" data-flag = "{{$list['only_flag']}}"  style="line-height: 26px!important;width: 80px;height: 30px;">取消</span>
        </div>
    </div>

</div>


{{--填写渠道定价 end--}}