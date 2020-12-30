<!-- 订单发货视图 -->
<div class="Checkes" style="margin-top:20px;">
    <div class="d_well d_well-sm exc_form_title" style="margin-bottom: 10px;margin-top: 20px;">
        <span class="d_well-img-c"><i class="fa fa-archive"></i></span>
        <span class="d_well-font s_info_span">快递信息</span>
    </div>

        <div class="exc_form_title row exc_form_info_content">
            <div id="d_table" style="width: 100%;border-top: 1px solid #f5f5f5;">
                <table class="no-border-table">
                    <thead>
                    <tr class="table-head d_table-head" style="background: white;">
                        <td>快递公司</td>
                        <td>快递单号</td>
                        <td>快递费用</td>
                        <td>发货备注</td>
                        <td>发货时间</td>
                    </tr>
                    </thead>
                    <tbody class="tbl-content">
                    <tr>
                        <td class="d_o_attr_goods" style="width: 20%;">{{$data['delivery']['express_name']}}</td>
                        <td class="d_o_attr_goods" style="width: 20%;">{{$data['delivery']['delivery_code']}}</td>
                        <td class="d_o_attr d_o_attr_price" style="vertical-align:middle;width: 20%;">￥{{$data['delivery']['freight']}}</td>
                        <td class="d_o_attr d_o_attr_num" style="vertical-align:middle;width: 20%;">{{$data['delivery']['note']}}</td>
                        <td class="d_o_attr d_o_attr_weight" style="vertical-align:middle;width: 20%;">{{date('Y-m-d h:i:s',$data['delivery']['created_at'])}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

    <div class="d_well d_well-sm exc_form_title" style="margin-bottom: 10px;margin-top: 20px;">
        <span class="d_well-img-c"><img src="/images/clipboard.png" alt=""></span>
        <span class="d_well-font s_info_span">物流信息</span>
    </div>

    <div class="exc_form_title row exc_form_info_content" style="margin-bottom: 10px;">

        @if(!empty($data['logistics']))
            @for($i=count($data['logistics'])-1;$i>=0;$i--)
                @if($i == count($data['logistics'])-1)
                    <div class="row logistics_row">
                        <div class="col-md-2 logistics_left">
                            <div class="logistics_circle logistics_now" style="background-color: red;border: 1px solid red"></div>
                            <div style="position: absolute;right: 15px;background-color: red;color: white;padding: 0 5px 0 5px;">最新</div>
                        </div>
                        <div class="col-md-10 logistics_text">
                            <span style="color: red;">{{date('Y年n月d日 H:i:s',strtotime($data['logistics'][$i]['AcceptTime']))}}</span>
                            <span style="color: red;">{{$data['logistics'][$i]['AcceptStation']}}</span>
                        </div>
                    </div>
                @else
                    <div class="row logistics_row">
                        <div class="col-md-2 logistics_left">
                            <div class="logistics_circle"></div>
                        </div>
                        <div class="col-md-10 logistics_text">
                            <span>{{date('Y年n月d日 H:i:s',strtotime($data['logistics'][$i]['AcceptTime']))}}</span>
                            <span>{{$data['logistics'][$i]['AcceptStation']}}</span>
                        </div>
                    </div>
                @endif

                @if($i != 0)
                    <div class="row logistics_row">
                        <div class=" col-md-2 logistics_left">
                            <div class="logistics_line"></div>
                        </div>
                    </div>
                @endif
            @endfor
        @else
            <span class="logistics_null">暂无物流信息</span>
        @endif

    </div>
    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
        </div>
    </div>

</div>

<style>
    .d_table-head td{
        font-weight: normal;
    }
</style>
