<!-- 搜索视图 -->
@component('component/search')
<form name="search-form" id="search-form" method="post">
<div style="display: flex" class="search-line">
    <div class="search-item row" style="" >
        <div class="col-lg-4 form-group">
            <div class="row">
                <div class="col-xl-4 col-md-3" style="text-align: right">
                    <label class="control-label" style="font-weight: normal">订单编号:</label>
                </div>
                <div class="col-xl-8 col-md-9">
                    <input name="name" type="text" class="form-control">
                </div>
            </div>
        </div>
        <div class="col-lg-4 form-group">
            <div class="row">
                <div class="col-xl-4 col-md-3" style="text-align: right">
                    <label class="control-label" style="font-weight: normal;padding-top: 2px!important;">
                        <select class="search-time">
                            <option value="">创建时间</option>
                            <option value="">发货时间</option>
                        </select>
                    </label>

                </div>
                <div class="col-xl-8 col-md-9">
                    <input name="name1" type="text" id="reservationtime" class="form-control float-right date-picker datetimerange">
                    <i class="fa fa-calendar"></i>
                </div>

            </div>
        </div>
        <div class="col-lg-4 form-group">
            <div class="row">
                    <label class="search-data-num" data-num="1">
                      昨天
                    </label>
                    <label class="search-data-num"  data-num="7">
                       最近7天
                    </label>
                    <label class="search-data-num"  data-num="30">
                        最近30天
                    </label>

            </div>
        </div>

    </div>

    <div style="display:  inline-block" class="btn-search">
        <button class="btn  btn-primary btn-sm btn-3F51B5 b-search" style="padding:3px 15px;font-size: 12px">搜索</button>
        &nbsp;&nbsp;
        <button class="btn  btn-default btn-sm " style="padding:3px 15px;font-size: 12px">重置</button>

        &nbsp;&nbsp;
        <span style="font-size: 12px;color:#3F51B5;cursor: pointer;" class="search-more">
            更多搜索条件
            &nbsp;
            <i class="fa fa-chevron-down"></i>
        </span>
    </div>
</div>

@endcomponent

@component('component/search_open')
    <div>
    <div style="display: flex;margin-top:5px" class="more search-line">
        <div class="search-item row" style="width: 80%;" >
            <div class="col-lg-4 form-group">
                <div class="row">
                    <div class="col-xl-4 col-md-3" style="text-align: right">
                        <label class="control-label" style="font-weight: normal">商品货号:</label>
                    </div>
                    <div class="col-xl-8 col-md-9">
                        <input type="text" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-lg-4 form-group">
                <div class="row">
                    <div class="col-xl-4 col-md-3" style="text-align: right">
                        <label class="control-label" style="font-weight: normal">订单状态:</label>
                    </div>
                    <div class="col-xl-8 col-md-9">
                        <select class="order_status_search">
                            <option value="">全部</option>
                            <option value="">未付款</option>
                            <option value="">待发货</option>
                            <option value="">已发货</option>
                            <option value="">已收货</option>
                            <option value="">已评价</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 form-group">
                <div class="row">
                    <div class="col-xl-4 col-md-3" style="text-align: right">
                        <label class="control-label" style="font-weight: normal">评价状态:</label>
                    </div>
                    <div class="col-xl-8 col-md-9">
                        <select class="order_status_search">
                            <option value="">全部</option>
                            <option value="">未评价</option>
                            <option value="">已评价</option>
                        </select>
                    </div>
                </div>
            </div>


        </div>
    </div>
    <div style="display: flex;margin-top:5px" class="more search-line">
        <div class="search-item row" style="width: 80%;" >
            <div class="col-lg-4 form-group">
                <div class="row">
                    <div class="col-xl-4 col-md-3" style="text-align: right">
                        <label class="control-label" style="font-weight: normal">收货人:</label>
                    </div>
                    <div class="col-xl-8 col-md-9">
                        <input type="text" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-lg-4 form-group">
                <div class="row">
                    <div class="col-xl-4 col-md-3" style="text-align: right">
                        <label class="control-label" style="font-weight: normal">物流单号:</label>
                    </div>
                    <div class="col-xl-8 col-md-9">
                        <input type="text" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-lg-4 form-group">
                <div class="row">
                    <div class="col-xl-4 col-md-3" style="text-align: right">
                        <label class="control-label" style="font-weight: normal">交易关联单号:</label>
                    </div>
                    <div class="col-xl-8 col-md-9">
                        <input type="text" class="form-control">
                    </div>
                </div>
            </div>


        </div>
    </div>
    </div>

    <div class="open-search-btn">
        <button class="btn  btn-primary btn-sm btn-3F51B5" style="padding:3px 15px;font-size: 12px">搜索</button>
        &nbsp;&nbsp;
        <button class="btn  btn-default btn-sm " style="padding:3px 15px;font-size: 12px">重置</button>

        &nbsp;&nbsp;
        <span style="font-size: 12px;color:#3F51B5;cursor: pointer;" class="search-more-hide">
            收起
            &nbsp;
            <i class="fa fa-chevron-up"></i>
        </span>
    </div>
</form>
@endcomponent