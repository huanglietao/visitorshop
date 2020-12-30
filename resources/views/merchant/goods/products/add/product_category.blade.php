<!--  选择分类 start -->

<div class="good-category" >
    <div class="step-all">
        @component('component/step',['count' => 3,'now_step'=>'1','bottom_title' => ['1' => '选择商品分类','2' => '填写商品信息','3'=>'填写商品属性'],'default_color'=>'#bbb','active_color'=>'#259B24' ])
        @endcomponent
    </div>


    <div class="d_well d_well-sm" style="margin-bottom: 1px">
        <span class="d_well-img-c"><i class="fa fa-dot-circle-o"></i></span>
        <span class="d_well-font">选择商品分类</span>
    </div>

    <div class="p-goods-cate">
        <div class="pgc-top">
            <span class="pgc-font">历史商品类目搜索：</span>
            <input type="hidden" class="admin_id" value="{{$adminId}}">
            <input type="hidden" class="last_cate" value="">
            <input type="hidden" class="cate_change" value="0">
            <select class="pgc-history" name="" id="">
                <option value="">请选择</option>
            </select>
            <button class="btn btn-blue pgc-hist-add" style="color: #ffffff;vertical-align: top;">添加</button>
        </div>
        <div class="pgc-middle">
            <div class="pgc-m-child">
                <div class="pgc-m-c-main">
                    <input type="hidden" value="{{$personalPrint}}" class="personal_print">
                    <input type="hidden" value="{{$commercialPrint}}" class="commercial_print">
                    <input type=hidden value="" name="level_1_cate_id" class="level_1_cate_id">
                    <input type=hidden value="" name="level_2_cate_id" class="level_2_cate_id">
                    <input type=hidden value="" name="level_3_cate_id" class="level_3_cate_id">

                    <p class="pcg-m-c-p">一级类目</p>
                    <div class="pcg-m-c-search">
                        <input type="text" class="pcg-m-c-input good-category-level-1" placeholder="名称/拼音首字母" data-level="1">
                        <i class="fa fa-search pcg-m-c-fa search-level cate-level-1" data-level="1" ></i>
                    </div>
                    <div class="pcg-m-c-result result-1">
                        @forelse  ($firstCategory as $k=>$v)
                            <span class="pcg-r-span pgc-font" data-level='{{$v['cate_level']}}' data-id='{{$v['cate_id']}}'>{{$v['cate_name']}}</span>
                        @empty
                            <span class="pgc-font">'暂无分类'</span>
                        @endforelse
                    </div>

                </div>
            </div>
            <div class="pgc-right">
                <i class="fa fa-caret-right"></i>
            </div>

            <div class="pgc-m-child">
                <div class="pgc-m-c-main">
                    <p class="pcg-m-c-p">二级类目</p>
                    <div class="pcg-m-c-search">
                        <input type="text" class="pcg-m-c-input good-category-level-2" placeholder="名称">
                        <i class="fa fa-search pcg-m-c-fa search-level cate-level-2" data-level="2"></i>
                    </div>
                    <div class="pcg-m-c-result result-2">

                    </div>

                </div>
            </div>
            <div class="pgc-right">
                <i class="fa fa-caret-right"></i>
            </div>
            <div class="pgc-m-child" style="margin-right: 4%">
                <div class="pgc-m-c-main">
                    <p class="pcg-m-c-p">三级类目</p>
                    <div class="pcg-m-c-search">
                        <input type="text" class="pcg-m-c-input good-category-level-3" placeholder="名称">
                        <i class="fa fa-search pcg-m-c-fa search-level  cate-level-3" data-level="3"></i>
                    </div>
                    <div class="pcg-m-c-result result-3">

                    </div>

                </div>
            </div>

            {{--<div class="pgc-m-child">
                <div class="pgc-m-c-main">
                    <p class="pcg-m-c-p">品牌选择</p>
                    <div class="pcg-m-c-search">
                        <input type="text" class="pcg-m-c-input good-category-brand" placeholder="名称">
                        <i class="fa fa-search pcg-m-c-fa good-category-brand-i"></i>
                    </div>
                    <div class="pcg-m-c-result result-brand">
                        <span class="pcg-r-span pgc-font pcg-r-active-span" >印刷/商务印刷/个性定制/设计DIY</span>
                        <span class="pcg-r-span pgc-font">实物/衣/食/住/行</span>
                        <span class="pcg-r-span pgc-font">实物/衣/食/住/行</span>
                    </div>

                </div>
            </div>--}}
        </div>
        <div class="d_well d_well_select_text" style="margin-bottom: 50px">

            <span class="d_well-font">已选择商品类目:&nbsp</span>
            <span class="d_all_cate d_cate_color"></span>
            <span class="d_first_cate d_cate_color"></span>
            <span class="d_second_cate d_cate_color"></span>
            <span class="d_third_cate d_cate_color"></span>
           {{-- <span class="d_brand_cate d_cate_color"></span>--}}
        </div>

        <div class="pgc-footer">
            <button class="btn btn-blue btn-go-next-info" style="color: #ffffff;vertical-align: top;">下一步,填写商品信息</button>
        </div>

    </div>
</div>


<!--  选择分类 end -->
