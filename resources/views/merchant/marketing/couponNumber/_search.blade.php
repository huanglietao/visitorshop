<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>true])
    @slot('slot_main')
        <div class="search-item row" style="" >
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'自增id', 'inp_name' => 'cou_num_id', 'inp_id' => 'cou_num_id'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'优惠券id', 'inp_name' => 'cou_id', 'inp_id' => 'cou_id'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'优惠码', 'inp_name' => 'cou_num_code', 'inp_id' => 'cou_num_code'])
                    @endcomponent
                </div>

        </div>
    @endslot

    @slot('slot_hide')
            <div class="search-item row" >
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'面值', 'inp_name' => 'cou_num_money', 'inp_id' => 'cou_num_money'])
                    @endcomponent
                </div>
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'是否使用;1:未使用,2:已使用', 'inp_name' => 'cou_num_is_used', 'inp_id' => 'cou_num_is_used'])
                    @endcomponent
                </div>

            </div>

    @endslot
@endcomponent
