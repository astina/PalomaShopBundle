import Vue from 'vue';
import PalomaProductList from "./catalog/PalomaProductList";
import PalomaProductSelect from "./catalog/PalomaProductSelect";
import PalomaProductRecommendations from "./catalog/PalomaProductRecommendations";
import PalomaProductImages from "./catalog/PalomaProductImages";

const productListElem = document.getElementById('paloma-product-list');
if (productListElem) {

    const productList = new Vue({
        components: {
            PalomaProductList
        }
    });

    productList.$mount(productListElem);
}

const productViewElem = document.getElementById('paloma-product-view');
if (productViewElem) {

    new Vue({
        el: '#paloma-product-select',
        components: {
            PalomaProductSelect
        }
    });

    new Vue({
        el: '#paloma-product-recommendations',
        components: {
            PalomaProductRecommendations
        }
    });

    const productImages = new Vue(PalomaProductImages);
    productImages.$mount('#paloma-product-images');
}