<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/suppliers/suppliers/save" onsubmit="return false;" autocomplete="off">
        <input type=hidden value="{{$row['sup_id']}}" name="sup_id" id="id">
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 供应商名称：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="sup_name" class="form-control col-sm-5" name="sup_name" type="text" value="{{$row['sup_name']}}" placeholder="" data-rule="供应商名称:required">
                    <span class="col-sm-7 color-6A6969"> 请填写供应商的名称。</span>
                </div>
                <span class="msg-box" style="position:static;" for="sup_name"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 供应商编号：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="sup_code" class="form-control col-sm-5" name="sup_code" type="text" value="{{$row['sup_code']}}" placeholder="" data-rule="供应商编号:required">
                    <span class="col-sm-7 color-6A6969"> 请给供应商填写编号，如名称拼音的首字母。</span>
                </div>
                <span class="msg-box" style="position:static;" for="sup_code"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 联系人：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="sup_contacts" class="form-control col-sm-5" name="sup_contacts" type="text" value="{{$row['sup_contacts']}}" placeholder="" data-rule="联系人:required">
                    <span class="col-sm-7 color-6A6969"> 请填写供应商的联系人。</span>
                </div>
                <span class="msg-box" style="position:static;" for="sup_contacts"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 电话/手机：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="sup_telephone" class="form-control col-sm-5" name="sup_telephone" type="text" value="{{$row['sup_telephone']}}" placeholder="" data-rule="电话/手机:required">
                    <span class="col-sm-7 color-6A6969"> 请填写联系人的联系方式。</span>
                </div>
                <span class="msg-box" style="position:static;" for="sup_telephone"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 所在区域：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <select  class="form-control col-sm-5" id="sup_region" name="sup_region" data-rule="所在区域:required">
                        @foreach($sup_list['sup_region'] as $key=>$value)
                            <option value='{{$key}}' @if($row['sup_region']==$key) selected @endif>{{$value}}</option>
                        @endforeach
                    </select>
                    <span class="col-sm-7 color-6A6969"> 请选择供应商所处的区域。</span>
                </div>
                <span class="msg-box" style="position:static;" for="sup_region"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 地区：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div style="padding-left: 0;padding-right: 0" class="col-sm-5">
                        @component('component.areas',['province_value'=>$row['sup_province'],'city_value'=>$row['sup_city'],'areas_value'=>$row['sup_area']])@endcomponent
                    </div>
                    <span class="col-sm-7 color-6A6969"> 请选择供应商所处的具体区域。</span>
                </div>
                <span class="msg-box" style="position:static;" for="sup_province"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 供应商类型：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div style="display: inline-block;padding-top: 5px;padding-left: 0" class="col-sm-5">
                        @component('component/radio',['radio'=>['1'=>'主力','2'=>'备选'],'name'=>'sup_type','default_key'=>$row['sup_type']??1])
                        @endcomponent
                    </div>
                    <span class="col-sm-7 color-6A6969" style="padding-top: 1%"> 同一市区有且只能有一个主力。</span>
                </div>
                <span class="msg-box" style="position:static;" for="sup_type"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">供应商产能：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="sup_capacity" class="form-control col-sm-5" name="sup_capacity" type="text" value="{{$row['sup_capacity']}}" placeholder="" >
                    <span class="col-sm-7 color-6A6969"> 请填写日平均产能。</span>
                </div>
                <span class="msg-box" style="position:static;" for="sup_capacity"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">订单分配量：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="sup_allocation_quantity" class="form-control col-sm-5" name="sup_allocation_quantity" type="text" value="{{$row['sup_allocation_quantity']}}" placeholder="" >
                    <span class="col-sm-7 color-6A6969"> 推送订单数量的上限值。</span>
                </div>
                <span class="msg-box" style="position:static;" for="sup_allocation_quantity"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">产能单位：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="sup_capacity_unit" class="form-control col-sm-5" name="sup_capacity_unit" type="text" value="{{$row['sup_capacity_unit']}}" placeholder="" >
                    <span class="col-sm-7 color-6A6969"> 请为产能定义一个单位，如:笔。</span>
                </div>
                <span class="msg-box" style="position:static;" for="sup_capacity_unit"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">排序：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="sort" class="form-control col-sm-5" name="sort" type="text" value="{{$row['sort']}}" placeholder="">
                    <span class="col-sm-7 color-6A6969"> 0~99，不填写默认为0。</span>
                </div>
                <span class="msg-box" style="position:static;" for="sort"></span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">服务区域：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div style="border: lightgrey 1px solid;height:250px;" class="col-sm-5" id="area">
                        <div style="width: 100%;height: auto;padding: 2% 0">
                            <input type="hidden" name="sup_service_area" value="{{$row['sup_service_area']}}" />
                            <div style="width: 70%;height: auto;display: inline-block;">
                                @component('component.areas',['province_name'=>'area_province','city_name'=>'area_city','district_name'=>'area_district'])@endcomponent
                            </div>
                            <span style="color: #0d95e8;padding-left: 3%;margin:-4px 0 0 5px;"> <a onclick="areaAdd(this)" href="javascript:void(0);" class="btn btn-success"> <i class="fa fa-plus"></i>添加</a> </span>
                        </div>
                        <div style="width: 100%;border-top: lightgrey 1px solid;" class="areaname">
                            @if(!empty($sup_list['sup_service'][0]))
                                @foreach($sup_list['sup_service'] as $k=>$v)
                                    <div class='ys-p  ys-no ys-click' data-id='{{$v[0]['area_id']}}'>
                                        {{$v[0]['area_name']}}
                                        <div style="display: inline-block"><i class='fa fa-trash fa-lg icon' aria-hidden='true' onclick='chooses(this)'></i></div>
                                    </div>
                                @endforeach
                            @else
                            @endif
                        </div>
                    </div>
                    <span class="col-sm-7 color-6A6969"> 如果没有指定区域则默认支持全国。</span>
                </div>
                <span class="msg-box" style="position:static;" for="sort"></span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 状态：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div style="display: inline-block;padding-top: 5px;padding-left: 0" class="col-sm-5">
                        @component('component/radio',['radio'=>['1'=>'启用','2'=>'禁用'],'name'=>'sup_status','default_key'=>$row['sup_status']??1])
                        @endcomponent
                    </div>
                    <span class="col-sm-7 color-6A6969" style="padding-top: 1%"> 启用:开启;禁用:不开启。</span>
                </div>
                <span class="msg-box" style="position:static;" for="sup_status"></span>
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
        display: flex;
        justify-content: space-between;
    }
    .areas-province,.areas-city,.areas-area{
        height: 30px !important;
        width: 32%;
    }

</style>