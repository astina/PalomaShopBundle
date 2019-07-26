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

    $categoryNav.find('.sitemap__section--has-children > .sitemap__section-link').click((e) => {

        // only relevant for > mobile
        if ($(window).width() > 768) {
            return;
        }

        e.preventDefault();
        $(e.currentTarget).parent().toggleClass('sitemap__section--active');
    });

    $(window).on('scroll', () => {

        const logoHeight = $('.page-header__brand-logo').height();
        const scrolled = $(window).scrollTop() > logoHeight;

        if (scrolled) {
            $('body').addClass('is-scrolled');
        } else {
            $('body').removeClass('is-scrolled');
        }
    })

})(jQuery);