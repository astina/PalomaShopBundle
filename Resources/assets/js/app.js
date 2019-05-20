import Vue from 'vue';

// TODO create plugin
Vue.prototype.$trans = function(key, params) {
    return Translator.trans(key, params);
};

Vue.directive('click-outside', {
    bind: function (el, binding, vnode) {
        el.clickOutsideEvent = function (event) {
            // here I check that click was outside the el and his childrens
            if (!(el === event.target || el.contains(event.target))) {
                // and if it did, call method provided in attribute value
                vnode.context[binding.expression](event);
            }
        };
        document.body.addEventListener('click', el.clickOutsideEvent)
    },
    unbind: function (el) {
        document.body.removeEventListener('click', el.clickOutsideEvent)
    },
});

require('./paloma-category-nav');

require('./paloma-catalog');

require('./paloma-cart');

require('./paloma-checkout');
