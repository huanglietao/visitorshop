<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/suppliers/suppliers/costSave" onsubmit="return false;" autocomplete="off">
        <input type=hidden value="{{$row['sup_log_cos_id']}}" name="sup_log_cos_id" id="sup_log_cos_id">
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 供货商名称：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <span class="col-sm-5" style="padding-top: 0.5%;font-size: 16px;padding-left: 0">{{$sup['sup_name']}}</span>
                    <input type=hidden value="{{$sup['sup_id']}}" name="sup_id" id="sup_id">
                    </div>
                <span class="msg-box" style="position:static;" for="del_temp_name"></span>
            </div>
        </div>

        <div class="form-group row form-item" style="padding-bottom: 20px">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 快递公司：</label>
            <div class="col-xs-12 col-sm-10">
                <input  id="trans_name_list" hidden class="form-control col-sm-5" name="sup_log_cos_delivery_list" type="text" value="{{$row['sup_log_cos_delivery_list']}}"  placeholder="" data-rule="快递公司:required">
                @if(!$transport)  <span class="col-sm-7 color-6A6969" style="top: 7px;padding-left: 0;font-size: 14px"><a href="/delivery/exprerss">请先添加运送方式再进行配置。</a> </span> @endif
                @foreach($transport as $k=>$v)
                    @if(!isset($row['sup_log_cos_delivery_list']) || str_contains($row['sup_log_cos_delivery_list'],$v['express_id'])==false)
                        <div class="row check" style="margin-top: 7px">
                            @component('component/checkbox',['checkbox'=>[$v['express_name']],'name'=>[$v['express_id']],'custom_class'=>'checkedres'])
                            @endcomponent
                        </div>
                        <div id ="{{$v['express_id']}}">
                            <div class="col-xs-12 col-sm-10">
                                <div style="height:auto;padding-bottom: 1%;margin-top: 1%;display: none" class="first{{$v['express_id']}}">
                                    <div style="border: lightgrey 1px solid;padding: 1% 1% 1% 2%;display:flex;justify-content:space-between;flex-direction: row">
                                        <div style="font-size:14px;">默认运费:</div>
                                        <div style="width: 75%">
                                            <div style="height: auto;display: flex;flex-wrap: nowrap;margin-bottom: 2%">
                                                <span style="margin-left: 2%;"><span style="color:red">*</span>首重：</span>
                                                <input style="width: 25%;display: inline-block" class="form-control" value="" name="default{{$v['express_id']}}[default_first_weight]" />
                                                <span style="margin-left: 1%;">KG</span>
                                                <span style="margin-left: 5%;"><span style="color:red">*</span>首重费用：</span>
                                                <input style="width: 25%;display: inline-block" class="form-control" name="default{{$v['express_id']}}[default_first_price]"/>
                                                <span style="margin-left: 1%;">元</span>
                                            </div>
                                            <div style="height: auto;display: flex;flex-wrap: nowrap;">
                                                <span style="margin-left: 2%;"><span style="color:red">*</span>续重：</span>
                                                <input style="width: 25%;display: inline-block" class="form-control" name="default{{$v['express_id']}}[default_continuation_weight]"/>
                                                <span style="margin-left: 1%;">KG</span>
                                                <span style="margin-left: 5%"><span style="color:red">*</span>续重费用：</span>
                                                <input style="width: 25%;display: inline-block" class="form-control" name="default{{$v['express_id']}}[default_continuation_price]"/>
                                                <span style="margin-left: 1%;">元</span>
                                            </div>
                                        </div>
                                        <div style="height:auto;">
                                            <a href="javascript:void(0);" onclick="deleteItem(this,{{$v['express_id']}})" class="btn btn-xs btn-danger btn-delone" title=关闭"><i class="fa fa-window-close"></i></a>
                                        </div>
                                    </div>
                                    <div style="height: auto;border: lightgrey 1px solid;border-top:none">
                                        <table class="no-border-table">
                                            <thead>
                                            <tr class="table-head">
                                                <td>首重(KG)</td>
                                                <td>首重费用(元)</td>
                                                <td>续重(KG)</td>
                                                <td>续重费用(元)</td>
                                                <td>配送地区 (
                                                    <span style="color: #0b97c4;cursor:pointer;text-decoration: underline" onclick="addTR(this,{{$v['express_id']}})">添加配送地区</span>
                                                    )
                                                </td>
                                                <td>操作</td>
                                            </tr>
                                            </thead>
                                            <input type="text" name="tr_num{{$v['express_id']}}" value="1" hidden/>
                                            <tbody class="tbl-content"  id="delivery_table{{$v['express_id']}}">
                                            {{--<input type="text" name="price{{$v['delivery_id']}}1" id="areas_list{{$v['delivery_id']}}" hidden/>--}}
                                            <input type="text" name="area{{$v['express_id']}}1" id="arealist{{$v['express_id']}}" hidden/>
                                            <tr id="del_tr{{$v['express_id']}}">
                                                <td style="width:12%;">
                                                    <input style="width: 80%;display: inline-block" class="form-control" name="area{{$v['express_id']}}[first_weight][]" data-name="area{{$v['express_id']}}[first_weight]"/>
                                                </td>
                                                <td style="width:12%">
                                                    <input style="width: 80%;display: inline-block" class="form-control" name="area{{$v['express_id']}}[first_price][]" data-name="area{{$v['express_id']}}[first_price]"/>
                                                </td>
                                                <td style="width:12%">
                                                    <input style="width: 80%;display: inline-block" class="form-control" name="area{{$v['express_id']}}[continuation_weight][]" data-name="area{{$v['express_id']}}[continuation_weight]"/>
                                                </td>
                                                <td style="width:12%">
                                                    <input style="width: 80%;display: inline-block" class="form-control" name="area{{$v['express_id']}}[continuation_price][]" data-name="area{{$v['express_id']}}[continuation_price]"/>
                                                </td>
                                                <td style="width: 40%">
                                                    @component('component.areas')@endcomponent
                                                </td>
                                                <td style="width:12%;text-align: center">
                                                    <div style="color:green;cursor:pointer;font-size: 14px;" onclick="areaText(this,{{$v['express_id']}})">添加</div>
                                                    <div style="color:red;cursor:pointer;font-size: 14px" onclick="delTR(this,{{$v['express_id']}})">删除</div>
                                                </td>
                                            </tr>
                                            <tr id="add_area{{$v['express_id']}}">
                                                <td colspan="6" style="text-align: left;"> </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif(str_contains($row['sup_log_cos_delivery_list'],$v['express_id'])==true)
                        <div class="row check" style="margin-top: 7px">
                            @component('component/checkbox',['checkbox'=>[$v['express_name']],'name'=>[$v['express_id']],'custom_class'=>'checkedres','checked'=>"0"])
                            @endcomponent
                        </div>
                        <div id ="{{$v['express_id']}}">
                            <div class="col-xs-12 col-sm-10">
                                <div style="height:auto;padding-bottom: 1%;margin-top: 1%;" class="first{{$v['express_id']}}">
                                    <div style="border: lightgrey 1px solid;padding: 1% 1% 1% 2%;display:flex;justify-content:space-between;flex-direction: row">
                                        <div style="font-size:14px;">默认运费:</div>
                                        <div style="width:75%;">
                                            <div style="height: auto;display: flex;flex-wrap: nowrap;margin-bottom: 2%">
                                                <span style="margin-left: 2%;"><span style="color:red">*</span>首重：</span>
                                                <input style="width: 25%;display: inline-block" class="form-control" value={{$area_fare[$v['express_id']][0][0]}} name="default{{$v['express_id']}}[default_first_weight]" />
                                                <span style="margin-left: 1%;">KG</span>
                                                <span style="margin-left: 5%"><span style="color:red">*</span>首重费用：</span>
                                                <input style="width: 25%;display: inline-block" class="form-control" value={{$area_fare[$v['express_id']][0][1]}} name="default{{$v['express_id']}}[default_first_price]"/>
                                                <span style="margin-left: 1%;">元</span>
                                            </div>
                                            <div style="height: auto;display: flex;flex-wrap: nowrap;">
                                                <span style="margin-left: 2%;"><span style="color:red">*</span>续重：</span>
                                                <input style="width: 25%;display: inline-block" class="form-control" value={{$area_fare[$v['express_id']][0][2]}} name="default{{$v['express_id']}}[default_continuation_weight]"/>
                                                <span style="margin-left: 1%;">KG</span>
                                                <span style="margin-left: 5%"><span style="color:red">*</span>续重费用：</span>
                                                <input style="width: 25%;display: inline-block" class="form-control" value={{$area_fare[$v['express_id']][0][3]}} name="default{{$v['express_id']}}[default_continuation_price]"/>
                                                <span style="margin-left: 1%;">元</span>
                                            </div>
                                        </div>
                                        <div style="height:auto;">
                                            <a href="javascript:void(0);" onclick="deleteItem(this,{{$v['express_id']}})" class="btn btn-xs btn-danger btn-delone" title=关闭"><i class="fa fa-window-close"></i></a>
                                        </div>
                                    </div>
                                    <div style="width: 100%;height: auto;border: lightgrey 1px solid;border-top:none">
                                        <table class="no-border-table">
                                            <thead>
                                            <tr class="table-head">
                                                <td>首重(KG)</td>
                                                <td>首重费用(元)</td>
                                                <td>续重(KG)</td>
                                                <td>续重费用(元)</td>
                                                <td>配送地区 (
                                                    <span style="color: #0b97c4;cursor:pointer;text-decoration: underline" onclick="addTR(this,{{$v['express_id']}})">添加配送地区</span>
                                                    )
                                                </td>
                                                <td>操作</td>
                                            </tr>
                                            </thead>
                                            @if(!$area_fare[$v['express_id']][1])
                                                <input type="text" name="tr_num{{$v['express_id']}}" value="1" hidden/>
                                                <tbody class="tbl-content"  id="delivery_table{{$v['express_id']}}">
                                                {{--<input type="text" name="price{{$v['delivery_id']}}1" id="areas_list{{$v['delivery_id']}}" hidden/>--}}
                                                <input type="text" name="area{{$v['express_id']}}1" id="arealist{{$v['express_id']}}" hidden/>
                                                <tr id="del_tr{{$v['express_id']}}">
                                                    <td style="width:12%;">
                                                        <input style="width: 80%;display: inline-block" class="form-control" name="area{{$v['express_id']}}[first_weight][]" data-name="area{{$v['express_id']}}[first_weight]"/>
                                                    </td>
                                                    <td style="width:12%">
                                                        <input style="width: 80%;display: inline-block" class="form-control" name="area{{$v['express_id']}}[first_price][]" data-name="area{{$v['express_id']}}[first_price]"/>
                                                    </td>
                                                    <td style="width:12%">
                                                        <input style="width: 80%;display: inline-block" class="form-control" name="area{{$v['express_id']}}[continuation_weight][]" data-name="area{{$v['express_id']}}[continuation_weight]"/>
                                                    </td>
                                                    <td style="width:12%">
                                                        <input style="width: 80%;display: inline-block" class="form-control" name="area{{$v['express_id']}}[continuation_price][]" data-name="area{{$v['express_id']}}[continuation_price]"/>
                                                    </td>
                                                    <td style="width: 40%">
                                                        @component('component.areas')@endcomponent
                                                    </td>
                                                    <td style="width:12%">
                                                        <div style="color:green;cursor:pointer;font-size: 14px" onclick="areaText(this,{{$v['express_id']}})">添加</div>
                                                        <div style="color:red;cursor:pointer;font-size: 14px" onclick="delTR(this,{{$v['express_id']}})">删除</div>
                                                    </td>
                                                </tr>
                                                <tr id="add_area{{$v['express_id']}}">
                                                    <td colspan="6" style="text-align: left;"> </td>
                                                </tr>
                                                </tbody>
                                            @elseif($area_fare[$v['express_id']][1])
                                                <input type="text" name="tr_num{{$v['express_id']}}" value="{{$area_fare['tr_num'.$v['express_id']]}}"  hidden/>
                                                <tbody class="tbl-content"  id="delivery_table{{$v['express_id']}}">
                                                @foreach($area_fare[$v['express_id']][1] as $index=>$value)
                                                    {{--<input type="text" name="price{{$v['delivery_id']}}{{$index+1}}"  value="{{$input_price['price'.$v['delivery_id']][$index]}}" hidden/>--}}
                                                    <input type="text" name="area{{$v['express_id']}}{{$index+1}}"  value="{{$input_area['area'.$v['express_id']][$index]}}" hidden/>
                                                    <tr id="del_tr{{$v['express_id']}}">
                                                        <td style="width:12%;">
                                                            <input style="width: 80%;display: inline-block" class="form-control" value=@if($value[0][0]) {{$value[0][0]}} @else '' @endif name="area{{$v['express_id']}}[first_weight][]" data-name="area{{$v['express_id']}}[first_weight]"/>
                                                        </td>
                                                        <td style="width:12%">
                                                            <input style="width: 80%;display: inline-block" class="form-control" value=@if($value[0][1]) {{$value[0][1]}} @else '' @endif name="area{{$v['express_id']}}[first_price][]" data-name="area{{$v['express_id']}}[first_price]"/>
                                                        </td>
                                                        <td style="width:12%">
                                                            <input style="width: 80%;display: inline-block" class="form-control" value=@if($value[0][2]) {{$value[0][2]}} @else '' @endif name="area{{$v['express_id']}}[continuation_weight][]" data-name="area{{$v['express_id']}}[continuation_weight]"/>
                                                        </td>
                                                        <td style="width:12%">
                                                            <input style="width: 80%;display: inline-block" class="form-control" value=@if($value[0][3]) {{$value[0][3]}} @else '' @endif name="area{{$v['express_id']}}[continuation_price][]" data-name="area{{$v['express_id']}}[continuation_price]"/>
                                                        </td>
                                                        <td style="width: 40%">
                                                            @component('component.areas')@endcomponent
                                                        </td>
                                                        <td style="width:12%">
                                                            <div style="color:green;cursor:pointer;font-size: 14px" onclick="areaText(this,{{$v['express_id']}})">添加</div>
                                                            <div style="color:red;cursor:pointer;font-size: 14px" onclick="delTR(this,{{$v['express_id']}})">删除</div>
                                                        </td>
                                                    </tr>
                                                    <tr id="add_area{{$v['express_id']}}">
                                                        <td colspan="6" style="text-align: left;">
                                                            @foreach($area_name_list['name'.$v['express_id']][$index] as $ka=>$va)
                                                                @foreach($va as $k_ka => $v_va)
                                                                    <div class='ys-p  ys-no ys-click' data-id="{{$v_va['area_id']}}">
                                                                        {{$v_va['area_name']}}
                                                                        <div style="display: inline-block"><i class='fa fa-trash fa-lg icon' aria-hidden='true' onclick='choose(this,{{$v['express_id']}})'></i></div>
                                                                    </div>
                                                                @endforeach
                                                            @endforeach
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            @endif
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endif
                @endforeach
                <span class="msg-box" style="position:static;" for="trans_name_list"></span>
            </div>

        </div>

    </form>
    <div class="form-group layer-footer">
        <label class="control-label col-xs-12 col-sm-2"></label>
        <div class="col-xs-12 col-sm-8">
            <button type="submit" class="btn btn-primary btn-3F51B5 btn-sure btn-submit">确定</button>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <button type="reset" class="btn btn-write btn-reset">重置</button>
        </div>
    </div>
</div>



<style>
    .btn-checkbox + label:before{
        top:0px
    }
    .btn-checkbox + label:after{
        top:4px
    }
    .first{
        display: none;
    }
    .ys-no
    {
        border: 1px solid #bbbbbb;
        padding: 0 1%;
    }
    .ys-p
    {
        cursor: pointer;
        margin-top: 1%;
        margin-bottom: 1%;
        /*width: 13%;*/
        height: 28px;
        padding-top: 6px;
        text-align: center;
        display: inline-block;
        margin-left: 8px;
    }
    .ys-click
    {
        border: 1px solid #01C1DE;
        background: #E6F9FD;
    }
    .icon {
        margin-left: 6%;
        color: red;
    }

    .areas-one{
        width: 100%;
    }
    .areas-province,.areas-city,.areas-area{
        height: 30px !important;
        width: 30% ;
    }

</style>
