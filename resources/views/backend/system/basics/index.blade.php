@extends('layout.iframe')
<!DOCTYPE html>
@section("main-content")

    <!-- 面包屑组件start  -->
    @component('component/crumb',['icon' => 'fa-dashboard', 'title' => '系统设置 / 基础设置' ])
    @endcomponent
    <!-- 面包屑组件end  -->

    <div id="main">
        <!--  提示组件 start -->
        @component('component/tips')
            {{--<p style="margin:5px 0 ;padding:0">该页面展示了所有能使用OMS系统的人员账号清单。</p>--}}
            {{--<p style="margin:5px 0 ;padding:0">管理员可手动添加并分配对应系统功能权限，查看该管理操作系统日志信息。</p>--}}
            {{--<p style="margin:5px 0;padding:0">管理员账号支持主账号与子账号关联关系，主账号能看到所有子账号信息，各子账号只能看到自己信息，可配合权限使用。</p>--}}
    @endcomponent
        <!--  提示组件 end -->

        <div class="row" style="width: 100%;display: flex;flex-direction: column;padding-top: 50px;font-size: 12px;color: #797777;padding-left: 30px;">
            <form method="post" action="{{url('system/basics/save')}}">
                @csrf
                <input type="hidden" name="admin_id" value="{{ $id }}"/>
                <input type="hidden" name="setting_id" value="{{$data['setting_id']}}"/>

                <div class="col-sm-12 col-xs-12" style="width: 100%;flex-direction: row;display: flex;margin-bottom: 15px;">
                    <div style="margin-right: 20px;text-align: right;line-height: 27px;width: 12%;"><span style="color: red;">*</span>数据配置平台名称:</div>
                    <div style="width: 88%">
                        <div class="row">
                            <input type="text" id="cms_name" class="form-control col-sm-7" name="cms_name" value="{{$data['cms_name']}}" data-rule="数据配置平台名称:required"/>
                            <span class="col-sm-5 color-6A6969">当前系统站点名，进入系统左上角显示的名称</span>
                        </div>
                        <span class="msg-box" style="position:static;" for="cms_name"></span>
                    </div>
                </div>

                <div class="col-sm-12 col-xs-12" style="width: 100%;flex-direction: row;display: flex;margin-bottom: 15px;">
                    <div style="margin-right: 20px;text-align: right;line-height: 27px;width: 12%;"><span style="color: red;">*</span>商户管理平台名称:</div>
                    <div style="width: 88%">
                        <div class="row">
                            <input type="text" id="oms_name" class="form-control col-sm-7" name="oms_name" value="{{$data['oms_name']}}" data-rule="商户管理平台名称:required"/>
                            <span class="col-sm-5 color-6A6969">商户管理品台进入系统左上角显示的名称</span>
                        </div>
                        <span class="msg-box" style="position:static;" for="oms_name"></span>
                    </div>
                </div>

                <div class="col-sm-12 col-xs-12" style="width: 100%;flex-direction: row;display: flex;margin-bottom: 15px;">
                    <div style="margin-right: 20px;text-align: right;line-height: 27px;width: 12%;"><span style="color: red;">*</span>分销管理平台名称:</div>
                    <div style="width: 88%">
                        <div class="row">
                            <input type="text" id="dms_name" class="form-control col-sm-7" name="dms_name" value="{{$data['dms_name']}}" data-rule="分销管理平台名称:required"/>
                            <span class="col-sm-5 color-6A6969">分销管理品台进入系统左上角显示的名称</span>
                        </div>
                        <span class="msg-box" style="position:static;" for="dms_name"></span>
                    </div>
                </div>

                <div class="col-sm-12 col-xs-12" style="width: 100%;flex-direction: row;display: flex;margin-bottom: 15px;">
                    <div style="margin-right: 20px;text-align: right;line-height: 27px;width: 12%;">系统语言:</div>
                    <div style="width: 50%">
                        <select class="form-control" name="language">
                            <option value="1" @if($data['language'] == 1) selected @endif>中文(zh_cn)</option>
                            <option value="2" @if($data['language'] == 2) selected @endif>英文(en_us)</option>
                        </select>
                    </div>
                </div>

                <div class="col-sm-12 col-xs-12" style="width: 100%;flex-direction: row;display: flex;margin-bottom: 15px;">
                    <div style="margin-right: 20px;text-align: right;line-height: 27px;width: 12%;">备案号:</div>
                    <div style="width: 50%">
                        <input class="form-control" style="width: 100%;margin-bottom: 5px;" name="site_record_num" value="{{$data['site_record_num']}}" />
                    </div>
                    <div style="line-height: 27px;margin-left: 10px;">各系统站点底部备案号</div>
                </div>

                <div class="col-sm-12 col-xs-12" style="width: 100%;flex-direction: row;display: flex;margin-bottom: 15px;">
                    <div style="margin-right: 20px;text-align: right;line-height: 27px;width: 12%;">版权信息:</div>
                    <div style="width: 50%">
                        <input class="form-control" style="width: 100%;margin-bottom: 5px;" name="site_copyright" value="{{$data['site_copyright']}}" />
                    </div>
                    <div style="line-height: 27px;margin-left: 10px;">各系统站点底部版权信息</div>
                </div>

                <div class="col-sm-12 col-xs-12" style="width: 100%;flex-direction: row;display: flex;margin-bottom: 15px;">
                    <div style="margin-right: 20px;text-align: right;line-height: 27px;width: 12%;">联系方式:</div>
                    <div style="width: 50%">
                        <input class="form-control" style="width: 100%;margin-bottom: 5px;" name="site_mobile" value="{{$data['site_mobile']}}" />
                    </div>
                    <div style="line-height: 27px;margin-left: 10px;">企业的联系方式</div>
                </div>

                <div class="col-sm-12 col-xs-12" style="width: 100%;flex-direction: row;display: flex;margin-bottom: 15px;">
                    <div style="margin-right: 20px;text-align: right;line-height: 27px;width: 12%;">详细地址:</div>
                    <div style="width: 50%">
                        <input class="form-control" style="width: 100%;margin-bottom: 5px;" name="site_address" value="{{$data['site_address']}}" />
                    </div>
                    <div style="line-height: 27px;margin-left: 10px;">企业的详细地址信息</div>
                </div>

                <div class="col-sm-12 col-xs-12" style="width: 100%;flex-direction: row;display: flex;margin-bottom: 15px;">
                    <div style="margin-right: 20px;text-align: right;line-height: 27px;width: 12%;">分页显示列表:</div>
                    <div style="width: 50%">
                        <input class="form-control" style="width: 100%;margin-bottom: 5px;" name="page_list" value="{{$data['page_list']}}" />
                    </div>
                    <div style="line-height: 27px;margin-left: 10px;">展示可选分页的数目</div>
                </div>

                <div class="col-sm-12 col-xs-12" style="width: 100%;flex-direction: row;display: flex;margin-bottom: 15px;">
                    <div style="margin-right: 20px;text-align: right;line-height: 27px;width: 12%;">默认分页数:</div>
                    <div style="width: 50%">
                        <input class="form-control" style="width: 100%;margin-bottom: 5px;" name="default_page" value="{{$data['default_page']}}" />
                    </div>
                    <div style="line-height: 27px;margin-left: 10px;">默认使用的分页数，数值必须在分页显示列表中</div>
                </div>

                <div class="col-sm-12 col-xs-12" style="width: 100%;flex-direction: row;display: flex;margin-bottom: 15px;">
                    <div style="margin-right: 20px;text-align: right;line-height: 27px;width: 12%;">货币标识:</div>
                    <div style="width: 50%">
                        <input class="form-control" style="width: 100%;margin-bottom: 5px;" name="currency" value="{{$data['currency']}}" />
                    </div>
                    <div style="line-height: 27px;margin-left: 10px;">使用货币时的统一使用标识</div>
                </div>

                <div class="col-sm-12 col-xs-12" style="width: 100%;flex-direction: row;display: flex;margin-bottom: 15px;">
                    <div style="margin-right: 20px;text-align: right;line-height: 27px;width: 12%;">金额保留小数:</div>
                    <div style="width: 50%">
                        <input class="form-control" style="width: 100%;margin-bottom: 5px;" name="money_decimal" value="{{$data['money_decimal']}}" />
                    </div>
                    <div style="line-height: 27px;margin-left: 10px;">使用金额时需保留的小数</div>
                </div>

                <div class="col-sm-12 col-xs-12" style="width: 100%;flex-direction: row;display: flex;margin-bottom: 15px;">
                    <div style="margin-right: 20px;text-align: right;line-height: 27px;width: 12%;">维护模式:</div>
                    <div style="width: 50%;padding-top: 5px;">
                        @component('component/radio',['radio'=>[PUBLIC_YES=>'是',PUBLIC_NO=>'否'],'name'=>'maintain','default_key'=>$data['maintain']??PUBLIC_NO])
                        @endcomponent
                    </div>
                    <div style="line-height: 27px;margin-left: 10px;">维护模式下所有页面变成正在维护</div>
                </div>

                <div class="col-sm-12 col-xs-12" style="width: 100%;flex-direction: row;display: flex;margin-bottom: 15px;">
                    <div style="margin-right: 20px;text-align: right;line-height: 27px;width: 12%;">关闭系统:</div>
                    <div style="width: 50%;padding-top: 5px;">
                        @component('component/radio',['radio'=>[PUBLIC_YES=>'是',PUBLIC_NO=>'否'],'name'=>'close_sys','default_key'=>$data['close_sys']??PUBLIC_NO])
                        @endcomponent
                    </div>
                    <div style="line-height: 27px;margin-left: 10px;">控制所有系统开关</div>
                </div>

                <div class="col-sm-12 col-xs-12" style="width: 100%;height: 80px;flex-direction: row;display: flex">
                    <div style="width:12%;margin-right: 20px;text-align: right;line-height: 130px;"></div>
                    <div style="width: 50%;margin-top: 25px;">
                        <button type="submit" id="comfirm" class="btn btn-primary btn-3F51B5 btn-sure" style="cursor:pointer;" onclick="return false;">确定</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
@endsection

<!---  引入当前页需使用的js  -->
@section("pages-js")

@endsection
@section("js-file")
    <script src="{{ URL::asset('js/backend/system.js')}}"></script>

@endsection
