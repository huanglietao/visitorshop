{{--填写商品属性 start--}}
<div class="product-attr" style="display: none">
    <div class="step-all">
        @component('component/step',['count' => 3,'now_step'=>'3','bottom_title' => ['1' => '选择商品分类','2' => '填写商品信息','3'=>'填写商品属性'],'default_color'=>'#bbb','active_color'=>'#259B24' ])
        @endcomponent
    </div>
    <div class="d_well d_well-sm" style="margin-bottom: 1px">
        <span class="d_well-img-c"><i class="fa fa-dot-circle-o"></i></span>
        <span class="d_well-font">商品属性信息</span>
    </div>

    <input type="hidden" name="personal_print" class="is_personal_print" value="{{$prodList['is_personal_print']}}">
    {{--影像类商品才会出现 start--}}
    @if($prodList['is_personal_print'])
    <div class="goods-image-category">
        <div class="form-group row form-item">
            <label style=" font-weight: normal" class="control-label col-xs-12 col-sm-2 pgc-font">
                商品规格：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <select  class="form-control col-sm-5" id="prod_size_id" name="prod_size_id" class="form-control" >
                        <option value="">无</option>

                        @foreach ($productSizeList as $k=>$v)
                            <option value='{{$v['size_id']}}' @if($v['size_id'] == $prodList['print']['prod_size_id'])selected @endif>{{$v['size_new_name']}}</option>
                        @endforeach

                    </select>
                    <span class="col-sm-7 control-label pgc-font" style="text-align: left!important;"><span style="color: #007bff!important;" data-prod-id = {{$prodList['prod_id']}}   class="custom-product-size" data-area="['70%', '70%']" data-url="{{URL::asset('/goods/custom_products_size/form')}}" data-title = "自定义规格">自定义规格</span> &nbsp;&nbsp;&nbsp;个性定制影像商品必须绑定商品规格，否则编辑器无法正常工作。</span>
                </div>
                <span class="msg-box" style="position:static;" for="prod_size_id"></span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="prod_express_fee" class="control-label col-xs-12 col-sm-2 pgc-font">
                商品可否加减p：</label>
            <input type="hidden" class="add_page_flag" value="0">
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div class="col-sm-5" style="line-height: 30px;">
                        <input type="hidden" name="prod_is_add_page" value="{{$prodList['print']['prod_pt_variable']}}">
                        @component('component/radio',['radio'=>[0=>'不支持加减p(固定p)',1=>'支持加减p'],'is_disabled'=>1, 'default_key'=>$prodList['print']['prod_pt_variable'],'name'=>'prod_is_add_page'])
                        @endcomponent
                    </div>
                    <span class="col-sm-7 color-6A6969">支持加减P：用户制作作品时可在设置的P数范围内，按加P规则自行调整；不支持：用户制作时只能按上述P数属性选择对应P数。 </span>
                </div>
                <span class="msg-box" style="position:static;" for="prod_is_add_page"></span>
            </div>
        </div>
        @if($prodList['print']['prod_pt_variable'])
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="prod_express_fee" class="control-label col-xs-12 col-sm-2 pgc-font">
            </label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div class="col-sm-1 pgc-font">
                        p数范围:
                    </div>
                    <div class="col-sm-1">
                        <input type="number"  name="prod_min_add_page" class="prod_min_add_page" value="{{$prodList['print']['prod_pt_min_p']}}" style="width: 100%">
                    </div>
                    <div class="col-sm-1" style="text-align: center">
                        -
                    </div>
                    <div class="col-sm-1">
                        <input type="number" name="prod_max_add_page" value="{{$prodList['print']['prod_pt_max_p']}}" style="width: 100%">
                    </div>
                    <div class="col-sm-1" style="text-align: center">

                    </div>

                    <span class="col-sm-7 color-6A6969">P数范围与当前商品装订工艺有密切关系，请正确设置。 </span>
                </div>
                <span class="msg-box" style="position:static;" for="prod_express_fee"></span>
            </div>
        </div>
        @endif
        @if($prodList['print']['prod_pt_variable'])
        <div class="form-group row form-item prod_add_page">
            <label style=" font-weight: normal" for="prod_express_fee" class="control-label col-xs-12 col-sm-2 pgc-font">
            </label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div class="col-sm-1 pgc-font">
                        加减p规则数量:
                    </div>
                    <div class="col-sm-4">
                        <input type="number" name="prod_add_page" value="{{$prodList['print']['prod_pt_variable_base']}}" class="prod_p_rule"   style="width: 100%" >
                    </div>

                    <span class="col-sm-7 color-6A6969">设置每次可加减p的p数 </span>
                </div>
                <span class="msg-box" style="position:static;" for="prod_express_fee"></span>
            </div>
        </div>
        @endif

    </div>
    @endif
    {{--影像类商品才会出现 end--}}

    {{--<div class="form-group row form-item">
        <label style=" font-weight: normal" class="control-label col-xs-12 col-sm-2 pgc-font">
            商品类型：</label>
        <div class="col-xs-12 col-sm-10">
            <div class="row">
                <select  class="form-control col-sm-5" id="prod_size_id" name="prod_size_id" class="form-control" >
                    <option value="0">请选择</option>

                    <option value="1">规格1</option>
                    <option value="2">规格2</option>
                    <option value="23">规格3</option>

                </select>
                <span class="col-sm-7 control-label pgc-font" style="text-align: left!important;"> <span style="color: #007bff!important;">添加规格 &nbsp;&nbsp;&nbsp;</span> <span style="color: #007bff!important;">添加属性 &nbsp;&nbsp;&nbsp;</span> 商品类型指是某一类有相同属性的商品集合。例：服装类商品都有尺码、颜色等属性</span>
            </div>
            <span class="msg-box" style="position:static;" for="prod_size_id"></span>
        </div>
    </div>--}}
    {{--sku属性--}}
    @if($prodList['prod_price_type'] == 2)
    <div class="form-group row form-item pro_sku_comb">
        <label style=" font-weight: normal" class="control-label col-xs-12 col-sm-2 pgc-font">
            商品属性:
        </label>
        <div class="col-xs-12 col-sm-10 product-sku">
            @foreach($prodList['attribute'] as $k => $v)
                <div class="row product-sku-row ">
                    <div class="col-sm-1 pgc-attr-font attr-pid" data-id="{{$k}}" >
                        <span class="attr_p_value">{{$v['attr_name']}}</span>
                    </div>
                    <div class="col-sm-11 attr-child">
                        {{--每行放7个checkbox，超过三行则自动隐藏--}}
                        @php $start = 1;  @endphp
                        @foreach($v['attr_value'] as $kk=>$vv)
                            @php if (in_array($vv['attr_val_id'],$prodList['attribute_value'])){$is_disabled = 1; $checked = $vv['attr_val_id'];}else{$is_disabled = 0; $checked = 0;} @endphp
                            <div class="prod-sku-check prod-sku-check-{{$k}}  @if($start > 21) no-show-check @else show-check  @endif " >
                                @component('component/checkbox',['checkbox'=>[$vv['attr_val_id'] => $vv['attr_val_name']],'name'=>['attr_value'],'is_disabled'=>$is_disabled,'checked'=>$checked,'custom_class'=>'attr-value','right_distance' => '30'])
                                @endcomponent
                            </div>
                            @php $start++; @endphp
                        @endforeach
                        @if($start > 21)
                            <div class="more-show" data-action="show"><span class="show-text">更多</span><i class="fa fa-angle-double-down"></i></div>
                        @endif

                    </div>
                </div>
            @endforeach
            <div class="row product-sku-row ">
                <button class="btn btn-blue btn-attr-add" style="color: #ffffff;vertical-align: top;margin-left: 10px">生成货品</button>
            </div>
        </div>
    </div>
    @endif

    <div class="pa-table" style="margin-bottom: 50px">
        <table width="100%" class="pa-sku-table">
            <thead>
            <tr class="table-head ">

                @if($prodList['prod_price_type'] == '2')
                    {{--sku商品--}}
                    <td>
                        <span>开卖</span>
                    </td>
                    <td>
                        <span>{{$prodList['attribute_name']}}</span>
                    </td>
                    @if( $prodList['is_personal_print'] && ( isset($prodList['print']) && $prodList['print']['prod_pt_variable'] == 1))
                        <td class="add_p_tr_price">{{$prodList['print']['prod_pt_min_p']}}P销售价</td>
                        <td class="add_p_tr_cost">{{$prodList['print']['prod_pt_min_p']}}P成本价</td>
                        <td class="add_p_tr_weight">{{$prodList['print']['prod_pt_min_p']}}P重量（克）</td>
                        @if($prodList['is_personal_print'])
                            <td class="add_p_tr_spine_thickness">{{$prodList['print']['prod_pt_min_p']}}p书脊厚度</td>
                        @endif
                        <td class="add_base_p_tr_price">每{{$prodList['print']['prod_pt_variable_base']}}p销售价</td>
                        <td class="add_base_p_tr_cost">每{{$prodList['print']['prod_pt_variable_base']}}p成本价</td>
                        <td class="add_base_p_tr_weight">每{{$prodList['print']['prod_pt_variable_base']}}p重量（克）</td>
                        @if($prodList['is_personal_print'])
                            <td class="add_base_p_tr_spine_thickness">每{{$prodList['print']['prod_pt_variable_base']}}p书脊厚度</td>
                        @endif
                    @else
                        <td>销售价</td>
                        <td>成本价</td>
                        <td>重量（克）</td>
                        @if($prodList['is_personal_print'])
                            <td class="add_p_tr_spine_thickness">书脊厚度</td>
                        @endif
                    @endif
                    <td>推荐照片数</td>
                    <td>商品货号</td>
                    <td>供应商码</td>
                    <td>操作</td>
                @else
                    {{--spu商品--}}
                    <td>
                        <span>开卖</span>
                    </td>
                    @if( $prodList['is_personal_print'] && ( isset($prodList['print']) && $prodList['print']['prod_pt_variable'] == 1))
                        <td class="add_p_tr_price">{{$prodList['print']['prod_pt_min_p']}}P销售价</td>
                        <td class="add_p_tr_cost">{{$prodList['print']['prod_pt_min_p']}}P成本价</td>
                        <td class="add_p_tr_weight">{{$prodList['print']['prod_pt_min_p']}}P重量（克）</td>
                        @if($prodList['is_personal_print'])
                            <td style="width: 6.5%" class="add_p_tr_spine_thickness">{{$prodList['print']['prod_pt_min_p']}}书脊厚度</td>
                        @endif
                        <td class="add_base_p_tr_price">每{{$prodList['print']['prod_pt_variable_base']}}p销售价</td>
                        <td class="add_base_p_tr_cost">每{{$prodList['print']['prod_pt_variable_base']}}p成本价</td>
                        <td class="add_base_p_tr_weight">每{{$prodList['print']['prod_pt_variable_base']}}p重量（克）</td>
                        @if($prodList['is_personal_print'])
                            <td class="add_base_p_tr_spine_thickness">每{{$prodList['print']['prod_pt_variable_base']}}p书脊厚度</td>
                        @endif
                    @else
                        <td>销售价</td>
                        <td>成本价</td>
                        <td>重量（克）</td>
                        @if($prodList['is_personal_print'])
                            <td class="add_p_tr_spine_thickness">书脊厚度</td>
                        @endif
                    @endif
                    <td>推荐照片数</td>
                    <td>商品货号</td>
                    <td>供应商码</td>
                    <td>操作</td>

                @endif


            </tr>
            <tr class="s_header_tr"></tr>
            <input type="hidden" class="add_page" value="@if(isset($prodList['print']) && $prodList['print']['prod_pt_variable']){{$prodList['print']['prod_pt_variable']}} @endif">
            </thead>
            <tbody class="pa-content">

            @if($prodList['prod_price_type'] == '2')
                @foreach($prodList['sku_info'] as $k=>$v)
                    @php
                        $unquid = uniqid();
                    @endphp
                    <tr>
                        <input type="hidden" value="{{$v['prod_sku_id']}}"  name="sku_id[]">
                        {{--sku商品--}}
                        <td style="width: 4%">
                            <input type="hidden" value="{{$v['attr_value_id']}}"  name="attr_id[]">

                            <input type="hidden" value="{{$v['prod_sku_onsale_status']}}"  name="sku_onsale[]">
                            @php if ($v['prod_sku_onsale_status'] == '1') {$check=0;}else{$check=1;} @endphp
                            @component('component/checkbox',['checkbox'=>[''],'name'=>['checkbox[]'],'checked'=>$check, 'data_value'=>$v['attr_value_id'],'custom_class'=>"cc_checkedres checkbox sku_onsale",'right_distance'=>10])
                            @endcomponent
                        </td>
                        <td style="width: 16%">
                            <span class="prod-sku-value">{{$v['attr_value_name']}}</span>
                        </td>
                        @if( $prodList['is_personal_print'] && ( isset($prodList['print']) && $prodList['print']['prod_pt_variable'] == 1))
                            {{--可加p-sku--}}
                            <td style="width: @if($prodList['is_personal_print']) 5.2% @else 7% @endif"><input type="number" value="{{$v['prod_sku_price']}}" class="pa-attr-input " name="prod_min_p_price[]" data-rule="{{$prodList['print']['prod_pt_min_p']}}P销售价:required"></td>
                            <td style="width: @if($prodList['is_personal_print']) 5.2% @else 7% @endif"><input type="number" value="{{$v['prod_sku_cost']}}" class="pa-attr-input " name="prod_min_p_cost[]" data-rule="{{$prodList['print']['prod_pt_min_p']}}P成本价:required"></td>
                            <td style="width: @if($prodList['is_personal_print']) 5.2% @else 7% @endif"><input type="number" value="{{$v['prod_sku_weight']}}" class="pa-attr-input " name="prod_min_p_weight[]" data-rule="{{$prodList['print']['prod_pt_min_p']}}P重量（克）:required"></td>
                            @if($prodList['is_personal_print'])
                                <td style="width: 5.2%"><input type="number" class="pa-attr-input" value="{{$v['prod_spine_thickness']}}" name="prod_min_spine_thickness[]" data-rule="{{$prodList['print']['prod_pt_min_p']}}书脊厚度:required"></td>
                            @endif
                            <td style="width: @if($prodList['is_personal_print']) 5.2% @else 7% @endif"><input type="number" value="{{$v['add_p_price']}}" class="pa-attr-input " name="prod_add_p_price[]" data-rule="每{{$prodList['print']['prod_pt_variable_base']}}p销售价:required"></td>
                            <td style="width: @if($prodList['is_personal_print']) 5.2% @else 7% @endif"><input type="number" value="{{$v['add_p_cost']}}" class="pa-attr-input " name="prod_add_p_cost[]" data-rule="每{{$prodList['print']['prod_pt_variable_base']}}P成本价:required"></td>
                            <td style="width: @if($prodList['is_personal_print']) 5.2% @else 7% @endif"><input type="number" value="{{$v['add_p_weight']}}" class="pa-attr-input " name="prod_add_p_weight[]" data-rule="每{{$prodList['print']['prod_pt_variable_base']}}P重量（克）:required"></td>
                            @if($prodList['is_personal_print'])
                                <td style="width: 5.2%"><input type="number" class="pa-attr-input" value="{{$v['add_p_spine_thickness']}}" name="prod_add_spine_thickness[]" data-rule="{{$prodList['print']['prod_pt_variable_base']}}书脊厚度:required"></td>
                            @endif
                            {{--可加p-sku--}}
                        @else
                            {{--不可加p-sku--}}
                            <td style="width: @if($prodList['is_personal_print']) 10.5% @else 14% @endif"><input type="number" value="{{$v['prod_sku_price']}}"  class="pa-attr-input " name="prod_sku_price[]" data-rule="销售价:required"></td>
                            <td style="width: @if($prodList['is_personal_print']) 10.5% @else 14% @endif"><input type="number" value="{{$v['prod_sku_cost']}}"  class="pa-attr-input " name="prod_sku_cost[]" data-rule="成本价:required"></td>
                            <td style="width: @if($prodList['is_personal_print']) 10.5% @else 14% @endif"><input type="number" value="{{$v['prod_sku_weight']}}"  class="pa-attr-input " name="prod_sku_weight[]" data-rule="重量（克）:required"></td>
                            @if($prodList['is_personal_print'])
                                <td style="width: 10.5%"><input type="number" value="{{$v['prod_spine_thickness']}}" class="pa-attr-input" name="prod_sku_spine_thickness[]" data-rule="书脊厚度:required"></td>
                            @endif
                            {{--不可加p-sku--}}
                        @endif



                        <td style="width:14%"><input type="text" value="{{$v['prod_min_photo']}}" class="pa-attr-input pa-attr-photo-input " name="prod_min_photo[]" data-rule="最小照片数:required">  <span class="photo-line">-</span>  <input type="text" class="pa-attr-photo-input pa-attr-input " value="{{$v['prod_max_photo']}}" name="prod_max_photo[]" data-rule="最大照片数:required"></td>
                        <td style="width:7%"><input type="text" value="{{$v['prod_sku_sn']}}" class="pa-attr-input prod_sku_sn " name="prod_sku_sn[]" data-rule="商品货号:required"> </td>
                        <td style="width:7%"><input type="text" value="{{$v['prod_supplier_sn']}}" class="pa-attr-input " name="prod_supplier_sn[]" data-rule="供货商码:required"> </td>
                        <td style="width: 10%">
                            <input type="hidden" class="uniqid-num" value="{{$unquid}}">
                           {{-- <span class="pa-attr-op  sales_price-form  " style="color: #007bff!important;">渠道定价 &nbsp;&nbsp;</span>--}}
                            <input type="hidden" name="sale_channle_price[]"  class="sales_price_{{$unquid}}" value="{{$v['sale_channle_price']}}">
                            <span class="pa-attr-op supplier_price-form supplier_price_{{$unquid}}" style="color: #007bff!important;">供货定价</span>
                            <input type="hidden" name="supplier_price[]" class="sales_price_{{$unquid}}" value="{{$v['sup_channle_price']}}">



                            {{--<input type="hidden" class="uniqid-num" value="{{$unquid}}">
                            <span class="pa-attr-op  sales_price-form  "  style="color: #007bff!important;">渠道定价 &nbsp;&nbsp;</span>
                            <input type="hidden" name="sale_channle_price[]"  class="sales_price_{{$unquid}} @if($v['sale_channle_price']!="") sale_price  @endif" value="{{$v['sale_channle_price']}}">
                            <span class="pa-attr-op supplier_price-form supplier_price_{{$unquid}}" style="color: #007bff!important;">供货定价</span>
                            <input type="hidden" name="supplier_price[]" class="sales_price_{{$unquid}}" value="">--}}
                        </td>
                        {{--sku商品--}}
                    </tr>
                @endforeach

            @else
                @foreach($prodList['sku_info'] as $k=>$v)
                @php
                    $unquid = uniqid();
                @endphp
                <td style="width: 4%">
                    <input type="hidden" value="{{$v['prod_sku_id']}}"  name="sku_id[]">
                    <input type="hidden" value="{{$v['prod_sku_onsale_status']}}"  name="sku_onsale[]">
                    @php if ($v['prod_sku_onsale_status'] == '1') {$check=0;}else{$check=1;} @endphp
                    @component('component/checkbox',['checkbox'=>[''],'name'=>['checkbox[]'],'checked'=>$check, 'data_value'=>'','custom_class'=>"cc_checkedres checkbox sku_onsale",'right_distance'=>10])
                    @endcomponent
                </td>
                @if( $prodList['is_personal_print'] && ( isset($prodList['print']) && $prodList['print']['prod_pt_variable'] == 1))
                    <td style="width: @if($prodList['is_personal_print']) 6.5% @else 8.6% @endif"><input type="number" value="{{$v['prod_sku_price']}}" class="pa-attr-input " name="prod_min_p_price[]" data-rule="{{$prodList['print']['prod_pt_min_p']}}P销售价:required"></td>
                    <td style="width: @if($prodList['is_personal_print']) 6.5% @else 8.6% @endif"><input type="number" value="{{$v['prod_sku_cost']}}" class="pa-attr-input " name="prod_min_p_cost[]" data-rule="{{$prodList['print']['prod_pt_min_p']}}P成本价:required"></td>
                    <td style="width: @if($prodList['is_personal_print']) 6.5% @else 8.6% @endif"><input type="number" value="{{$v['prod_sku_weight']}}" class="pa-attr-input " name="prod_min_p_weight[]" data-rule="{{$prodList['print']['prod_pt_min_p']}}P重量（克）:required"></td>
                    @if($prodList['is_personal_print'])
                        <td style="width:6.5%;"><input type="number" class="pa-attr-input" value="{{$v['prod_spine_thickness']}}" name="prod_min_spine_thickness[]" data-rule="{{$prodList['print']['prod_pt_min_p']}}书脊厚度:required"></td>
                    @endif
                    <td style="width: @if($prodList['is_personal_print']) 6.5% @else 8.6% @endif"><input type="number" value="{{$v['add_p_price']}}" class="pa-attr-input " name="prod_add_p_price[]" data-rule="每{{$prodList['print']['prod_pt_variable_base']}}p销售价:required"></td>
                    <td style="width: @if($prodList['is_personal_print']) 6.5% @else 8.6% @endif"><input type="number" value="{{$v['add_p_cost']}}" class="pa-attr-input " name="prod_add_p_cost[]" data-rule="每{{$prodList['print']['prod_pt_variable_base']}}P成本价:required"></td>
                    <td style="width: @if($prodList['is_personal_print']) 6.5% @else 8.6% @endif"><input type="number" value="{{$v['add_p_weight']}}" class="pa-attr-input " name="prod_add_p_weight[]" data-rule="每{{$prodList['print']['prod_pt_variable_base']}}P重量（克）:required"></td>
                    @if($prodList['is_personal_print'])
                        <td style="width: 6.5%"><input type="number" class="pa-attr-input" name="prod_add_spine_thickness[]" value="{{$v['add_p_spine_thickness']}}" data-rule="{{$prodList['print']['prod_pt_variable_base']}}书脊厚度:required"></td>
                    @endif
                @else
                    <td style="width: @if($prodList['is_personal_print']) 10.5% @else 14% @endif"><input type="number" value="{{$v['prod_sku_price']}}"  class="pa-attr-input " name="prod_sku_price[]" data-rule="销售价:required"></td>
                    <td style="width: @if($prodList['is_personal_print']) 10.5% @else 14% @endif"><input type="number" value="{{$v['prod_sku_cost']}}"  class="pa-attr-input " name="prod_sku_cost[]" data-rule="成本价:required"></td>
                    <td style="width: @if($prodList['is_personal_print']) 10.5% @else 14% @endif"><input type="number" value="{{$v['prod_sku_weight']}}"  class="pa-attr-input " name="prod_sku_weight[]" data-rule="重量（克）:required"></td>
                    @if($prodList['is_personal_print'])
                        <td style="width: 10.5%"><input type="number" value="{{$v['prod_spine_thickness']}}" class="pa-attr-input" name="prod_sku_spine_thickness[]" data-rule="书脊厚度:required"></td>
                    @endif
                @endif



                <td style="width:@if(isset($prodList['print']) && $prodList['print']['prod_pt_variable'] == 1) 14%  @else 16% @endif"><input type="number" value="{{$v['prod_min_photo']}}" class="pa-attr-input pa-attr-photo-input " name="prod_min_photo[]" data-rule="最小照片数:required">  <span class="photo-line">-</span>  <input type="number" class="pa-attr-photo-input pa-attr-input " value="{{$v['prod_max_photo']}}" name="prod_max_photo[]" data-rule="最大照片数:required"></td>
                <td style="width:@if(isset($prodList['print']) && $prodList['print']['prod_pt_variable'] == 1) 7%  @else 14% @endif"><input type="text" value="{{$v['prod_sku_sn']}}" class="pa-attr-input prod_sku_sn " name="prod_sku_sn[]" data-rule="商品货号:required"> </td>
                <td style="width:@if(isset($prodList['print']) && $prodList['print']['prod_pt_variable'] == 1) 7%  @else 14% @endif"><input type="text" value="{{$v['prod_supplier_sn']}}" class="pa-attr-input " name="prod_supplier_sn[]" data-rule="供货商码:required"> </td>
                <td style="width: 10%">
                    <input type="hidden" class="uniqid-num" value="{{$unquid}}">
                    {{--<span class="pa-attr-op  sales_price-form  " style="color: #007bff!important;">渠道定价 &nbsp;&nbsp;</span>--}}
                    <input type="hidden" name="sale_channle_price[]"  class="sales_price_{{$unquid}}" value="{{$v['sale_channle_price']}}">
                    <span class="pa-attr-op supplier_price-form supplier_price_{{$unquid}}" style="color: #007bff!important;">供货定价</span>
                    <input type="hidden" name="supplier_price[]" class="sales_price_{{$unquid}}" value="{{$v['sup_channle_price']}}">
                </td>
                @endforeach
            @endif

            </tbody>
            @if($prodList['prod_price_type'] == '2')
                <tr class="pa-attr-checkall">
                    <td>
                        @component('component/checkbox',['checkbox'=>[''],'name'=>['checkall'],'custom_class'=>"cc_checkall checkbox",'right_distance'=>10])
                        @endcomponent
                    </td>
                    <td style="text-align: left;padding-top: 5px">
                        <span>全选/反选 &nbsp;&nbsp;</span>
                        {{--<button class="btn btn-blue" style="background: #dc3545; color: #ffffff;vertical-align: top;"><i class="fa fa-trash"></i> &nbsp;删除</button>--}}
                    </td>
                    <td colspan="11" style="text-align: center">
                        {{--  <span style="color: #dc3545">
                                  （说明：商品属性含有两页以上请用批量导入功能）
                          </span>--}}
                    </td>
                </tr>
            @endif
            <tr class="pa-attr-checkall">
                <td>

                </td>
                <td style="text-align: left;padding-top: 5px">

                </td>
                <td colspan="11" style="text-align: center">
                    {{--  <button class="btn btn-blue" style="background: #007bff; color: #ffffff;">CSV批量上传</button>--}}
                </td>

            </tr>

        </table>
    </div>
 {{--   <div class="d_well d_well-sm" style="margin-bottom: 1px">
        <span class="d_well-img-c"><i class="fa fa-dot-circle-o"></i></span>
        <span class="d_well-font">商品属性图片</span>
    </div>

    <div class="pa-ap-table">
        <table width="100%">
            <thead>
            <tr class="table-head">
                <td style="width: 10%">
                    属性名称
                </td>
                <td style="width: 20%">
                    <span>示例图</span>
                </td>
                <td style="width: 20%">属性值</td>
                <td style="width: 20%">排序</td>
                <td style="width: 30%">操作</td>
            </tr>
            <tr class="s_header_tr"></tr>
            </thead>

            <tbody class="pa-content">
            <tr>
                <td>
                    规格
                </td>
                <td>
                    <img class="pa-attr-apt-img" src="/images/1.jpg" alt="">
                </td>
                <td>
                    210*285MM
                </td>
                <td>
                    <input type="text" class="pa-attr-input">
                </td>

                <td>
                    <button class="btn btn-blue" style="background: #007bff; color: #ffffff;"><i class="fa fa-cloud-upload"></i> &nbsp;上传图片</button>
                    <button class="btn btn-blue" style="background: #dc3545; color: #ffffff;"><i class="fa fa-trash"></i> &nbsp;删除</button>

                </td>
            </tr>
            <tr>
                <td rowspan="2">
                    规格
                </td>
                <td>
                    <img class="pa-attr-apt-img" src="/images/1.jpg" alt="">
                </td>
                <td>
                    210*285MM
                </td>
                <td>
                    <input type="text" class="pa-attr-input">
                </td>

                <td>
                    <button class="btn btn-blue" style="background: #007bff; color: #ffffff;"><i class="fa fa-cloud-upload"></i> &nbsp;上传图片</button>
                    <button class="btn btn-blue" style="background: #dc3545; color: #ffffff;"><i class="fa fa-trash"></i> &nbsp;删除</button>

                </td>
            </tr>
            <tr>

                <td>
                    <img class="pa-attr-apt-img" src="/images/1.jpg" alt="">
                </td>
                <td>
                    210*285MM
                </td>
                <td>
                    <input type="text" class="pa-attr-input">
                </td>

                <td>
                    <button class="btn btn-blue" style="background: #007bff; color: #ffffff;"><i class="fa fa-cloud-upload"></i> &nbsp;上传图片</button>
                    <button class="btn btn-blue" style="background: #dc3545; color: #ffffff;"><i class="fa fa-trash"></i> &nbsp;删除</button>

                </td>
            </tr>


            </tbody>
        </table>
    </div>--}}

    <div class="pgc-footer" style="margin-top: 50px">
        <button class="btn btn-return-good-attr" style="vertical-align: top;background: #ffffff;color: #000000;border: 1px solid #007bff">上一步,编辑商品信息</button>
        <button class="btn good-edit-cate-submit" style="background: #007bff;color: #ffffff;vertical-align: top;">完成,保存商品</button>
    </div>


</div>
{{--填写商品属性 end--}}