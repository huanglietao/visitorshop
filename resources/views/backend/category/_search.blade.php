<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>true])
    @slot('slot_main')
        <div class="search-item row" style="" >

        </div>
    @endslot

    @slot('slot_hide')
            <div class="search-item row" >

            </div>

<input type="hidden" value="{{$firstType}}" name="cate_uid" class="type_f">

    @endslot
@endcomponent
