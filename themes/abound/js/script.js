$(function () {
    setInterval(function () {
        $(".callout:not(.message)").fadeOut('fast', function () {
            $(this).remove();
        });
    }, 5000);

    $("body").on('click', '.add-multipliable-input', function () {
            var input = document.createElement('input');
            input.type = 'text';
            input.name = 'Apps[permissions][' + $('.multipliable-input').length + ']';
            input.placeholder = 'دسترسی';
            input.className = 'form-control multipliable-input';
            var container = document.getElementsByClassName('multipliable-input-container');
            $(container).append(input);
            return false;
        })
        .on('click', '.remove-multipliable-input', function () {
            if ($('.multipliable-input').length > 1)
                $('.multipliable-input-container .multipliable-input:last').remove();
            return false;
        });
});