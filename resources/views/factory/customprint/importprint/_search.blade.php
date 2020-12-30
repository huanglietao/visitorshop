<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>true])
    @slot('slot_main')
        <div class="search-item row" style="" >
            <div class="col-lg-4 form-group">
                <div class="row" >
                    <div class="col-xl-4 col-md-4" style="text-align: right" >
                        <label class="control-label" style="font-weight: normal">打印状态:</label>
                    </div>
                    <div class="col-xl-5 col-md-5">
                        <select name="is_print" style="padding:4px 8px;color:rgb(106, 105, 105);font-size:12px;" class="form-control">
                            <option value="">请选择</option>
                            <option value="1">已打印</option>
                            <option value="0">未打印</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 form-group">
                <div class="row">
                    <div class="col-xl-2 col-md-2" style="text-align: right">
                        <label class="control-label" style="font-weight: normal">创建时间:</label>
                    </div>
                    <div class="col-xl-8 col-md-8">
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
