;(function ($) {
    $(document).ready(function() {
        var body = document.getElementsByTagName("body");
        body[0].dispatchEvent(new Event("load-fullbg"));
    });
})(jQuery);