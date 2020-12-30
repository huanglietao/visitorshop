<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>false])
    @slot('slot_main')
        <div class="search-item row" style="" >
            <div class="col-lg-4 form-group">
                @component('component/search/label_input',['label'=>'名称', 'inp_name' => 'score_rule_name', 'inp_id' => 'score_rule_name'])
                @endcomponent
            </div>
            <div class="col-lg-4 form-group">
                <div class="row" >
                    <div class="col-xl-4 col-md-4" style="text-align: right" >
                        <label class="control-label" style="font-weight: normal">获得途径:</label>
                    </div>
                    <div class="col-xl-8 col-md-8">
                        <select name="score_rule_way" style="padding:4px 8px;color:rgb(106, 105, 105);font-size:12px;" class="form-control">
                            @foreach($scoreRule as $k=>$v)
                                <option value="{{$k}}">{{$v}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    @endslot
@endcomponent
