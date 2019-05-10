require('../css/paloma.scss');

// jQuery
import jQuery from 'jquery';
import Vue from 'vue';

import PalomaProductList from './PalomaProductList';

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


// Vue

// TODO create plugin
Vue.prototype.$trans = function(key) {
    return Translator.trans(key);
};

// Product list

const productListElem = document.getElementById('paloma-product-list');

if (productListElem) {

    const productList = new Vue({
        components: {
            PalomaProductList
        }
    });

    productList.$mount(productListElem);
}