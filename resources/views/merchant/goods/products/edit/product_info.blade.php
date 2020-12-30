<div class="good-info-fill">

    <div class="step-all">
        @component('component/step',['count' => 3,'now_step'=>'2','bottom_title' => ['1' => '选择商品分类','2' => '填写商品信息','3'=>'填写商品属性'],'default_color'=>'#bbb','active_color'=>'#259B24' ])
        @endcomponent
    </div>
    <div class="d_well d_well-sm" style="margin-bottom: 1px">
        <span class="d_well-img-c"><i class="fa fa-dot-circle-o"></i></span>
        <span class="d_well-font">商品通用信息</span>
    </div>

    <input type="hidden" name="prod_id" value="{{$prodList['prod_id']}}">
    <div class="form-group row form-item">
        <label style=" font-weight: normal" class="control-label col-xs-12 col-sm-2 pgc-font">
            分类名称：</label>
        <div class="col-xs-12 col-sm-10">
            <div class="row">

                <span class="col-sm-5 control-label pi-cate-name" style="text-align: left!important;"> {{$prodList['cate_name']}}</span>
                <span class="col-sm-7 control-label pgc-font" style="text-align: left!important;"> {{--<span class="btn-go-good-cate" style="color: #007bff!important;cursor: pointer">切换分类 &nbsp;&nbsp;&nbsp;</span>--}} 类目指的是商品类型对应系统购物流程，系统级定义，商家无权限自定义。</span>
            </div>
            <span class="msg-box" style="position:static;" for="cate_name"></span>
        </div>
    </div>

    <div class="form-group row form-item">
        <label style=" font-weight: normal" for="prod_name" class="control-label col-xs-12 col-sm-2 pgc-font">
            <span style="color:red">*</span> 商品名称：</label>
        <div class="col-xs-12 col-sm-10">
            <div class="row">
                <input  id="prod_name" class="form-control col-sm-5" name="prod_name" type="text" value="{{$prodList['prod_name']}}" placeholder="" {{--data-rule="商品名称:required"--}}>
                <span class="col-sm-7 color-6A6969"> 商品名称长度在商品设置中统一设置，当前最多输入100个字符约33个汉字。</span>
            </div>
            <span class="msg-box" style="position:static;" for="prod_name"></span>
        </div>
    </div>
    <div class="form-group row form-item">
        <label style=" font-weight: normal" for="prod_abbr" class="control-label col-xs-12 col-sm-2 pgc-font">
            商品简称：</label>
        <div class="col-xs-12 col-sm-10">
            <div class="row">
                <input  id="prod_abbr" class="form-control col-sm-5" name="prod_abbr" type="text" value="{{$prodList['prod_abbr']}}" placeholder="请填写商品简称" {{--data-rule="商品名称:required"--}}>
                <span class="col-sm-7 color-6A6969"> </span>
            </div>
            <span class="msg-box" style="position:static;" for="prod_abbr"></span>
        </div>
    </div>

    <div class="form-group row form-item">
        <label style=" font-weight: normal" for="prod_title" class="control-label col-xs-12 col-sm-2 pgc-font">
            副标题/简单描述：</label>
        <div class="col-xs-12 col-sm-10">
            <div class="row">
                <textarea class="form-control col-sm-5" name="prod_title" id="" cols="30" rows="5" style="resize: none;">{{$prodList['prod_title']}}</textarea>
                <span class="col-sm-7 color-6A6969"> 最多输入60个汉字。</span>
            </div>
            <span class="msg-box" style="position:static;" for="prod_title"></span>
        </div>
    </div>
    <div class="form-group row form-item">
        <label style=" font-weight: normal" for="mch_prod_cate_uid" class="control-label col-xs-12 col-sm-2 pgc-font">
            自定义分类：</label>
        <div class="col-xs-12 col-sm-10">
            <div class="row">
                <input type="hidden" name="mch_prod_cate_uid" value="">
                <label for="return_flag"></label>
                <select  class="selectpicker form-control col-sm-5 cus_prod_cate_uid bla bla bli" title="无" multiple data-live-search="false"  id="cus_prod_cate_uid" name="cus_prod_cate_uid" >
                    @foreach ($customCategoryList as $k=>$v)
                        <option value='{{$v['cus_cate_id']}}' @if(in_array($v['cus_cate_id'],$prodList['mch_cus_cate'])) selected @endif>{{$v['cate_name']}}</option>
                    @endforeach

                </select>
                <span class="col-sm-7 color-6A6969"></span>
            </div>
            <span class="msg-box" style="position:static;" for="cus_prod_cate_uid"></span>
        </div>
    </div>
    <div class="form-group row form-item">
        <label style=" font-weight: normal" for="prod_sn" class="control-label col-xs-12 col-sm-2 pgc-font">
            商品编码：</label>
        <div class="col-xs-12 col-sm-10">
            <div class="row">
                <input  id="prod_sn" class="form-control col-sm-5" name="prod_sn" type="text" value="{{$prodList['prod_sn']}}" placeholder="">
                <span class="col-sm-7 color-6A6969"> {{--数字和字母组合，必须保持唯一。不输入，则系统将自动生成唯一编码，编码前缀可在商品设置中指定。--}}</span>
            </div>
            <span class="msg-box" style="position:static;" for="prod_sn"></span>
        </div>
    </div>
    <div class="form-group row form-item">
        <label style=" font-weight: normal" for="prod_fee" class="control-label col-xs-12 col-sm-2 pgc-font">
            商品价格：</label>
        <div class="col-xs-12 col-sm-10">
            <div class="row">
                <input  id="prod_sn" class="form-control col-sm-5" name="prod_fee" type="text" value="{{$prodList['prod_fee']}}" placeholder="" >
                <span class="col-sm-7 color-6A6969"> </span>
            </div>
            <span class="msg-box" style="position:static;" for="prod_fee"></span>
        </div>
    </div>
    {{--<div class="form-group row form-item">
        <label style=" font-weight: normal" for="prod_fee" class="control-label col-xs-12 col-sm-2 pgc-font">
            商品重量：</label>
        <div class="col-xs-12 col-sm-10">
            <div class="row">
                <input  id="prod_sn" class="form-control col-sm-5" name="prod_fee" type="text" value="" placeholder="">
                <span class="col-sm-7 color-6A6969"> </span>
            </div>
            <span class="msg-box" style="position:static;" for="prod_fee"></span>
        </div>
    </div>--}}
    <div class="form-group row form-item">
        <label style=" font-weight: normal" for="prod_unit" class="control-label col-xs-12 col-sm-2 pgc-font">
            商品单位：</label>
        <div class="col-xs-12 col-sm-10">
            <div class="row">
                <input  id="prod_sn" class="form-control col-sm-5" name="prod_unit" type="text" value="{{$prodList['prod_unit']}}" placeholder="">
                <span class="col-sm-7 color-6A6969"> </span>
            </div>
            <span class="msg-box" style="position:static;" for="prod_unit"></span>
        </div>
    </div>
    <div class="form-group row form-item">
        <label style=" font-weight: normal" for="prod_stock_inventory" class="control-label col-xs-12 col-sm-2 pgc-font">
            商品库存：</label>
        <div class="col-xs-12 col-sm-10">
            <div class="row">
                <div class="col-sm-1" style="line-height: 30px;">

                    @component('component/checkbox',['checkbox'=>[1=>'启用库存'],'checked'=>$prodList['prod_stock_status'],'name'=>['prod_stock_status'],'custom_class'=>'','right_distance' => '0'])
                    @endcomponent
                </div>

                <input  id="prod_sn" class="form-control col-sm-4 stock-control" name="prod_stock_inventory" type="text" value="{{$prodList['prod_stock_inventory']}}" placeholder="" @if($prodList['prod_stock_status']==0) style="display: none"@endif>
                <span class="stock-control-line col-sm-4" @if($prodList['prod_stock_status']==1) style="display: none"@endif ></span>
                <span class="col-sm-7 color-6A6969">启用库存，则在购物流程中对库存操作，操作环节可在“系统设置>运营设置”中指定 </span>
            </div>
            <span class="msg-box" style="position:static;" for="prod_stock_inventory"></span>
        </div>
    </div>
    <div class="form-group row form-item stock-control"  @if($prodList['prod_stock_status']==0) style="display: none"@endif>
        <label style=" font-weight: normal" for="prod_stock_waring" class="control-label col-xs-12 col-sm-2 pgc-font">
            库存预警值：</label>
        <div class="col-xs-12 col-sm-10">
            <div class="row">
                <input  id="prod_sn" class="form-control col-sm-5" name="prod_stock_waring" type="text" value="{{$prodList['prod_stock_waring']}}" placeholder="">
                <span class="col-sm-7 color-6A6969"> 商品库存小于等于，设置的最低预警值时会提示商家。1000以内整数，0为不报警 </span>
            </div>
            <span class="msg-box" style="position:static;" for="prod_stock_waring"></span>
        </div>
    </div>
    <div class="form-group row form-item">
        <label style=" font-weight: normal" for="prod_brand_id" class="control-label col-xs-12 col-sm-2 pgc-font">
            商品品牌：</label>
        <div class="col-xs-12 col-sm-10">
            <div class="row">
                <select  class="form-control col-sm-5"  id="prod_brand_id" name="prod_brand_id" class="form-control" >
                    <option value="">无</option>
                    @foreach ($brandList as $k=>$v)
                        <option value='{{$v['brand_id']}}' @if($v['brand_id'] == $prodList['prod_brand_id'])selected @endif >{{$v['brand_name']}}</option>
                    @endforeach

                </select>
                {{--<span class="col-sm-7 color-6A6969" style="color: #007bff!important;"> 添加</span>--}}
            </div>
            <span class="msg-box" style="position:static;" for="prod_brand_id"></span>
        </div>
    </div>
    <div class="form-group row form-item">
        <label style=" font-weight: normal" for="prod_photos" class="control-label col-xs-12 col-sm-2 pgc-font">
            商品图片：</label>
        <div class="col-xs-12 col-sm-10">
            <div class="row">
                <div style="padding-left: 0;min-height: 108px" class="col-sm-5">
                    @component('component/image_upload',['name'=>'prod_photos','uploader'=>'uploader1','num'=>5,'value'=>$prodList['photo_str'],'direction'=>1,'browse_btn'=>'form-avatar','content_class'=>'background','img_format'=>'jpg,jpeg,png',{{--'rule'=>'相册:required'--}}])
                    @endcomponent
                </div>
                <span class="col-sm-7 color-6A6969"  style="margin-top: 3px">商品主图最多5张。尺寸：800 * 800PX，格式：jpg/jpeg/png/gif，大小：3M以内每张。</span>
            </div>
            <span class="msg-box" style="position:static;" for="prod_photos"></span>
        </div>
    </div>
    {{-- <div class="form-group row form-item">
         <label style=" font-weight: normal" for="prod_photos" class="control-label col-xs-12 col-sm-2 pgc-font">
             主图视频：</label>
         <div class="col-xs-12 col-sm-10">
             <div class="row">
                 <div style="padding-left: 0;height: 108px" class="col-sm-5">
                     @component('component/image_upload',['name'=>'prod_photos','uploader'=>'uploader1','num'=>5,'value'=>'','direction'=>1,'browse_btn'=>'form-avatar','content_class'=>'background','img_format'=>'jpg,jpeg,png','img_size'=>'300kb','rule'=>'相册:required'])
                     @endcomponent
                 </div>
                 <span class="col-sm-7 color-6A6969"  style="margin-top: 3px">商品主图最多5张。尺寸：800 * 800PX，格式：jpg/jpeg/png/gif，大小：3M以内每张。</span>
             </div>
             <span class="msg-box" style="position:static;" for="prod_photos"></span>
         </div>
     </div>--}}
    <div class="form-group row form-item">
        <label style=" font-weight: normal" for="prod_express_fee" class="control-label col-xs-12 col-sm-2 pgc-font">
            商品运费：</label>
        <div class="col-xs-12 col-sm-10">
            <div class="row">
                <div class="col-sm-2" style="line-height: 30px;">

                    @component('component/radio',['radio'=>['1'=>'按固定运费'],'default_key'=>$prodList['prod_express_type'],'name'=>'prod_express_type'])
                    @endcomponent
                </div>


                <input  id="prod_express_fee" class="form-control col-sm-3" name="prod_express_fee" @if($prodList['prod_express_type'] == 2)style="display: none" @endif type="text" value="{{$prodList['prod_express_fee']}}" placeholder="">
                <span class="col-sm-7 color-6A6969 express_type_span" @if($prodList['prod_express_type'] == 2)style="display: none" @endif>按固定运费表示每订单收取输入运费金额，如为包邮可设置为0元 </span>
            </div>
            <span class="msg-box" style="position:static;" for="prod_express_fee"></span>
        </div>
    </div>
    <div class="form-group row form-item">
        <label style=" font-weight: normal" for="prod_express_tpl_id" class="control-label col-xs-12 col-sm-2 pgc-font">
        </label>
        <div class="col-xs-12 col-sm-10">
            <div class="row">
                <div class="col-sm-2" style="line-height: 30px;">

                    @component('component/radio',['radio'=>['2'=>'按运费模板'],'default_key'=>$prodList['prod_express_type'],'name'=>'prod_express_type'])
                    @endcomponent
                </div>
                <select  class="form-control col-sm-3" id="prod_express_tpl_id" name="prod_express_tpl_id" class="form-control" @if($prodList['prod_express_type'] == 1)style="display: none" @endif>
                    <option value="">无</option>

                    @foreach ($deliveryList as $k=>$v)
                        <option value='{{$v['del_temp_id']}}' @if($prodList['prod_express_tpl_id'] == $v['del_temp_id']) selected @endif >{{$v['del_temp_name']}}</option>
                    @endforeach

                </select>
                <span class="col-sm-7 color-6A6969 express_tpl_id" style="color: #007bff!important;display: none">  {{--<span>添加</span>&nbsp;&nbsp;<span>编辑</span>--}}</span>
            </div>
            <span class="msg-box" style="   position:static;" for="prod_express_tpl_id"></span>
        </div>
    </div>

    <div class="d_well d_well-sm" style="margin-bottom: 1px">
        <span class="d_well-img-c"><i class="fa fa-dot-circle-o"></i></span>
        <span class="d_well-font">商品详细信息</span>
    </div>

    <div class="form-group row form-item">
        <div class="good-detail-port">
            <div class="gdp-pc c_u_new_add c_u_new_add_active" data-value = "pc">
                <i class="fa fa fa-desktop"></i>
                PC端
            </div>
            <div class=" gdp-mob c_u_new_add " data-value = "mob">
                <i class="fa fa fa-desktop"></i>
                移动端
            </div>
            <input  id="good-detail" name="good-detail-type" type="hidden" value="" placeholder="">
        </div>


    </div>
    <script id="pc_container" name="prod_details_pc" type="text/plain" style="width:100%;height:500px;">{!! $prodList['prod_details_pc'] !!}</script>
    <script id="mob_container" name="prod_details_mobile" type="text/plain" style="width:100%;height:500px;display:none">{!! $prodList['prod_details_mobile'] !!}</script>

    <div class="d_well d_well-sm" style="margin-bottom: 1px">
        <span class="d_well-img-c"><i class="fa fa-dot-circle-o"></i></span>
        <span class="d_well-font">商品运营信息</span>
    </div>
    <div class="form-group row form-item">
        <label style=" font-weight: normal" for="prod_return_flag" class="control-label col-xs-12 col-sm-2 pgc-font">
            退货标识：</label>
        <div class="col-xs-12 col-sm-10">
            <div class="row">
                <input type="hidden" name="prod_return_flag" value="">
                <label for="return_flag"></label>
                <select  class="selectpicker form-control col-sm-5 return_flag bla bla bli" title="无" multiple data-live-search="false"  id="return_flag" name="return_flag" >
                    @foreach ($returnGoodsArr as $k=>$v)
                        <option value='{{$k}}' @if(in_array($k,$prodList['return_arr'])) selected @endif>{{$v}}</option>
                    @endforeach

                </select>
                <span class="col-sm-7 color-6A6969"></span>
            </div>
            <span class="msg-box" style="position:static;" for="prod_return_flag"></span>
        </div>
    </div>
    <div class="form-group row form-item">
        <label style=" font-weight: normal" for="prod_return_flag" class="control-label col-xs-12 col-sm-2 pgc-font">
            售后服务：</label>
        <div class="col-xs-12 col-sm-10">
            <div class="row">
            <input type="hidden" name="prod_aftersale_flag" value="">
            <label for="return_flag"></label>
            <select  class="selectpicker form-control col-sm-5 aftersale_flag bla bla bli" title="无" multiple data-live-search="false"  id="aftersale_flag" name="aftersale_flag" >
                @foreach ($afterSaleArr as $k=>$v)
                    <option value='{{$k}}' @if(in_array($k,$prodList['aftersale_arr'])) selected @endif>{{$v}}</option>
                @endforeach

            </select>
                <span class="col-sm-7 color-6A6969"></span>
            </div>
            <span class="msg-box" style="position:static;" for="prod_return_flag"></span>
        </div>
    </div>
    <div class="form-group row form-item">
        <label style=" font-weight: normal" for="prod_comment_flag" class="control-label col-xs-12 col-sm-2 pgc-font">
            商品评论标签：</label>
        <div class="col-xs-12 col-sm-10">
            <div class="row">
            <input type="hidden" name="prod_comment_flag" value="">
            <label for="comment_flag"></label>
            <select  class="selectpicker form-control col-sm-5 comment_flag bla bla bli" title="无" multiple data-live-search="false"  id="comment_flag" name="comment_flag" >
                @foreach ($goodsLabelArr as $k=>$v)
                    <option value='{{$k}}' @if(in_array($k,$prodList['comment_arr'])) selected @endif>{{$v}}</option>
                @endforeach

            </select>
                <span class="col-sm-7 color-6A6969"></span>
            </div>
            <span class="msg-box" style="position:static;" for="prod_comment_flag"></span>
        </div>
    </div>

    <div class="form-group row form-item">
        <label style=" font-weight: normal" for="prod_onsale_status" class="control-label col-xs-12 col-sm-2 pgc-font">
            上/下架状态：</label>
        <div class="col-xs-12 col-sm-10">
            <div class="row" style="line-height: 30px">
                @component('component/radio',['radio'=>['1'=>'立即上架','0'=>'立即下架'],'default_key'=>$prodList['prod_onsale_status'],'name'=>'prod_onsale_status'])
                @endcomponent
            </div>
            <span class="msg-box" style="position:static;" for="prod_onsale_status"></span>
        </div>
    </div>
    <div class="form-group row form-item">
        <label style=" font-weight: normal" for="prod_price_type" class="control-label col-xs-12 col-sm-2 pgc-font">
            定价方式：</label>
        <div class="col-xs-12 col-sm-10">
            <div class="row">
                <input type="hidden" name="prod_price_type" class="prod_price_type" value="{{$prodList['prod_price_type']}}">
                <div class="col-sm-5" style="padding-right: 0;padding-left: 0;line-height: 30px">
                    @component('component/radio',['radio'=>['1'=>'SPU定价标准化商品（Standard Product Unit）','2'=>'SKU商品属性（Stock Keeping Unit）定价'],'default_key'=>$prodList['prod_price_type'],'name'=>'prod_price_type','is_disabled'=>1])
                    @endcomponent
                </div>



                <span class=" color-6A6969">SPU方式请在通用信息中设置一口价；SKU方式请在下一步定价。 </span>
            </div>
            <span class="msg-box" style="position:static;" for="prod_price_type"></span>
        </div>
    </div>
{{--    <div class="form-group row form-item">
        <label style=" font-weight: normal" for="prod_price_type" class="control-label col-xs-12 col-sm-2 pgc-font">
            加入推荐：</label>
        <div class="col-xs-12 col-sm-10">
            <div class="row">

                <div class="col-sm-5" style="line-height: 30px;">
                    @component('component/checkbox',['checkbox'=>['精品','新品','热销'],'name'=>['1','2','3'],'custom_class'=>'','right_distance' => '30'])
                    @endcomponent
                </div>



                <span class=" color-6A6969 col-sm-7"> </span>
            </div>
            <span class="msg-box" style="position:static;" for="prod_price_type"></span>
        </div>
    </div>
    <div class="form-group row form-item">
        <label style=" font-weight: normal" for="prod_price_type" class="control-label col-xs-12 col-sm-2 pgc-font">
            店铺推荐：</label>
        <div class="col-xs-12 col-sm-10">
            <div class="row">

                <div class="col-sm-5" style="line-height: 30px;">
                    @component('component/checkbox',['checkbox'=>['精品','新品','热销'],'name'=>['1','2','3'],'custom_class'=>'','right_distance' => '30'])
                    @endcomponent
                </div>



                <span class=" color-6A6969 col-sm-7"></span>
            </div>
            <span class="msg-box" style="position:static;" for="prod_price_type"></span>
        </div>
    </div>--}}
    <div class="form-group row form-item">
        <label style=" font-weight: normal" for="prod_price_type" class="control-label col-xs-12 col-sm-2 pgc-font">
            能否作为普通商品销售：</label>
        <div class="col-xs-12 col-sm-10">
            <div class="row">

                <div class="col-sm-5" style="padding-right: 0;padding-left: 0;line-height: 30px">
                    @component('component/radio',['radio'=>['1'=>'是','0'=>'否'],'default_key'=>$prodList['prod_onsale_issingle'],'name'=>'prod_onsale_issingle'])
                    @endcomponent
                </div>



                <span class="col-sm-7 color-6A6969">默认为普通商品，如果设置为否，则本商品只能作为配件、赠品配套购买，不能直接购买。 </span>
            </div>
            <span class="msg-box" style="position:static;" for="prod_price_type"></span>
        </div>
    </div>
    <div class="form-group row form-item">
        <label style=" font-weight: normal" for="prod_price_type" class="control-label col-xs-12 col-sm-2 pgc-font">
            <span style="color:red">*</span> 销售渠道设置：</label>
        <div class="col-xs-12 col-sm-10">
            <div class="row">
                @php $i = 1; @endphp
                @foreach($chanelList as $k=>$v)

                    @php if (in_array($v['cha_id'],$prodList['sale_channle'])) {$checked = $v['cha_id'];}else{$checked = 0;}  @endphp
                    <div class="col-sm-5 row" style="line-height: 30px">
                        <div class="col-sm-4">
                        @component('component/checkbox',['checkbox'=>[$v['cha_id']=>$v['cha_name']],'name'=>['sales_chanel[]'],'data_value'=>$v['cha_flag'] ,'checked'=> $checked,'custom_class'=>'','right_distance' => '30'])
                        @endcomponent
                        </div>
                        <div class="gpd-group col-sm-8" style="padding-left: 0 !important; @if(in_array($v['cha_id'],$prodList['sale_channle'])||(isset($prodList['prod_custom'][$v['cha_id']])&&$prodList['prod_custom'][$v['cha_id']]['is_exist'])) @else display: none; @endif">
                            <div class="row">
                                @foreach($v['customerList'] as $kk=>$vv)
                                    @php if (isset($prodList['prod_custom'][$v['cha_id']])&&in_array($vv['cust_lv_id'],$prodList['prod_custom'][$v['cha_id']]['prodCustLevelArr'])){$checked = $vv['cust_lv_id'];}else{$checked = 0;}  @endphp

                                    <div class="col-sm-3" style="white-space: nowrap;">
                                        @component('component/checkbox',['checkbox'=>[$vv['cust_lv_id'] => $vv['cust_lv_name']],'checked'=> $checked,'name'=>['sales_chanel_customer['.$v['cha_id'].'][]'],'custom_class'=>'','right_distance' => '0'])
                                        @endcomponent
                                    </div>

                                @endforeach
                            </div>

                        </div>
                    </div>

                        <span class="col-sm-7 color-6A6969">@if($i == 1)销售渠道必填，至少需填一个。渠道对应组别不开卖，则不勾选。@endif</span>
                    @php $i++; @endphp



                @endforeach



            </div>
            <span class="msg-box" style="position:static;" for="sales_chanel"></span>
        </div>

    </div>
    <div class="d_well d_well-sm" style="margin-bottom: 1px">
        <span class="d_well-img-c"><i class="fa fa-dot-circle-o"></i></span>
        <span class="d_well-font">供应商信息</span>
    </div>
    <div class="form-group row form-item">
        <label style=" font-weight: normal" for="prod_price_type" class="control-label col-xs-12 col-sm-2 pgc-font">
            供应商选择</label>
        <div class="col-xs-12 col-sm-10">
            <div class="row">
                @php $i = 1;@endphp

                @foreach($supplierList as $k=>$v)
                    @php  if (in_array($v['sup_id'],$prodList['supplier_channle'])) {$checked = $v['sup_id'];}else{$checked = 0;}  @endphp
                    <div class="col-sm-5 " style="line-height: 30px">
                        @component('component/checkbox',['checkbox'=>[$v['sup_id']=>$v['supplier_new_name']],'checked'=> $checked,'name'=>['supplier[]'],'custom_class'=>'','right_distance' => '30'])
                        @endcomponent
                        {{-- <div class="gpd-group">
                             @component('component/checkbox',['checkbox'=>['银牌组','铜牌组','金牌组'],'name'=>['1','2','3'],'custom_class'=>'','right_distance' => '30'])
                             @endcomponent
                         </div>--}}
                    </div>

                    <span class="col-sm-7 color-6A6969">@if($i == 1)@endif</span>
                    @php $i++; @endphp



                @endforeach



            </div>
            <span class="msg-box" style="position:static;" for="sales_chanel"></span>

        </div>
    </div>

    <div class="form-group row form-item" style="margin-bottom: 50px">
        <label style=" font-weight: normal" for="prod_price_type" class="control-label col-xs-12 col-sm-2 pgc-font">
            订单分配规则</label>
        <div class="col-xs-12 col-sm-10">
            <div class="col-sm-5" style="line-height: 30px">
                @component('component/checkbox',['checkbox'=>['1' => '按订单收货人地址自动匹配最近供应商'],'name'=>['prod_dist_rule'],'checked' => $prodList['prod_dist_rule'],'custom_class'=>'','right_distance' => '30'])
                @endcomponent
            </div>
        </div>
    </div>

    <div class="pgc-footer">

        <button class="btn btn-blue btn-edit-good-attr" style="color: #ffffff;vertical-align: top;">下一步,编辑商品属性</button>
        <button class="btn good-edit-cate-submit" style="background: #007bff;color: #ffffff;vertical-align: top;">完成,保存商品</button>
    </div>





</div>
