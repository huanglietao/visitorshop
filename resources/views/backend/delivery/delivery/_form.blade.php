<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/delivery/delivery/save" onsubmit="return false;" autocomplete="off">
    <input type=hidden value="{{$row['delivery_id']}}" name="delivery_id" id="delivery_id">
    <input type=hidden value="{{$row['mch_id']}}" name="mch_id" id="mch_id">
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 运送名称：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="delivery_name" class="form-control col-sm-5" name="delivery_name" type="text" value="{{$row['delivery_name']}}" placeholder="" data-rule="运送名称:required">
                    <span class="col-sm-7 color-6A6969"> 请填写该配置方式的名称。</span>
                </div>
                <span class="msg-box" style="position:static;" for="delivery_name"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 展示名称：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="delivery_show_name" class="form-control col-sm-5" name="delivery_show_name" type="text" value="{{$row['delivery_show_name']}}" placeholder="" data-rule="展示名称:required">
                    <span class="col-sm-7 color-6A6969"> 请填写该配置方式的展示名称。</span>
                </div>
                <span class="msg-box" style="position:static;" for="delivery_show_name"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 包含快递：</label>
            <div class="col-xs-12 col-sm-10">
                <input  id="del_name_list" hidden class="form-control col-sm-5" name="delivery_express_list" type="text" value="{{$row['delivery_express_list']}}"  placeholder="" data-rule="包含快递:required">
                @if(!$delivery)  <span class="col-sm-7 color-6A6969" style="top: 7px;padding-left: 0;font-size: 14px"><a href="/delivery/exprerss">请先添加物流公司再进行包含快递配置。</a> </span> @endif
                @foreach($delivery as $k=>$v)
                    <div class="row check" style="margin-top: 7px">
                        @if(!isset($row['delivery_express_list']) || str_contains($row['delivery_express_list'],$v['express_id'])==false)
                            @component('component/checkbox',['checkbox'=>[$v['express_name']],'name'=>[$v['express_id']],'custom_class'=>'checkedres'])
                            @endcomponent
                        @elseif(str_contains($row['delivery_express_list'],$v['express_id'])==true)
                            @component('component/checkbox',['checkbox'=>[$v['express_name']],'name'=>[$v['express_id']],'custom_class'=>'checkedres','checked'=>'0'])
                            @endcomponent
                        @endif
                    </div>
                @endforeach
                <span class="msg-box" style="position:static;" for="del_name_list"></span>
            </div>

        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 适用场景：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <textarea id="delivery_desc" class="form-control col-sm-5" name="delivery_desc" placeholder="" style="height: 100px;resize: none;" data-rule="适用场景:required">{{$row['delivery_desc']}}</textarea>
                    <span class="col-sm-7 color-6A6969"> 请描述该配置的运送方式适用的场景。</span>
                </div>
                <span class="msg-box" style="position:static;" for="delivery_desc"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">货到付款：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row radio">
                    <div style="display: inline-block;padding-top: 5px;padding-left: 0" class="col-sm-5">
                        @component('component/radio',['radio'=>['0'=>'否','1'=>'是'],'name'=>'delivery_is_cash','default_key'=>$row['delivery_is_cash']??0])
                        @endcomponent
                    </div>
                    <span class="col-sm-7 color-6A6969"  style="margin-top: 3px"> 是：启用；否：不启用</span>
                </div>
                <span class="msg-box" style="position:static;" for="cash_on_del"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">状态：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row radio">
                    <div style="display: inline-block;padding-top: 5px;padding-left: 0" class="col-sm-5">
                        @component('component/radio',['radio'=>['1'=>'启用','0'=>'禁用'],'name'=>'delivery_status','default_key'=>$row['delivery_status']??1])
                        @endcomponent
                    </div>
                    <span class="col-sm-7 color-6A6969"  style="margin-top: 3px"> 启用：正常；禁用：不能使用</span>
                </div>
                <span class="msg-box" style="position:static;" for="status"></span>
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
</style>
