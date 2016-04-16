/**
 *
 +---------------
 *后台topic.js
 *
 +---------------
 */

$(function () {
    //$("#zhaobiao li a, #department li a, #downfile li a, #f_link li a").each(function () { alert($(this).html()) });
    //一个汉字占一个字节
    //招标公告的新闻长度截取
    $(".topic").formatTopic({
        length: 30,
        fill: '.',
        fillLength: 4
    });



});