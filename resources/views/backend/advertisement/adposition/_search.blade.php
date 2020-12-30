<!-- 搜索视图 -->
@component('component/search/search_full',['has_more'=>false])
    @slot('slot_main')
        <input type="hidden" value="{{$firstChannel}}" name="channel_id" id="channel">
        <div class="search-item row" style="" >
                <div class="col-lg-4 form-group">
                    @component('component/search/label_input',['label'=>'广告位置', 'inp_name' => 'ad_position', 'inp_id' => 'ad_position'])
                    @endcomponent
                </div>

        </div>
    @endslot

    @slot('slot_hide')

    @endslot
@endcomponent
