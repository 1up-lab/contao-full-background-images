;(function ($) {
    $(document).ready(function() {
        var event = document.createEvent("Event");
        event.initEvent("load-fullbg", true, true);
        window.dispatchEvent(event);
    });
})(jQuery);