require('../css/paloma.scss');

import Vue from 'vue';

import PalomaProductList from './PalomaProductList';

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