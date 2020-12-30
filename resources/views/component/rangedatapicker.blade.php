<!-- 时间选择 -->
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
    .daterangepicker{
        z-index: 999999999;
    }
</style>

<div class="row" >
    <div class="col-xl-6 col-md-6 reservationtime" style="padding-left: 0">
        <input name="{{isset($name)?$name:''}}" type="text" style="font-size: 12px;width: 100%" id="reservationtime" class="form-control float-right date-picker datetimerange">
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

