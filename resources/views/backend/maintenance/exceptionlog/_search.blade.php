<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>false])
    @slot('slot_main')
        <div class="search-item row" >
            <div class="col-lg-4 form-group">
                @component('component/search/label_input',['label'=>'标题', 'inp_name' => 'title', 'inp_id' => 'title'])
                @endcomponent
            </div>

            <div class="col-lg-4 form-group">
                <div class="row" >
                    <div class="col-xl-4 col-md-4" style="text-align: right" >
                        <label class="control-label" style="font-weight: normal">是否处理:</label>
                    </div>
                    <div class="col-xl-5 col-md-5">
                        <select name="is_solved" style="padding:4px 8px;color:rgb(106, 105, 105);font-size:12px;" class="form-control">
                            @foreach ($isSolved as $k=>$v)
                                <option value={{$k}}>{!! $v !!}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

    @endslot

    @slot('slot_hide')



    @endslot
@endcomponent
