<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>true])
    @slot('slot_main')
        <div class="search-item row" style="" >
            <div class="col-lg-4 form-group">
                @component('component/search/label_input',['label'=>'文章标题', 'inp_name' => 'art_title', 'inp_id' => 'art_title'])
                @endcomponent
            </div>
            <div class="col-lg-4 form-group">
                <div class="row" >
                    <div class="col-xl-4 col-md-4" style="text-align: right" >
                        <label class="control-label" style="font-weight: normal">所属分类:</label>
                    </div>
                    <div class="col-xl-8 col-md-8">
                        <select name="art_type" style="padding:4px 8px;color:rgb(106, 105, 105);font-size:12px;" class="form-control">
                            @foreach ($artTypeList as $k=>$v)
                                <option value={{$k}}>{!! $v !!}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
           {{-- <div class="col-lg-4 form-group">
                <div class="row" >
                    <div class="col-xl-4 col-md-4" style="text-align: right" >
                        <label class="control-label" style="font-weight: normal">所属渠道:</label>
                    </div>
                    <div class="col-xl-8 col-md-8">
                        <select name="channel_id" style="padding:4px 8px;color:rgb(106, 105, 105);font-size:12px;" class="form-control">
                            @foreach ($channelList as $k=>$v)
                                <option value={{$k}}>{{$v}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
--}}
        </div>
    @endslot

    @slot('slot_hide')
        <div class="search-item row" >
            <div class="col-lg-8 form-group">
                <div class="row">
                    <div class="col-xl-2 col-md-3" style="text-align: right">
                        <label class="control-label" style="font-weight: normal">创建时间:</label>
                    </div>
                    <div class="col-xl-9 col-md-9" style="padding-left: 0">
                        @component('component/rangedatapicker',['name'=>'created_at'])
                        @endcomponent
                    </div>
                </div>
            </div>
        </div>

    @endslot
@endcomponent
