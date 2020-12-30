<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>true])
    @slot('slot_main')
        <div class="search-item row" style="" >
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'menu为菜单,file为权限节点', 'inp_name' => 'oms_auth_rule_type', 'inp_id' => 'oms_auth_rule_type'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'父ID', 'inp_name' => 'oms_auth_rule_pid', 'inp_id' => 'oms_auth_rule_pid'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'规则名称', 'inp_name' => 'oms_auth_rule_name', 'inp_id' => 'oms_auth_rule_name'])
                    @endcomponent
                </div>

        </div>
    @endslot

    @slot('slot_hide')
            <div class="search-item row" >
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'规则名称', 'inp_name' => 'oms_auth_rule_title', 'inp_id' => 'oms_auth_rule_title'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'图标', 'inp_name' => 'oms_auth_rule_icon', 'inp_id' => 'oms_auth_rule_icon'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'条件', 'inp_name' => 'oms_auth_rule_condition', 'inp_id' => 'oms_auth_rule_condition'])
                    @endcomponent
                </div>

            </div>
            <div class="search-item row" >
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'操作提示', 'inp_name' => 'oms_auth_rule_remark', 'inp_id' => 'oms_auth_rule_remark'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'是否为菜单', 'inp_name' => 'oms_auth_rule_ismenu', 'inp_id' => 'oms_auth_rule_ismenu'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'权重', 'inp_name' => 'oms_auth_rule_weigh', 'inp_id' => 'oms_auth_rule_weigh'])
                    @endcomponent
                </div>

            </div>
            <div class="search-item row" >
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'状态,1启用0禁用', 'inp_name' => 'oms_auth_rule_status', 'inp_id' => 'oms_auth_rule_status'])
                    @endcomponent
                </div>

            </div>

    @endslot
@endcomponent
