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


    {{--影像类商品才会出现 start--}}
    <div class="goods-image-category">
        <div class="form-group row form-item">
            <label style=" font-weight: normal" class="control-label col-xs-12 col-sm-2 pgc-font">
                商品规格：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input type="hidden" class="size_cate_id" value="">
                    <select  class="form-control col-sm-5" id="prod_size_id" name="prod_size_id" class="form-control" >

                        <option value="">无</option>

                    </select>
                    <span class="col-sm-7 control-label pgc-font" style="text-align: left!important;"><span style="color: #007bff!important;"  class="custom-product-size" data-area="['70%', '70%']" data-url="{{URL::asset('/goods/custom_products_size/form')}}" data-title = "规格详情">查看规格</span> &nbsp;&nbsp;&nbsp;个性定制影像商品必须绑定商品规格，否则编辑器无法正常工作。</span>
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

                        @component('component/radio',['radio'=>[0=>'不支持加减p(固定p)',1=>'支持加减p'],'default_key'=>0,'name'=>'prod_is_add_page'])
                        @endcomponent
                    </div>
                    <span class="col-sm-7 color-6A6969">支持加减P：用户制作作品时可在设置的P数范围内，按加P规则自行调整；不支持：用户制作时只能按上述P数属性选择对应P数。 </span>
                </div>
                <span class="msg-box" style="position:static;" for="prod_is_add_page"></span>
            </div>
        </div>
        <div class="form-group row form-item prod_add_page_range" style="display: none;">
            <label style=" font-weight: normal" for="prod_express_fee" class="control-label col-xs-12 col-sm-2 pgc-font">
            </label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div class="col-sm-1 pgc-font">
                        p数范围:
                    </div>
                    <div class="col-sm-1">
                        <input type="number"  name="prod_min_add_page" class="prod_min_add_page" value="" style="width: 100%">
                    </div>
                    <div class="col-sm-1" style="text-align: center">
                        -
                    </div>
                    <div class="col-sm-1">
                        <input type="number" name="prod_max_add_page" value="" style="width: 100%">
                    </div>
                    <div class="col-sm-1" style="text-align: center">

                    </div>

                    <span class="col-sm-7 color-6A6969">P数范围与当前商品装订工艺有密切关系，请正确设置。 </span>
                </div>
                <span class="msg-box" style="position:static;" for="prod_express_fee"></span>
            </div>
        </div>
        <div class="form-group row form-item prod_add_page" style="display: none">
            <label style=" font-weight: normal" for="prod_express_fee" class="control-label col-xs-12 col-sm-2 pgc-font">
            </label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div class="col-sm-1 pgc-font">
                        加减p规则数量:
                    </div>
                    <div class="col-sm-4">
                        <input type="number" name="prod_add_page" value="" class="prod_p_rule"   style="width: 100%" >
                    </div>

                    <span class="col-sm-7 color-6A6969">设置每次可加减p的p数 </span>
                </div>
                <span class="msg-box" style="position:static;" for="prod_express_fee"></span>
            </div>
        </div>
        <div class="form-group row form-item prod_spu_but" style="display: none">
            <label style=" font-weight: normal" for="prod_express_fee" class="control-label col-xs-12 col-sm-2 pgc-font">
            </label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <button class="btn btn-blue btn-spu-attr-add" style="color: #ffffff;vertical-align: top;">生成价格信息</button>
                </div>

            </div>
        </div>

    </div>
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
    <div class="form-group row form-item pro_sku_comb">
        <label style=" font-weight: normal" class="control-label col-xs-12 col-sm-2 pgc-font">
            商品属性:
        </label>
        <div class="col-xs-12 col-sm-10 product-sku"></div>
    </div>

    <div class="pa-table" style="margin-bottom: 50px"></div>
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
        <button class="btn btn-return-good-attr" style="vertical-align: top;background: #ffffff;color: #000000;border: 1px solid #007bff">上一步,填写商品信息</button>
        <button class="btn good-cate-submit" style="background: #007bff;color: #ffffff;vertical-align: top;">完成,发布商品</button>
    </div>


</div>
{{--填写商品属性 end--}}