<!-- form表单视图 -->
<link rel="stylesheet" href="{{ URL::asset('assets/umeditor/themes/default/css/umeditor.css') }}">
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/merchant/omsrule/save" onsubmit="return false;" autocomplete="off">
    <input type=hidden value="{{$row['oms_auth_rule_id']}}" name="id" id="id">

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">菜单：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row radios">
                    <div style="display: inline-block;padding-top: 5px;padding-left: 0" class="col-sm-6">
                        @component('component/radio',['radio'=>[ONE=>'是',ZERO=>'否'],'name'=>'oms_auth_rule_ismenu','default_key'=>$row['oms_auth_rule_ismenu']??ONE])
                        @endcomponent
                    </div>
                    <span class="col-sm-5 color-6A6969"> 是：正常现在左侧菜单使用；否：不能在左侧菜单显示</span>
                </div>

                <span class="msg-box" style="position:static;" for="oms_auth_rule_ismenu"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">父级：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <select  class="form-control col-sm-6 mater-cate" id="oms_auth_rule_pid" name="oms_auth_rule_pid" >
                        <option value="0">无</option>
                        @foreach($cateList as $k=>$v)
                            <option value={{$k}} @if($k == $row['oms_auth_rule_pid']) selected @endif >{!! $v !!}</option>
                        @endforeach
                    </select>
                    <span class="col-sm-5 color-6A6969"> 选择所属上级</span>
                </div>
                <span class="msg-box" style="position:static;" for="oms_auth_rule_pid"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 规则：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="oms_auth_rule_name" class="form-control col-sm-6" name="oms_auth_rule_name" type="text" value="{{$row['oms_auth_rule_name']}}" placeholder="" data-rule="规则名称:required">
                    <span class="col-sm-5 color-6A6969"> 填写路由名称如：auth/rule</span>
                </div>
                <span class="msg-box" style="position:static;" for="oms_auth_rule_name"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 菜单名称：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="oms_auth_rule_title" class="form-control col-sm-6" name="oms_auth_rule_title" type="text" value="{{$row['oms_auth_rule_title']}}" placeholder="" data-rule="规则名称:required">
                    <span class="col-sm-5 color-6A6969"> 中英文字符不能超过30个字符</span>
                </div>
                <span class="msg-box" style="position:static;" for="oms_auth_rule_title"></span>
            </div>
        </div>

        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">权重：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="oms_auth_rule_weigh" class="form-control col-sm-6" name="oms_auth_rule_weigh" type="text" value="{{$row['oms_auth_rule_weigh']}}">
                    <span class="col-sm-5 color-6A6969"> 菜单的排序前后，填写正整数</span>
                </div>
                <span class="msg-box" style="position:static;" for="oms_auth_rule_weigh"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">状态：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row radios">
                    <div style="display: inline-block;padding-top: 5px;padding-left: 0" class="col-sm-6">
                        @component('component/radio',['radio'=>[ONE=>'启用',ZERO=>'禁用'],'name'=>'oms_auth_rule_status','default_key'=>$row['oms_auth_rule_status']??ONE])
                        @endcomponent
                    </div>
                    <span class="col-sm-5 color-6A6969"> 启用：正常使用；禁用：无法正常启用</span>
                </div>

                <span class="msg-box" style="position:static;" for="oms_auth_rule_status"></span>
            </div>
        </div>

        <div class="form-group row form-item" >
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">备注提示：</label>
            <div class="col-xs-12 col-sm-10">
                <script id="oms_auth_rule_remark_id" name="oms_auth_rule_remark" type="text/plain" style="width:100%;height:500px;">{!! $row['oms_auth_rule_remark'] !!}</script>
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

<script src="{{ URL::asset('assets/umeditor/umeditor.config.js')}}"></script>
<script src="{{ URL::asset('assets/umeditor/umeditor.js')}}"></script>
<script src="{{ URL::asset('assets/umeditor/lang/zh-cn/zh-cn.js')}}"></script>
<script src="{{ URL::asset('js/backend/umedit.js')}}"></script>
