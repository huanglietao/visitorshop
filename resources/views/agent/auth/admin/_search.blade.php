@component('component/search/search_full',['has_more'=>true])
    @slot('slot_main')
        <div class="search-item row" style="" >
            <div class="col-lg-4 form-group" style="white-space: nowrap">
                @component('component/search/label_input',['label'=>'管理员账号','inp_name' => 'dms_adm_username'])
                @endcomponent
            </div>
            <div class="col-lg-8 form-group row search-row">

                <div class="col-xl-2 col-md-4 duration-search-label" style="text-align: right">
                    <label class="control-label" style="font-weight: normal;">
                        创建时间:
                    </label>
                </div>
                <div class="col-xl-9 col-md-8 form-group duration-search ">
                    @component('component/rangedatapicker',['name'=>'created_at'])

                    @endcomponent
                </div>
            </div>

        </div>
    @endslot

    @slot('slot_hide')
        <div class="search-item row" >
            <div class="col-lg-4 form-group">
                @component('component/search/label_input',['label'=>'真实姓名','inp_name' => 'dms_real_name'])
                @endcomponent
            </div>
            <div class="col-lg-4 form-group">
                <div class="row" >
                    <div class="col-xl-4 col-md-4" style="text-align: right" >
                        <label class="control-label" style="font-weight: normal">状态:</label>
                    </div>
                    <div class="col-xl-8 col-md-8">
                        <select  class="form-select form-control" name="dms_adm_status">
                            <option value="">请选择</option>
                            <option value="{{PUBLIC_ENABLE}}">启用</option>
                            <option value="{{PUBLIC_DISABLE}}">禁用</option>
                            <option value="{{PUBLIC_LOCK}}">锁定</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 form-group">
                <div class="row" >
                    <div class="col-xl-4 col-md-4" style="text-align: right" >
                        <label class="control-label" style="font-weight: normal">账户类型:</label>
                    </div>
                    <div class="col-xl-8 col-md-8">
                        <select  class="form-select form-control" name="agent_type">
                            <option value="">请选择</option>
                            @foreach(config('agent.shop_type') as $k=>$v)
                                <option value="{{$k}}">{{$v}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>


        </div>
        </div>
        <div style="display: flex;margin-top:5px" class="more search-line">
            <div class="search-item row"  >
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'Email','inp_name' => 'dms_adm_email'])
                    @endcomponent
                </div>

                <div class="col-lg-4 form-group">
                    <div class="row" >
                        <div class="col-xl-4 col-md-4" style="text-align: right" >
                            <label class="control-label" style="font-weight: normal">角色组:</label>
                        </div>
                        <div class="col-xl-8 col-md-8">
                            <select  class="form-select form-control" name="dms_adm_group_id">
                                <option value="">请选择</option>
                                @foreach($agtGroupList as $k=>$v)
                                    <option value="{{$k}}">{{$v}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

            </div>
    @endslot
@endcomponent
