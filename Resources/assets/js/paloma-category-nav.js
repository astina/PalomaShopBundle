import jQuery from "jquery";

(function($) {

    const $categoryNav = $('.category-nav');

    // open category nav on hamburger click
    $categoryNav.find('.category-nav__burger').click((e) => {
        e.preventDefault();
        $categoryNav.toggleClass('is-active');
    });

    // close category nav on outside click
    $categoryNav.find('.category-nav__menu-wrapper').click((e) => {
        if (e.target === e.currentTarget) {
            e.preventDefault();
            $categoryNav.toggleClass('is-active');
        }
    });

    // on mouse enter, add .category-nav__dropdown--active to leave dropdown open (when not :hover'ing)
    $categoryNav.find('.category-nav__item--has-children').on('mouseenter', (e) => {
        $categoryNav.find('.category-nav__dropdown').removeClass('category-nav__dropdown--active');
        $(e.currentTarget)
            .find('.category-nav__dropdown')
            .addClass('category-nav__dropdown--active');
    });

    const $categoryDropdowns = $categoryNav.find('.category-nav__dropdown');

    // close dropdown after delay on leaving dropdown
    $categoryDropdowns.on('mouseleave', (e) => {
        closeCategoryDropdown(e.currentTarget);
    });

    $categoryDropdowns.hover(
        (e) => {
            $(e.currentTarget).data('_hover', 1)
        },
        (e) => {
            $(e.currentTarget).data('_hover', 0)
        });

    // close dropdown after delay (if mouse not hovering) on leaving nav item
    $categoryNav.find('.category-nav__item--has-children').on('mouseleave', (e) => {
        $(e.currentTarget)
            .find('.category-nav__dropdown--active')
            .each((index, elem) => {
                closeCategoryDropdown(elem);
            });
    });

    function closeCategoryDropdown(elem) {
        setTimeout(() => {
            const $elem = $(elem);
            if (($elem).data('_hover')) {
                return;
            }
            $elem.removeClass('category-nav__dropdown--active');
        }, 500);
    }

    // toggle sitemap section
    $categoryNav.find('.sitemap__section--has-children > .sitemap__section-link').click((e) => {

        // only relevant for > mobile
        if ($(window).width() > 768) {
            return;
        }

        e.preventDefault();
        $(e.currentTarget).parent().toggleClass('sitemap__section--active');
    });

    // manage .is-scrolled on body
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