<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/auth/group/save" onsubmit="return false;" autocomplete="off">
        <input type=hidden value="{{$row['id']}}" name="id" id="id">
        <input type="hidden" name="rules" value="" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 父级：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <select  class="form-control col-sm-5" name='pid'>
                        <option value="1">系统默认组</option>
                        <option value="2">ces组</option>
                        <option value="33">rr组</option>
                    </select>
                    {{--<input  id="pid" class="form-control col-sm-5" name="pid" type="text" value="{{$row['pid']}}" placeholder="" data-rule="父级:required">--}}
                    <span class="col-sm-7 color-6A6969"> 父级可根据不同权限的管理员分配不同级别的角色组。</span>
                </div>
                <span class="msg-box" style="position:static;" for="pid"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 组名：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="name" class="form-control col-sm-5" name="name" type="text" value="{{$row['name']}}" placeholder="" data-rule="组名:required">
                    <span class="col-sm-7 color-6A6969"> 支持中英文,长度2~15位。</span>
                </div>
                <span class="msg-box" style="position:static;" for="name"></span>
            </div>
        </div>


        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">状态：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row radios">
                    <div style="display: inline-block;padding-top: 5px;padding-left: 0" class="col-sm-5">
                        @component('component/radio',['radio'=>['normal'=>'启用','hidden'=>'禁用'],'name'=>'status','default_key'=>$row['status']])
                        @endcomponent
                    </div>
                    <span class="col-sm-7 color-6A6969"> 启用：正常使用；禁用：隐藏账号不能使用。</span>
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">权限：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row checkbox-row" style="margin-bottom: 20px">
                    @component('component/checkbox',['checkbox'=>['选中全部','展开全部'],'name'=>['checkedall','expandall'],'custom_class'=>"checkedall checkallbox",'left_distance'=>25,'right_distance'=>15])
                    @endcomponent
                        {{-- <input  id="rules" class="form-control col-sm-5" name="rules" type="text" value="{{$row['rules']}}" placeholder="" data-rule="规则ID:required">--}}
                </div>
                @component('component/authRules',['data'=>$data,'url'=>'/auth/group/tree'])
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
<script>
   /* $('#treeview').on("changed.jstree", function (e, data) {
        console.log(data.selected);
    });*/
    $(document).on("click", "[name='checkedall']", function () {
        $("#treeview").jstree($(this).prop("checked") ? "check_all" : "uncheck_all");
    });
   $(document).on("click", "[name='expandall']", function () {
       $("#treeview").jstree($(this).prop("checked") ? "open_all" : "close_all");
   });
</script>