<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>true])
    @slot('slot_main')
        <div class="search-item row" style="" >
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'商户id', 'inp_name' => 'mid', 'inp_id' => 'mid'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'用户名', 'inp_name' => 'username', 'inp_id' => 'username'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'昵称', 'inp_name' => 'nickname', 'inp_id' => 'nickname'])
                    @endcomponent
                </div>

        </div>
    @endslot

    @slot('slot_hide')
            <div class="search-item row" >
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'密码', 'inp_name' => 'password', 'inp_id' => 'password'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'密码盐', 'inp_name' => 'salt', 'inp_id' => 'salt'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'是否为主账号', 'inp_name' => 'is_main', 'inp_id' => 'is_main'])
                    @endcomponent
                </div>

            </div>
            <div class="search-item row" >
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'token标识', 'inp_name' => 'token', 'inp_id' => 'token'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'头像1', 'inp_name' => 'avatar', 'inp_id' => 'avatar'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'创建时间', 'inp_name' => 'created_at', 'inp_id' => 'created_at'])
                    @endcomponent
                </div>

            </div>
            <div class="search-item row" >
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'更新时间', 'inp_name' => 'updated_at', 'inp_id' => 'updated_at'])
                    @endcomponent
                </div>

            </div>

    @endslot
@endcomponent
