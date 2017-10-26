$(function () {
    setInterval(function () {
        $(".callout:not(.message)").fadeOut('fast', function () {
            $(this).remove();
        });
    },5000);
});