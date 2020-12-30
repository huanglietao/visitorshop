<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>true])
    @slot('slot_main')
        <div class="search-item row" style="" >
            <div class="col-lg-4 form-group">
                @component('component/search/label_input',['label'=>'用户名', 'inp_name' => 'cms_adm_username', 'inp_id' => 'cms_adm_username'])
                @endcomponent
            </div>
            <div class="col-lg-4 form-group">

                <div class="row" >
                    <div class="col-xl-4 col-md-4" style="text-align: right" >
                        <label class="control-label" style="font-weight: normal">所属角色:</label>
                    </div>
                    <div class="col-xl-8 col-md-8">
                        <select name="cms_adm_group_id" style="padding:4px 8px;color:rgb(106, 105, 105);font-size:12px;" class="form-control">
                            @foreach ($groups as $k=>$v)
                                <option value={{$k}}>{{$v}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            {{--<div class="col-lg-4 form-group">
                <div class="row" >
                    <div class="col-xl-4 col-md-4" style="text-align: right" >
                        <label class="control-label" style="font-weight: normal">状态:</label>
                    </div>
                    <div class="col-xl-8 col-md-8">
                        <select name="cms_adm_status" style="padding:4px 8px;color:rgb(106, 105, 105);font-size:12px;" class="form-control">
                            <option value="">请选择</option>
                            <option value="1">启用</option>
                            <option value="0">禁用</option>
                        </select>
                    </div>
                </div>
            </div>--}}

        </div>
    @endslot

    @slot('slot_hide')
            <div class="search-item row" >
                <div class="col-lg-4 form-group">
                    <div class="row" >
                        <div class="col-xl-4 col-md-4" style="text-align: right" >
                            <label class="control-label" style="font-weight: normal">创建时间:</label>
                        </div>
                        <div class="col-xl-8 col-md-8">
                            @component('component/rangedatapicker',[ 'name' => 'created_at'])
                            @endcomponent
                        </div>
                    </div>

                </div>

            </div>


    @endslot
@endcomponent
