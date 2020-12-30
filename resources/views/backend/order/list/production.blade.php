<!-- 提交生产视图 -->
<div class="Checkes" style="margin-top:20px;">
    <form class="form-horizontal" id="form-save" method="post" action="/order/production/{{$data[0]['ord_id']}}" autocomplete="off">
        @csrf
        <div class="d_well d_well-sm exc_form_title" style="margin-bottom: 10px;margin-top: 20px;">
            <span class="d_well-img-c"><i class="fa fa-archive"></i></span>
            <span class="d_well-font s_info_span">商品信息</span>
        </div>
        @foreach($data as $k=>$v)
            <div class="exc_form_title row exc_form_info_content">
                <div id="d_table" style="width: 100%;border-top: 1px solid #f5f5f5;">
                    <table class="no-border-table">
                        <thead>
                        <tr class="table-head d_table-head" style="background: white;">
                            <td>商品信息</td>
                            <td>作品信息</td>
                            <td>页数</td>
                            <td>单价</td>
                            <td>数量</td>
                            <td>小计</td>
                        </tr>
                        </thead>
                        <tbody class="tbl-content">
                        <tr>
                            <td class="d_o_attr_goods" style="width: 35%;">
                                <div class="d_o_works_info">
                                    <div class="d_o_works_img">
                                        <img src="{{$v['prod_main_thumb']}}">
                                    </div>
                                    <div class="d_o_works_detail">
                                        <p class="d_o_works_name">{{$v['prod_name']}}</p>
                                        <p class="d_o_works_pnum">商品货号：{{$v['prod_sku_sn']}}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="d_o_attr_goods" style="width: 35%;">
                                @if($v['prj_type'] == WORKS_FILE_TYPE_DIY)
                                    <div class="d_o_works_info">
                                        <div class="d_o_works_img">
                                            <img src="{{$v['prj_image']}}">
                                        </div>
                                        <div class="d_o_works_detail">
                                            <p class="d_o_works_name">{{$v['prj_name']}}</p>
                                            <p class="d_o_works_pnum" style="width: 100%;">作品号：{{$v['prj_sn']}}</p>
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td class="d_o_attr d_o_attr_price" style="vertical-align:middle;width: 6%;">{{$v['prod_pages']}}</td>
                            <td class="d_o_attr d_o_attr_num" style="vertical-align:middle;width: 9%;">￥{{$v['prod_sale_price']}}</td>
                            <td class="d_o_attr d_o_attr_weight" style="vertical-align:middle;width: 6%;">{{$v['prod_num']}}</td>
                            <td class="d_o_attr d_o_attr_count" style="vertical-align:middle;width: 9%;">￥{{$v['subtotal']}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </form>
    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button type="submit" class="btn btn-primary btn-3F51B5 btn-sure btn-submit">提交</button>
            &nbsp;&nbsp;&nbsp;&nbsp;
        </div>
    </div>

</div>

<style>
    .d_table-head td{
        font-weight: normal;
    }
</style>
