@component('component/search/search_full',['has_more'=>false])
    @slot('slot_main')
        <div class="search-item row" style="" >
            <div class="col-lg-8 form-group row search-row">
                <div class="col-xl-2 col-md-4 duration-search-label" style="text-align: right">
                    <label class="control-label" style="font-weight: normal;">
                        查询时间:
                    </label>
                </div>
                <div class="col-xl-9 col-md-8 form-group duration-search ">
                    <div class="row" >
                        <div class="col-xl-6 col-md-6 reservationtime" style="padding-left: 0">
                            <input name="search" type="text" style="font-size: 12px;width: 100%" id="reservationtime" class="form-control float-right date-picker datetimerange">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <div class="col-xl-6 col-md-6 data-num" style="display: flex; white-space: nowrap;">

                    <span class="search-data-num" data-num="1">
                      昨天
                    </span>
                            <span class="search-data-num"  data-num="7">
                       最近7天
                    </span>
                            <span class="search-data-num"  data-num="30">
                        最近30天
        </span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    @endslot
@endcomponent
<style>
    .search-data-num {
        padding-top: 5px!important;
        margin-left: 13px;
        font-weight: normal;
        color: #6A6969;
        cursor: pointer;
    }
    .fa-calendar{
        color: #a9a9a9;
        position: absolute;
        vertical-align: middle;
        top: 31%;
        right: 0;
        padding-right: 13px;
    }
</style>