<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>true])
    @slot('slot_main')
        <div class="search-item row" style="" >
            <div class="col-lg-4 form-group">
                @component('component/search/label_input',['label'=>'姓名', 'inp_name' => 'workers_name', 'inp_id' => 'workers_name'])
                @endcomponent
            </div>
            <div class="col-lg-8 form-group">
                <div class="row">
                    <div class="col-xl-2 col-md-2" style="text-align: right">
                        <label class="control-label" style="font-weight: normal">日期:</label>
                    </div>
                    <div class="col-xl-8 col-md-8">
                        @component('component/rangedatapicker',['name'=>'finish_time'])

                        @endcomponent
                    </div>
                </div>
            </div>
        </div>
    @endslot

    @slot('slot_hide')
        <div class="search-item row" >
            <div class="col-lg-4 form-group">
                <div class="row">
                    <div class="col-xl-4 col-md-4" style="text-align: right">
                        <label class="control-label" style="font-weight: normal">职位:</label>
                    </div>
                    <div class="col-xl-8 col-md-8">
                        <select  style="padding:4px 8px;color:rgb(106, 105, 105);font-size:12px;" class="form-control"  name="salary_worker_position">
                            <option value="">请选择</option>
                            @foreach($position as $key=>$val)
                                <option value="{{$key}}">{{$val['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 form-group"><div class="row">
                    <div class="col-xl-4 col-md-4" style="text-align: right">
                        <label class="control-label" style="font-weight: normal">班次:</label>
                    </div>
                    <div class="col-xl-8 col-md-8">
                        <select  style="padding:4px 8px;color:rgb(106, 105, 105);font-size:12px;" class="form-control"  name="shift">
                            <option value="">请选择</option>
                            @foreach($shift as $key=>$val)
                                <option value="{{$key}}">{{$val}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    @endslot
@endcomponent
