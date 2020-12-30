<table width="100%" class="pa-sku-table">
    <thead>
    <tr class="table-head ">
        @if($is_sku == '2')
            {{--sku商品--}}
            <td style="width: 4%">
                <span>开卖</span>
            </td>
            <td style="width: 16%">
                <span>{{$attr_p_value}}</span>
            </td>
            @if($is_add_page == '1')
                <td class="add_p_tr_price">{{$min_p}}P销售价</td>
                <td class="add_p_tr_cost">{{$min_p}}P成本价</td>
                <td class="add_p_tr_weight">{{$min_p}}P重量（克）</td>
                @if($is_personal_print)
                    <td class="add_p_tr_spine_thickness">{{$min_p}}p书脊厚度</td>
                @endif
                <td class="add_base_p_tr_price">每{{$p_rule}}p销售价</td>
                <td class="add_base_p_tr_cost">每{{$p_rule}}p成本价</td>
                <td class="add_base_p_tr_weight">每{{$p_rule}}p重量（克）</td>
                @if($is_personal_print)
                    <td class="add_base_p_tr_spine_thickness">每{{$p_rule}}p书脊厚度</td>
                @endif
            @else
                <td>销售价</td>
                <td>成本价</td>
                <td>重量（克）</td>
                @if($is_personal_print)
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
            @if($is_add_page == '1')
                <td class="add_p_tr_price">{{$min_p}}P销售价</td>
                <td class="add_p_tr_cost">{{$min_p}}P成本价</td>
                <td class="add_p_tr_weight">{{$min_p}}P重量（克）</td>
                @if($is_personal_print)
                    <td class="add_p_tr_spine_thickness">{{$min_p}}书脊厚度</td>
                @endif
                <td class="add_base_p_tr_price">每{{$p_rule}}p销售价</td>
                <td class="add_base_p_tr_cost">每{{$p_rule}}p成本价</td>
                <td class="add_base_p_tr_weight">每{{$p_rule}}p重量（克）</td>
                @if($is_personal_print)
                    <td class="add_base_p_tr_spine_thickness">每{{$p_rule}}p书脊厚度</td>
                @endif
            @else
                <td>销售价</td>
                <td>成本价</td>
                <td>重量（克）</td>
                @if($is_personal_print)
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
    </thead>
    <input type="hidden" class="add_page" value="{{$is_add_page}}">
    <tbody class="pa-content">

    @if($is_sku == '2')
        @foreach($attr_id_arr as $k=>$v)
            @php
                $unquid = uniqid();
            @endphp
            <tr>

                {{--sku商品--}}
                <td style="width: 4%">
                    <input type="hidden" value="{{$v['attr_str']}}"  name="attr_id[]">
                    <input type="hidden" value=""  name="sku_id[]">
                    <input type="hidden" value="0"  name="sku_onsale[]">
                    @component('component/checkbox',['checkbox'=>[''],'name'=>['checkbox[]'],'data_value'=>$v['attr_str'],'custom_class'=>"cc_checkedres checkbox sku_onsale",'right_distance'=>10])
                    @endcomponent
                </td>
                <td style="width: 16%">
                    <span class="prod-sku-value">{{$attr_value_arr[$k]['attr_str']}}</span>
                </td>
                @if($is_add_page == '1')
                    <td style="width: @if($is_personal_print) 5.2% @else 7% @endif"><input type="number" class="pa-attr-input" name="prod_min_p_price[]" data-rule="{{$min_p}}P销售价:required"></td>
                    <td style="width: @if($is_personal_print) 5.2% @else 7% @endif"><input type="number" class="pa-attr-input" name="prod_min_p_cost[]" data-rule="{{$min_p}}P成本价:required"></td>
                    <td style="width: @if($is_personal_print) 5.2% @else 7% @endif"><input type="number" class="pa-attr-input" name="prod_min_p_weight[]" data-rule="{{$min_p}}P重量（克）:required"></td>
                    @if($is_personal_print)
                        <td style="width: 5.2%"><input type="number" class="pa-attr-input" name="prod_min_spine_thickness[]" data-rule="{{$min_p}}书脊厚度:required"></td>
                    @endif
                    <td style="width: @if($is_personal_print) 5.2% @else 7% @endif"><input type="number" class="pa-attr-input" name="prod_add_p_price[]" data-rule="每{{$p_rule}}p销售价:required"></td>
                    <td style="width: @if($is_personal_print) 5.2% @else 7% @endif"><input type="number" class="pa-attr-input" name="prod_add_p_cost[]" data-rule="每{{$p_rule}}P成本价:required"></td>
                    <td style="width: @if($is_personal_print) 5.2% @else 7% @endif"><input type="number" class="pa-attr-input" name="prod_add_p_weight[]" data-rule="每{{$p_rule}}P重量（克）:required"></td>
                    @if($is_personal_print)
                        <td style="width: 5.2%"><input type="number" class="pa-attr-input" name="prod_add_spine_thickness[]" data-rule="{{$p_rule}}书脊厚度:required"></td>
                    @endif
                @else
                    <td style="width: @if($is_personal_print) 10.5% @else 14% @endif"><input type="number" class="pa-attr-input" name="prod_sku_price[]" data-rule="销售价:required"></td>
                    <td style="width: @if($is_personal_print) 10.5% @else 14% @endif"><input type="number" class="pa-attr-input" name="prod_sku_cost[]" data-rule="成本价:required"></td>
                    <td style="width: @if($is_personal_print) 10.5% @else 14% @endif"><input type="number" class="pa-attr-input" name="prod_sku_weight[]" data-rule="重量（克）:required"></td>
                    @if($is_personal_print)
                        <td style="width: 10.5%"><input type="number" class="pa-attr-input" name="prod_sku_spine_thickness[]" data-rule="书脊厚度:required"></td>
                    @endif
                @endif



                <td style="width:14%">
                    <input type="number" class="pa-attr-input pa-attr-photo-input" name="prod_min_photo[]" data-rule="最小照片数:required">
                    <span class="photo-line">-</span>
                    <input type="number" class="pa-attr-photo-input" name="prod_max_photo[]" data-rule="最大照片数:required"></td>
                <td style="width:7%"><input type="text" class="pa-attr-input prod_sku_sn" name="prod_sku_sn[]" data-rule="商品货号:required"> </td>
                <td style="width:7%"><input type="text" class="pa-attr-input" name="prod_supplier_sn[]" data-rule="供货商码:required"> </td>
                <td style="width: 10%">
                    <input type="hidden" class="uniqid-num" value="{{$unquid}}">
                    <span class="pa-attr-op  sales_price-form  " style="color: #007bff!important;">渠道定价 &nbsp;&nbsp;</span>
                    <input type="hidden" name="sale_channle_price[]"  class="sales_price_{{$unquid}}" value="">
                    <span class="pa-attr-op supplier_price-form supplier_price_{{$unquid}}" style="color: #007bff!important;">供货定价</span>
                    <input type="hidden" name="supplier_price[]" class="sales_price_{{$unquid}}" value="">
                </td>
            </tr>
        @endforeach

    @else

        @php
            $unquid = uniqid();
        @endphp
        <td style="width: 4%">
            <input type="hidden" value=""  name="sku_id[]">
            <input type="hidden" value="0"  name="sku_onsale[]">
            @component('component/checkbox',['checkbox'=>[''],'name'=>['checkbox[]'],'data_value'=>'','custom_class'=>"cc_checkedres checkbox sku_onsale",'right_distance'=>10])
            @endcomponent
        </td>
        @if($is_add_page == '1')
            <td style="width: @if($is_personal_print) 6.5% @else 8.6% @endif"><input type="number" class="pa-attr-input" name="prod_min_p_price[]"  data-rule="{{$min_p}}P销售价:required"></td>
            <td style="width: @if($is_personal_print) 6.5% @else 8.6% @endif"><input type="number" class="pa-attr-input" name="prod_min_p_cost[]" data-rule="{{$min_p}}P成本价:required"></td>
            <td style="width: @if($is_personal_print) 6.5% @else 8.6% @endif"><input type="number" class="pa-attr-input" name="prod_min_p_weight[]" data-rule="{{$min_p}}P重量（克）:required"></td>
            @if($is_personal_print)
                <td style="width:6.5%;"><input type="number" class="pa-attr-input" name="prod_min_spine_thickness[]" data-rule="{{$min_p}}书脊厚度:required"></td>
            @endif
            <td style="width: @if($is_personal_print) 6.5% @else 8.6% @endif"><input type="number" class="pa-attr-input" name="prod_add_p_price[]" data-rule="每{{$p_rule}}p销售价:required"></td>
            <td style="width: @if($is_personal_print) 6.5% @else 8.6% @endif"><input type="number" class="pa-attr-input" name="prod_add_p_cost[]" data-rule="每{{$p_rule}}P成本价:required"></td>
            <td style="width: @if($is_personal_print) 6.5% @else 8.6% @endif"><input type="number" class="pa-attr-input" name="prod_add_p_weight[]" data-rule="每{{$p_rule}}P重量（克）:required"></td>
            @if($is_personal_print)
                <td style="width: 6.5%"><input type="number" class="pa-attr-input" name="prod_add_spine_thickness[]" data-rule="{{$p_rule}}书脊厚度:required"></td>
            @endif
        @else
            <td style="width:@if($is_personal_print) 10.5% @else 14% @endif"><input type="number" class="pa-attr-input" name="prod_sku_price[]" data-rule="销售价:required"></td>
            <td style="width:@if($is_personal_print) 10.5% @else 14% @endif"><input type="number" class="pa-attr-input" name="prod_sku_cost[]" data-rule="成本价:required"></td>
            <td style="width:@if($is_personal_print) 10.5% @else 14% @endif"><input type="number" class="pa-attr-input" name="prod_sku_weight[]" data-rule="重量（克）:required"></td>
            @if($is_personal_print)
                <td style="width: 10.5%"><input type="number" class="pa-attr-input" name="prod_sku_spine_thickness[]" data-rule="书脊厚度:required"></td>
            @endif
        @endif



        <td style="width:14%"><input type="number" class="pa-attr-input pa-attr-photo-input" name="prod_min_photo[]" data-rule="最小照片数:required">  <span class="photo-line">-</span>  <input type="number" class="pa-attr-photo-input" name="prod_max_photo[]" data-rule="最大照片数:required"></td>
        <td style="width:7% "><input type="text" class="pa-attr-input prod_sku_sn" name="prod_sku_sn[]" data-rule="商品货号:required"> </td>
        <td style="width:7%"><input type="text" class="pa-attr-input" name="prod_supplier_sn[]" data-rule="供货商码:required"> </td>
        <td style="width: 10%">
            <input type="hidden" class="uniqid-num" value="{{$unquid}}">
            <span class="pa-attr-op  sales_price-form  " style="color: #007bff!important;">渠道定价 &nbsp;&nbsp;</span>
            <input type="hidden" name="sale_channle_price[]"  class="sales_price_{{$unquid}}" value="">
            <span class="pa-attr-op supplier_price-form supplier_price_{{$unquid}}" style="color: #007bff!important;">供货定价</span>
            <input type="hidden" name="supplier_price[]" class="sales_price_{{$unquid}}" value="">

        </td>
    @endif

    </tbody>
    @if($is_sku == '2')
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


