$(function(){

    'use strict';

    $('#main').delegate(".btn-dialog",'click',function () {
        setTimeout(function(){
            if ($("#form-save").find("#reservationtime"))
            {
                rangedatapickers();
            }
        },300);

    });

    //时间组件 start
    function rangedatapickers() {
        var options = {
            singleDatePicker: true,//设置为单个的datepicker，而不是有区间的datepicker 默认false
            showDropdowns: true,
            timePicker: false,
            autoUpdateInput: false,
            timePickerSeconds: true,
            timePicker24Hour: true,
            autoApply: true,
            locale: {
                format: 'YYYY-MM-DD HH:mm:ss',
                customRangeLabel: "Custom Range",
                applyLabel: "Apply",
                cancelLabel: "Clear",
                daysOfWeek: ['日', '一', '二', '三', '四', '五', '六'],
                monthNames: ['一月', '二月', '三月', '四月', '五月', '六月',
                    '七月', '八月', '九月', '十月', '十一月', '十二月'
                ],
            },
        };
        var origincallback = function (start) {
            $(this.element).val(start.format(options.locale.format));
            $(this.element).trigger('blur');
        };

        $(".datetimerange").each(function () {
            var callback = typeof $(this).data('callback') == 'function' ? $(this).data('callback') : origincallback;
            $(this).on('apply.daterangepicker', function (ev, picker) {
                callback.call(picker, picker.startDate, picker.endDate);
            });
            $(this).on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('').trigger('blur');
            });
            $(this).daterangepicker($.extend({}, options, $(this).data()), callback);
        });
    }
//时间组件 end



});