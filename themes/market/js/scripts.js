$(function() {
    var $body = $('body');
    var $window = $(window);
    $(".content").css({'min-height':$(window).height()-225});

    $window.resize(function(){
        $(".content").css({'min-height':$(window).height()-225});
    });

    $body.on('click', '.dropdown-toggle', function () {
        var $this = $(this);
        $(".dropdown-toggle").not($this).removeClass('active');
        $this.addClass('active');
    });

    $body.on('click', '.add-multipliable-input', function () {
        var input = document.createElement('input');
        input.type = 'text';
        input.name = 'Apps[permissions][' + $('.multipliable-input').length + ']';
        input.placeholder = 'دسترسی';
        input.className = 'form-control multipliable-input';
        var container = document.getElementsByClassName('multipliable-input-container');
        $(container).append(input);
        return false;
    });

    $body.on('click', '.remove-multipliable-input', function () {
        if ($('.multipliable-input').length > 1)
            $('.multipliable-input-container .multipliable-input:last').remove();
        return false;
    });

    if ($(".app-description").height() > 230)
        $(".app-description").next().show();
    $body.on('click', '.more-text', function () {
        var $h = $(".app-description").height();
        if ($h > 230) {
            $(this).parent().animate({height: $h + 80}, 0).addClass('open');
            $(this).remove();
        }
        return false;
    });

    // Responsive Scripts
    $body.on('click', ".search-trigger", function () {
        var $this = $(this);
        var $searchBox = $('.mobile-search');
        if ($searchBox.parents('header.mobile').hasClass('search-open')) {
            $searchBox.parents('header.mobile').removeClass('search-open');
            $('.mobile-nav').removeClass('fadeInLeft fadeOutLeft animated').addClass('fadeInLeft animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend');
            $('.logo-box').removeClass('fadeInRight fadeOutRight animated').addClass('fadeInRight animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend');
            $searchBox.find('.svg-close').removeClass('bounceIn bounceOut animated').addClass('bounceOut animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                $(this).removeClass('bounceOut animated');
            });
        } else {
            $searchBox.parents('header.mobile').addClass('search-open');
            $('.mobile-nav').removeClass('fadeInLeft fadeOutLeft animated').addClass('fadeOutLeft animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend');
            $('.logo-box').removeClass('fadeInRight fadeOutRight animated').addClass('fadeOutRight animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend');
            $searchBox.find('.svg-close').removeClass('bounceOut bounceIn animated').addClass('bounceIn animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                $(this).removeClass('bounceIn animated');
            });
        }
    });

    $body.on('click', ".navbar-trigger", function () {
        var $this = $(this);
        var $overlay = $('.overlay');
        var $navbar = $('.mobile-navbar');
        $navbar.toggleClass('open');
        $overlay.toggleClass('in');
        $('body,html').toggleClass('overflow');
    });
    $body.on('click', ".side-bar-trigger", function () {
        var $this = $(this);
        var $overlay = $('.overlay');
        var $sidebar = $('.side-bar');
        $sidebar.toggleClass('open');
        $overlay.toggleClass('in');
        $('body,html').toggleClass('overflow');
    });
    $body.on('click', ".overlay", function () {
        var $overlay = $('.overlay');
        var $navbar = $('.mobile-navbar');
        var $sidebar = $('.side-bar');
        $navbar.removeClass('open');
        $sidebar.removeClass('open');
        $overlay.toggleClass('in');
        $('body,html').toggleClass('overflow');
    });

    // user menu show
    $body.on('click', ".user-section .avatar", function () {
        var $this = $(this);
        $this.parent().toggleClass('open');
    });

    // enamad position
    if ($(window).width() > 991)
        $(".enamad").css({'right':parseInt(($(".sidebar").width() - 166)/2)});


    if ($('.cat-menu-container').length != 0)
        $('.cat-menu-container').niceScroll({cursorcolor: "#ccc"});

    // resize changes
    $(window).resize(function () {
        if ($(window).width() > 991) {
            $(".overlay").removeClass('in');
            $(".open").removeClass('open');
            // enamad
            $(".enamad").css({'right':parseInt(($(".sidebar").width() - 166)/2)});
        }
    });
});
function ratingAnimate(){
    $('.bar span').hide();
    $('#bar-five').animate({
        width: $('#bar-five').data('percent')+'%'}, 1000);
    $('#bar-four').animate({
        width: $('#bar-four').data('percent')+'%'}, 1000);
    $('#bar-three').animate({
        width: $('#bar-three').data('percent')+'%'}, 1000);
    $('#bar-two').animate({
        width: $('#bar-two').data('percent')+'%'}, 1000);
    $('#bar-one').animate({
        width: $('#bar-one').data('percent')+'%'}, 1000);

    setTimeout(function() {
        $('.bar span').fadeIn('slow');
    }, 1000);
}
function submitAjaxForm(form ,url ,loading ,callback) {
    loading = typeof loading !== 'undefined' ? loading : null;
    callback = typeof callback !== 'undefined' ? callback : null;
    $.ajax({
        type: "POST",
        url: url,
        data: form.serialize(),
        dataType: "json",
        beforeSend: function () {
            if(loading)
                loading.show();
        },
        success: function (html) {
            if(loading)
                loading.hide();
            if (typeof html === "object" && typeof html.state === 'undefined') {
                $.each(html, function (key, value) {
                    $("#" + key + "_em_").show().html(value.toString()).parent().removeClass('success').addClass('error');
                });
            }else
                eval(callback);
        }
    });
}