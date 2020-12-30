<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/article/type/save" onsubmit="return false;" autocomplete="off">
    <input type=hidden value="{{$row['art_type_id']}}" name="id" id="id">
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 分类名称：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="art_type_name" class="form-control col-sm-7" name="art_type_name" type="text" value="{{$row['art_type_name']}}" placeholder="" data-rule="分类名称:required">
                    <span class="col-sm-5 color-6A6969"> 支持中英文，长度不能超过30个字符</span>
                </div>
                <span class="msg-box" style="position:static;" for="art_type_name"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 所属渠道：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <select  class="form-control col-sm-7" id="channel_id" name="channel_id" data-rule="所属渠道:required">
                        <option value="">请选择</option>
                        @foreach($channelArr as $k=>$v)
                            <option value={{$k}} @if($k == $row['channel_id']) selected @endif >{!! $v !!}</option>
                        @endforeach
                    </select>
                    <span class="col-sm-5 color-6A6969">选择该文章分类在哪个渠道使用</span>
                </div>
                <span class="msg-box" style="position:static;" for="channel_id"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 分类标识：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <input  id="art_type_sign" class="form-control col-sm-7" name="art_type_sign" type="text" value="{{$row['art_type_sign']}}" @if($row['art_type_id']) disabled @endif @if(!$row['art_type_id']) data-rule="分类标识:required" @endif>
                    <span class="col-sm-5 color-6A6969"> 输入分类标识如：wt，添加后不可修改</span>
                </div>
                <span class="msg-box" style="position:static;" for="art_type_sign"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">状态：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row radios">
                    <div style="display: inline-block;padding-top: 5px;padding-left: 0" class="col-sm-7">
                        @component('component/radio',['radio'=>[1=>'启用',0=>'禁用'],'name'=>'art_type_status','default_key'=>$row['art_type_status']??ONE])
                        @endcomponent
                    </div>
                    <span class="col-sm-5 color-6A6969"> 启用：正常使用；禁用：隐藏不能使用。</span>
                </div>

                <span class="msg-box" style="position:static;" for="ad_status"></span>
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

