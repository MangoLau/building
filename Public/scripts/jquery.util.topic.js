/**
 *
 +-----------------------
 *截取文章长度jquery插件
 *@length 要显示的长度
 *@fill 要填充的符号
 *@fillLength 填充符号的重复次数
 +-----------------------
 */

(function ($) {
    $.fixedTopicWidth = function (str, options) {
        var settings = $.extend({ length: 50, fill: null, fillLength: 3 }
        , options || {});
        var pos = settings.length - str.length;
        if (pos > 0) {
            return str;
        } else {
            if (settings.fill) {
                var fs = "";

                for (var i = 0; i < settings.fillLength; i++) {
                    fs = fs + settings.fill;
                }
                return str.substr(0, settings.length - settings.fillLength) + fs;
            } else {
                return str.substr(0, settings.length);
            }
        }
    }

    $.fn.formatTopic = function (options) {
        this.each(function (n) {
           
            $(this).html($.fixedTopicWidth($(this).html(),options));

        });
    }
   

})(jQuery);