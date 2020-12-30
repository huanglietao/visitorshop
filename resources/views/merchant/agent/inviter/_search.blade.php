<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>false])
    @slot('slot_main')
        <div class="search-item row" style="" >
            <div class="col-lg-4 form-group">
                <div class="row">
                    <div class="col-xl-4 col-md-4" style="text-align: right">
                        <label class="control-label" style="font-weight: normal">邀请人:</label>
                    </div>
                    <div class="col-xl-8 col-md-8" style="padding-left: 0">
                        <select  style="padding:4px 8px;color:rgb(106, 105, 105);font-size:12px;" class="form-control"  name="inviter_id">
                            @foreach($info as $key=>$value)
                                <option value="{{$key}}">{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 form-group">
                <div class="row">
                    <div class="col-xl-2 col-md-3" style="text-align: right">
                        <label class="control-label" style="font-weight: normal">交易时间:</label>
                    </div>
                    <div class="col-xl-9 col-md-9" style="padding-left: 0">
                        @component('component/rangedatapicker',['name'=>'created_at'])
                        @endcomponent
                    </div>
                </div>
            </div>
        </div>
    @endslot

    @slot('slot_hide')


    @endslot
@endcomponent
