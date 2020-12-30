<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>true])
    @slot('slot_main')
        <div class="search-item row" style="" >
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'SMTP地址', 'inp_name' => 'smtp_address', 'inp_id' => 'search_smtp_address'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'SMTP端口', 'inp_name' => 'smtp_port', 'inp_id' => 'search_smtp_port'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'SMTP用户名', 'inp_name' => 'smtp_username', 'inp_id' => 'search_smtp_username'])
                    @endcomponent
                </div>

        </div>
    @endslot

    @slot('slot_hide')
            <div class="search-item row" >
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'发送人', 'inp_name' => 'sender', 'inp_id' => 'search_sender'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    <div class="row" >
                        <div class="col-xl-4 col-md-4" style="text-align: right" >
                            <label class="control-label" style="font-weight: normal">连接类型:</label>
                        </div>
                        <div class="col-xl-8 col-md-8">
                            <select name="connecttype" style="padding:4px 8px;color:rgb(106, 105, 105);font-size:12px;" class="form-control" >
                                <option value="">请选择</option>
                                <option value="1">ssl</option>
                                <option value="2">tls</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 form-group">
                    <div class="row" >
                        <div class="col-xl-4 col-md-4" style="text-align: right" >
                            <label class="control-label" style="font-weight: normal">使用场景:</label>
                        </div>
                        <div class="col-xl-8 col-md-8">
                            <select name="scene" style="padding:4px 8px;color:rgb(106, 105, 105);font-size:12px;" class="form-control">
                                <option value="">请选择</option>
                                <option value="1">内部服务</option>
                                <option value="2">服务器报警</option>
                                <option value="3">客户邮件</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>


    @endslot
@endcomponent
