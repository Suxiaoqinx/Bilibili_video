/* * author:wqq date:2020-03-09 */
(function($) {
    $.extend({
        jqAlert: function(option) {
            let _this = this;
            var settings = {
                type: 'info', //info,success,warning,error
                content: '提示内容',
                autoClose: true
            };
            var $dom = $('.my_alert-wrapper');
            if ($dom.length === 0) {
                $(document.body).append('<div class="my_alert-wrapper"></div>');
            }
            $dom = $('.my_alert-wrapper');
            $.extend(settings, option);
            let box = $('<div class="my_alertBox" animation=""></div>');
            box.addClass('my_alertBox--' + settings.type);
            let typeIcon = $('<i class="my_alert-icon iconfont"></i>');
            typeIcon.addClass('icon-alert-' + settings.type);
            let contentBox = $('<div class="my_alert-content"></div>');
            contentBox.text(settings.content);
            let closeIcon = $('<i class="my_alert-closebtn iconfont icon-close"></i>');
            box.append(typeIcon).append(contentBox).append(closeIcon);
            $dom.append(box);
            if (settings.autoClose === true) {
                setTimeout(function() {
                    box.remove();
                }, 3 * 1000);
            }
            closeIcon.on('click', function() {
                box.remove();
            });
        }
    });
})(jQuery);

//info,success,warning,error
function showTips(txt, type) {
    var options = {
        content: txt,
        type: type ? type : "info",
    }
    $.jqAlert(options);
}

var _hmt = _hmt || [];
(function() {
    var hm = document.createElement("script");
    hm.src = "https://hm.baidu.com/hm.js?831d6cb5f7cac2aa05bfe2f09bb380c9";
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(hm, s);
})();