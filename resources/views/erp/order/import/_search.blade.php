<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>true])
    @slot('slot_main')
        <div class="search-item row" style="" >
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'客户订单号', 'inp_name' => 'partner_number', 'inp_id' => 'partner_number'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'客户简称', 'inp_name' => 'partner_real_name', 'inp_id' => 'partner_real_name'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    <div class="row" >
                        <div class="col-xl-4 col-md-4" style="text-align: right" >
                            <label class="control-label" style="font-weight: normal">状态:</label>
                        </div>
                        <div class="col-xl-8 col-md-8">
                            <input name="status"  id="status" hidden type="text" class="form-control">
                            <select style="padding:4px 8px;color:rgb(106, 105, 105);font-size:12px;" class="form-control" id="statusSelect" onchange="sselect()" >
                                <option value="">请选择</option>
                                <option value="success">成功</option>
                                <option value="error">失败</option>
                            </select>
                            <script>
                                function sselect() {
                                    var obj = document.getElementById("statusSelect"); //定位id
                                    var index = obj.selectedIndex; // 选中索引
                                    var value = obj.options[index].value; // 选中值
                                    document.getElementById('status').value = value;
                                }
                            </script>
                        </div>
                    </div>
                </div>
        </div>
    @endslot

    @slot('slot_hide')
            <div class="search-item row" >
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'产品编码', 'inp_name' => 'product_code', 'inp_id' => 'product_code'])
                    @endcomponent
                </div>

                <div class="col-lg-4 form-group">
                    <div class="row" >
                        <div class="col-xl-4 col-md-4" style="text-align: right" >
                            <label class="control-label" style="font-weight: normal">指定物流方式:</label>
                        </div>
                        <div class="col-xl-8 col-md-8">
                            <input name="assign_express_type"  id="assign_express_type" hidden type="text" class="form-control">
                            <select style="padding:4px 8px;color:rgb(106, 105, 105);font-size:12px;" class="form-control" id="expressSelect" onchange="express()">
                                <option value="">请选择</option>
                                <option value="yto">圆通快递</option>
                                <option value="sto">申通快递</option>
                                <option value="zto">中通快递</option>
                                <option value="yunda">韵达快递</option>
                                <option value="best">百世快递</option>
                                <option value="sfj">顺丰寄</option>
                                <option value="sfd">顺丰到</option>
                                <option value="ems">中国邮政快递包裹</option>
                                <option value="since">自提</option>
                                <option value="other">其他快递</option>
                            </select>
                            <script>
                                function express() {
                                    var obj = document.getElementById("expressSelect"); //定位id
                                    var index = obj.selectedIndex; // 选中索引
                                    var value = obj.options[index].value; // 选中值
                                    document.getElementById('assign_express_type').value = value;
                                }
                            </script>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 form-group">
                    <div class="row" >
                        <div class="col-xl-4 col-md-4" style="text-align: right" >
                            <label class="control-label" style="font-weight: normal">合并发货:</label>
                        </div>
                        <div class="col-xl-8 col-md-8">
                            <input name="is_collect"  id="is_collect" hidden type="text" class="form-control">
                            <select style="padding:4px 8px;color:rgb(106, 105, 105);font-size:12px;" class="form-control" id="collectSelect" onchange="select()" >
                                <option value="">请选择</option>
                                <option value="1">是</option>
                                <option value="2">否</option>
                            </select>
                            <script>
                                function select() {
                                    var obj = document.getElementById("collectSelect"); //定位id
                                    var index = obj.selectedIndex; // 选中索引
                                    var value = obj.options[index].value; // 选中值
                                    document.getElementById('is_collect').value = value;
                                }
                            </script>
                        </div>
                    </div>
                </div>
            </div>
            <div class="search-item row" >
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'收件人', 'inp_name' => 'recipient_person', 'inp_id' => 'recipient_person'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'收件人手机', 'inp_name' => 'recipient_phone', 'inp_id' => 'recipient_phone'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'收件人地址', 'inp_name' => 'recipient_address', 'inp_id' => 'recipient_address'])
                    @endcomponent
                </div>

            </div>
            <div class="search-item row" >
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'发件人', 'inp_name' => 'sender_person', 'inp_id' => 'sender_person'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'发件人手机', 'inp_name' => 'sender_phone', 'inp_id' => 'sender_phone'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'发件人地址', 'inp_name' => 'sender_address', 'inp_id' => 'sender_address'])
                    @endcomponent
                </div>
            </div>

    @endslot
@endcomponent
