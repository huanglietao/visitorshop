$(function(){
    'use strict'

    if ($('.no-border-table').attr('data-url')) {
        var url = $('.no-border-table').attr('data-url');

        //加入加载标示
        var html = '<div class="loading" style="text-align: center;margin-top:10px"> <img src="/assets/layer/src/theme/default/loading-0.gif"> </div>';

        $('.no-border-table').after(html);
        loadTableList(url,[]);

    }
});