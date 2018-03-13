$(function () {
    if ($.fn.owlCarousel) {
        $('.is-carousel').each(function () {
            var items = $(this).data('items'),
                dots = ($(this).data('dots') == 1) ? true : false,
                nav = ($(this).data('nav') == 1) ? true : false,
                margin = $(this).data('margin'),
                responsive = $(this).data('responsive'),
                loop = ($(this).data('loop') == 1) ? true : false,
                autoPlay = ($(this).data('autoplay') == 1) ? true : false,
                autoPlayHoverPause = ($(this).data('autoplay-hover-pause') == 1) ? true : false,
                mouseDrag = ($(this).data('mouse-drag') == 1) ? true : false;

            if ($(this).hasClass('auto-width')) {
                var carousel = $(this);
                $(this).on('refresh.owl.carousel', function () {
                    setCarouselItemsWidth(carousel, items, margin);
                });

                $(this).owlCarousel({
                    autoWidth: true,
                    dots: dots,
                    nav: nav,
                    navText: ["<i class='arrow-icon'></i>", "<i class='arrow-icon'></i>"],
                    rtl: true
                });
            } else if ($(this).hasClass('vertical')) {
                $(this).owlCarousel({
                    loop: loop,
                    autoplay: autoPlay,
                    items: items,
                    dots: dots,
                    nav: nav,
                    autoplayHoverPause: autoPlayHoverPause,
                    mouseDrag: mouseDrag,
                    animateOut: 'slideOutUp',
                    animateIn: 'slideInUp',
                    rtl: true
                });
            } else {
                $(this).owlCarousel({
                    loop: loop,
                    autoplay: autoPlay,
                    items: items,
                    dots: dots,
                    nav: nav,
                    autoplayHoverPause: autoPlayHoverPause,
                    mouseDrag: mouseDrag,
                    navText: ["<i class='arrow-icon'></i>", "<i class='arrow-icon'></i>"],
                    responsive: responsive,
                    rtl: true
                });
            }
        });
        var owl = $('.owl-carousel');
        owl.owlCarousel();
        $('.customNextBtn').click(function () {
            owl.trigger('next.owl.carousel');
        });
        $('.customPrevBtn').click(function () {
            owl.trigger('prev.owl.carousel', [300]);
        });
        $('.owl-carousel').owlCarousel({
            loop: true,
            margin: 0,
            nav: true,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 3
                },
                1000: {
                    items: 3
                }
            }
        });
    }

    $("body").on("click", ".lists", function () {
        $(".hide-menu").fadeToggle(200);
    }).on("click", ".user", function () {
        $(".login-process").fadeToggle(200);
    }).on("click", ".user-dash", function () {
        $(".login-panel").fadeToggle(200);
    }).on("click", ".btn-3", function () {
        $(".login-panel").fadeOut(200);
    }).on("click", ".search", function () {
        $(".hide-search").show();
    }).on("click", ".svg-close", function () {
        $(".hide-search").hide();
    }).on("click", ".svg-bars", function () {
        $(".mobail-menu").toggle(200);
    }).on("click", ".header, .consumer", function () {
        $(".mobail-menu").hide(200);
    });

    if ($.fn.niceScroll)
        $(".opinion-to").niceScroll({
            cursorcolor: "#00381d",
            cursorwidth: "8px",
            background: "rgba(204,215,210,0.3)",
            cursorborder: "1px solid #00381d",
            cursorborderradius: 4,
            autohidemode: 'leave'
        });

    /*$(".father-scroll").niceScroll({
        cursorcolor: "#00381d",
        cursorwidth: "8px",
        background: "rgba(204,215,210,0.3)",
        cursorborder: "1px solid #00381d",
        cursorborderradius: 4,
        autohidemode: 'leave'
    });*/

    if ($.fn.rateYo)
        $("#demoon").rateYo({
            starWidth: "30px",
            normalFill: "#e3e3e3",
            ratedFill: "#ffb234",
            numStars: 5,
            minValue: 0,
            maxValue: 5,
            precision: 1,
            rating: 0,
            onChange: null,
            onSet: null
        });
});


