/**
 * 框架内组件公共处理的js
 * Created by hlt on 2020/3/27.
 */
$(function() {
    'use strict';

    //======================权限树选择js开始 =============//
    $(document).on("click", "[name='checkedall']", function () {
        $("#treeview").jstree($(this).prop("checked") ? "check_all" : "uncheck_all");
    });
    $(document).on("click", "[name='expandall']", function () {
        $("#treeview").jstree($(this).prop("checked") ? "open_all" : "close_all");
    });
    //======================权限树选择js结束 =============//
});