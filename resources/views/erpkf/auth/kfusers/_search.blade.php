<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>true])
    @slot('slot_main')
        <div class="search-item row" style="" >
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'用户名', 'inp_name' => 'username', 'inp_id' => 'username'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'昵称', 'inp_name' => 'nickname', 'inp_id' => 'nickname'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'密码', 'inp_name' => 'password', 'inp_id' => 'password'])
                    @endcomponent
                </div>

        </div>
    @endslot

    @slot('slot_hide')
            <div class="search-item row" >
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'密码盐', 'inp_name' => 'salt', 'inp_id' => 'salt'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'头像', 'inp_name' => 'avatar', 'inp_id' => 'avatar'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'邮箱', 'inp_name' => 'email', 'inp_id' => 'email'])
                    @endcomponent
                </div>

            </div>
            <div class="search-item row" >
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'登录时间', 'inp_name' => 'logintime', 'inp_id' => 'logintime'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'登录标识', 'inp_name' => 'token', 'inp_id' => 'token'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'状态', 'inp_name' => 'status', 'inp_id' => 'status'])
                    @endcomponent
                </div>

            </div>
            <div class="search-item row" >

            </div>

    @endslot
@endcomponent
