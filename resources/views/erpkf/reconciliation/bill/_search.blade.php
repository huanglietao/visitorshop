@component('component/search/search_full',['has_more'=>false])
    @slot('slot_main')
        <div class="search-item row" style="" >
            <div class="col-lg-4 form-group" style="white-space: nowrap">
                <div class="row" >
                    <div class="col-xl-4 col-md-4" style="text-align: right" >
                        <label class="control-label" style="font-weight: normal">客户编号:</label>
                    </div>
                    <div class="col-xl-6 col-md-6">
                        <input list="options" class="form-control" id="partner_code" placeholder="请选择或输入客户编号" autocomplete="off">
                        <datalist id="options">
                            @foreach($partner_code as $k=>$v)
                            <option value={{$v['partner_code']}}>{{$v['partner_code']}}</option>
                            @endforeach
                        </datalist>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 form-group" style="white-space: nowrap">
                <div class="row" >
                    <div class="col-xl-4 col-md-4" style="text-align: right" >
                        <label class="control-label" style="font-weight: normal">客户简称:</label>
                    </div>
                    <div class="col-xl-6 col-md-6">
                        <input  class="form-control" id="partner_real_name" placeholder="请输入客户简称" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="col-lg-8 form-group row search-row">
                <div class="col-xl-2 col-md-4 duration-search-label" style="text-align: right">
                    <label class="control-label" style="font-weight: normal;">
                        查询时间:
                    </label>
                </div>
                <div class="col-xl-9 col-md-8 form-group duration-search ">
                    <div class="row" >
                        <div class="col-xl-6 col-md-6 reservationtime" style="padding-left: 0">
                            <input name="search" type="text" style="font-size: 12px;width: 100%" id="reservationtime" class="form-control float-right date-picker datetimerange" autocomplete="off">
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
    .search-row{
        margin-top: 10px;
        margin-left: -3px;
    }
</style>