<!-- form表单视图 -->
<div style="margin-top:30px">
    <form class="form-horizontal common-form" id="form-save" method="post" action="/system/payment/save" onsubmit="return false;" autocomplete="off">
    <input type=hidden value="{{$row['pay_id']}}" name="pay_id" id="id">
    <input type=hidden value="{{$row['pay_name']}}" name="pay_name" id="names">
    <input type=hidden value="{{$row['mch_id']}}" name="mch_id" id="mch_id">
    <input type=hidden value="{{$row['pay_class_name']}}" name="pay_class_name" id="class_name">
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
            <span style="color:red">*</span> 支付名称：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    {{--<input  id="name" class="form-control col-sm-5" name="name" type="text" value="{{$row['name']}}" placeholder="" data-rule="支付名称:required">--}}
                    <select id="pay_name" class="form-control col-sm-5" name="partner_id" type="text" onchange="paySelect(this)">
                        @if(empty($payNames))
                            <option value="">无</option>
                        @else
                            <option value="">请选择</option>
                            @foreach($payNames as $key=>$value)
                                <option value="{{$value['pay_id']}}" @if($value['pay_id']==$row['partner_id']) selected @endif  >{{$value['pay_name']}}</option>
                            @endforeach
                        @endif
                    </select>
                    <span class="col-sm-7 color-6A6969"> 请选择对应的支付方式。</span>
                </div>
                <span class="msg-box" style="position:static;" for="pay_name"></span>
            </div>
        </div>

        <div style="display: none;" id="alipay">
            <div class="form-group row form-item">
                <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                    <span style="color:red">*</span> 合作者身份：</label>
                <div class="col-xs-12 col-sm-10">
                    <div class="row">
                        <input  id="pid" class="form-control col-sm-5" name="pid" type="text" value="{{$row['pid']}}" placeholder="" >
                        <span class="col-sm-7 color-6A6969"> 请务必按照正确的格式填写。</span>
                    </div>
                    <span class="msg-box" style="position:static;" for="pid"></span>
                </div>
            </div>
            <div class="form-group row form-item">
                <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                    <span style="color:red">*</span> 卖家账号：</label>
                <div class="col-xs-12 col-sm-10">
                    <div class="row">
                        <input  id="seller_id" class="form-control col-sm-5" name="seller_id" type="text" value="{{$row['seller_id']}}" placeholder="">
                        <span class="col-sm-7 color-6A6969"> 请务必按照正确的格式填写。</span>
                    </div>
                    <span class="msg-box" style="position:static;" for="seller_id"></span>
                </div>
            </div>
            <div class="form-group row form-item">
                <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                    <span style="color:red">*</span> KEY：</label>
                <div class="col-xs-12 col-sm-10">
                    <div class="row">
                        <input  id="key" class="form-control col-sm-5" name="key" type="text" value="{{$row['key']}}" placeholder="">
                        <span class="col-sm-7 color-6A6969"> 请务必按照正确的格式填写。</span>
                    </div>
                    <span class="msg-box" style="position:static;" for="key"></span>
                </div>
            </div>
        </div>

        <div style="display: none" id="wxpay">
            <div class="form-group row form-item">
                <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                    <span style="color:red">*</span> appid：</label>
                <div class="col-xs-12 col-sm-10">
                    <div class="row">
                        <input  id="appid" class="form-control col-sm-5" name="appid" type="text" value="{{$row['appid']}}" placeholder="">
                        <span class="col-sm-7 color-6A6969"> 请务必按照正确的格式填写。</span>
                    </div>
                    <span class="msg-box" style="position:static;" for="appid"></span>
                </div>
            </div>
            <div class="form-group row form-item">
                <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                    <span style="color:red">*</span> 商户号：</label>
                <div class="col-xs-12 col-sm-10">
                    <div class="row">
                        <input  id="mchid" class="form-control col-sm-5" name="mchid" type="text" value="{{$row['mchid']}}" placeholder="">
                        <span class="col-sm-7 color-6A6969"> 请务必按照正确的格式填写。</span>
                    </div>
                    <span class="msg-box" style="position:static;" for="mchid"></span>
                </div>
            </div>
            <div class="form-group row form-item">
                <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                    <span style="color:red">*</span> 商户支付密钥：</label>
                <div class="col-xs-12 col-sm-10">
                    <div class="row">
                        <input  id="wekey" class="form-control col-sm-5" name="wekey" type="text" value="{{$row['wekey']}}" placeholder="" >
                        <span class="col-sm-7 color-6A6969"> 请务必按照正确的格式填写。</span>
                    </div>
                    <span class="msg-box" style="position:static;" for="wekey"></span>
                </div>
            </div>
            <div class="form-group row form-item">
                <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                    <span style="color:red">*</span> 公众账号：</label>
                <div class="col-xs-12 col-sm-10">
                    <div class="row">
                        <input  id="appsecret" class="form-control col-sm-5" name="appsecret" type="text" value="{{$row['appsecret']}}" placeholder="" >
                        <span class="col-sm-7 color-6A6969"> 请务必按照正确的格式填写。</span>
                    </div>
                    <span class="msg-box" style="position:static;" for="appsecret"></span>
                </div>
            </div>
            <div class="form-group row form-item">
                <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">公钥证书：</label>
                <div class="col-xs-12 col-sm-10">
                    <div class="row" >
                        <span class="col-sm-5" style="padding-left: 0;padding-right: 0">
                            <button id="upload_file_btn" data-value="1" type="button" style="width: 65px;height: 25px;background-color: #dcdcdc;border: 1px solid #a9a9a9">选择文件</button>
                            <span id="sslcert_file" style="color: #696969;padding: 3px 3px 0 3px;">
                                @if($row['sslcert_path_name'])
                                    {{$row['sslcert_path_name']}}
                                @else 未选择任何文件
                                @endif
                            </span>
                            @if($row['sslcert_path_name'])
                                  <span id="del_file_upload" style="color: #0b97c4;cursor:pointer;text-decoration: underline;padding-top: 3px;">删除</span>
                            @else <span id="del_file_upload" style="display: none;color: #0b97c4;cursor:pointer;text-decoration: underline;padding-top: 3px;">删除</span>
                            @endif
                            <input hidden name='sslcert_path' class="sslcert_path" value="{{$row['sslcert_path']}}">
                            <input id="sslcert_path" accept="image/*"  hidden style='width:140px;display: inline-block;padding-left: 0' type='file' class="col-sm-5" onchange="fileupload(this,1)">
                        </span>
                        <span class="col-sm-7 color-6A6969"> 请以图片形式上传公钥证书，格式： jpg / jpeg / png</span>

                    </div>
                    <span class="msg-box" style="position:static;" for="sslcert_path" id="message"></span>
                </div>
            </div>
            <div class="form-group row form-item">
                <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">密钥证书：</label>
                <div class="col-xs-12 col-sm-10">
                    <div class="row">
                        <span class="col-sm-5" style="padding-left: 0;padding-right: 0">
                            <button id="upload_file_btn" data-value="2" type="button" style="width: 65px;height: 25px;background-color: #dcdcdc;border: 1px solid #a9a9a9">选择文件</button>
                            <span id="sslkey_file" style="color: #696969;padding: 3px 3px 0 3px;">
                                @if($row['sslkey_path_name'])
                                    {{$row['sslkey_path_name']}}
                                @else 未选择任何文件
                                @endif
                            </span>
                            @if($row['sslkey_path_name'])
                                  <span id="del_file_upload" style="color: #0b97c4;cursor:pointer;text-decoration: underline;padding-top: 3px;">删除</span>
                            @else <span id="del_file_upload" style="display: none;color: #0b97c4;cursor:pointer;text-decoration: underline;padding-top: 3px;">删除</span>
                            @endif
                            <input  hidden  name='sslkey_path' class="sslkey_path" value="{{$row['sslkey_path']}}">
                            <input id="sslkey_path" accept="image/*"  hidden style='width:140px;display: inline-block;padding-left: 0' type='file' class="col-sm-5" onchange="fileupload(this,2)">
                        </span>
                        <span class="col-sm-7 color-6A6969"> 请以图片形式上传密钥证书，格式： jpg / jpeg / png</span>

                    </div>
                </div>
            </div>
        </div>


        <div class="form-group row form-item">
            <label style=" font-weight: normal;line-height: 108px" for="c-mch_name" class="control-label col-xs-12 col-sm-2">
                <span style="color:red">*</span> 图标：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <div style="padding-left: 0;height: 108px" class="col-sm-5">
                        @component('component/image_upload',['name'=>'pay_logo','uploader'=>'uploader1','num'=>1,'value'=>$row['pay_logo'],'direction'=>1,'browse_btn'=>'form-avatar','content_class'=>'background','img_format'=>'jpg,jpeg,png','rule'=>'图标:required'])
                        @endcomponent
                    </div>
                    <span class="col-sm-7 color-6A6969"  style="margin-top: 3px">推荐：200 * 75 PX，大小：100K。格式： jpg / jpeg / png</span>
                </div>
                <span class="msg-box" style="position:static;" for="pay_logo"></span>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">描述：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row">
                    <textarea id="description" class="form-control col-sm-5" name="pay_desc" placeholder="" style="height: 100px;resize: none;">{{$row['pay_desc']}}</textarea>
                    <span class="col-sm-7 color-6A6969"> 请不要超过200个字符。</span>
                </div>
            </div>
        </div>
        <div class="form-group row form-item">
            <label style=" font-weight: normal" for="c-mch_name" class="control-label col-xs-12 col-sm-2">状态：</label>
            <div class="col-xs-12 col-sm-10">
                <div class="row radio">
                    <div style="display: inline-block;padding-top: 5px;padding-left: 0" class="col-sm-5">
                        @component('component/radio',['radio'=>['1'=>'启用','2'=>'禁用'],'name'=>'pay_status','default_key'=>$row['pay_status']??1])
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

