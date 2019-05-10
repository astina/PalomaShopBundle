import jQuery from "jquery";

(function($) {

    const $categoryNav = $('.category-nav');
    $categoryNav.find('.category-nav__burger').click((e) => {
        e.preventDefault();
        $categoryNav.toggleClass('is-active');
    });

    $categoryNav.find('.category-nav__menu-wrapper').click((e) => {
        if (e.target === e.currentTarget) {
            e.preventDefault();
            $categoryNav.toggleClass('is-active');
        }
    });

    $(window).on('scroll', () => {
        const scrolled = $(window).scrollTop() > 80;
        if (scrolled) {
            $('body').addClass('is-scrolled');
        } else {
            $('body').removeClass('is-scrolled');
        }
    })

})(jQuery);