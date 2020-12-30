<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/auth/rule/save" onsubmit="return false;" autocomplete="off">
        <input type=hidden value="{{$row['dms_group_id']}}" name="id" id="id">

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 名称：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="dms_group_name" class="form-control col-sm-5" name="dms_group_name" type="text" value="{{$row['dms_group_name']}}" placeholder="" data-rule="角色名称:required">
                    <span class="col-sm-7 color-6A6969"> 支持中英文,长度2~15位。</span>
                </div>
                <span class="msg-box" style="position:static;" for="dms_group_name"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">状态：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row radios">
                    <div style="display: inline-block;padding-top: 5px;padding-left: 0" class="col-sm-5">
                        @component('component/radio',['radio'=>[PUBLIC_ENABLE=>'启用',PUBLIC_DISABLE=>'禁用'],'name'=>'dms_group_status','default_key'=>$row['dms_group_status']??PUBLIC_ENABLE])
                        @endcomponent
                    </div>
                    <span class="col-sm-7 color-6A6969"> 启用：正常使用；禁用：隐藏角色不能使用。</span>
                </div>
                <span class="msg-box" style="position:static;" for="dms_group_status"></span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">权限：</label>
            <div class="col-xs-12 col-sm-10">

                <div class="row checkbox-row" style="margin-bottom: 20px">
                    @component('component/checkbox',['checkbox'=>['选中全部','展开全部'],'name'=>['checkedall','expandall'],'custom_class'=>"checkedall checkallbox",'left_distance'=>25,'right_distance'=>15])
                    @endcomponent
                    <input type="hidden" name="rules" value="" data-rule="权限:required"/>
                </div>

                @component('component/authRules',['data'=>$data])
                @endcomponent

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
    .btn-checkbox + label{top:14px;!important;}
    .checkbox-row span{margin-top: 8px}
</style>
