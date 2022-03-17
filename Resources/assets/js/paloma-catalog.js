import Vue from 'vue';
import PalomaProductList from "./catalog/PalomaProductList";
import PalomaProductSummary from "./catalog/PalomaProductSummary";
import PalomaProductSelect from "./catalog/PalomaProductSelect";
import PalomaProductRecommendations from "./catalog/PalomaProductRecommendations";
import PalomaProductImages from "./catalog/PalomaProductImages";

const productListElements = document.getElementsByClassName('paloma-product-list');
if (productListElements) {

    Array.from(productListElements).forEach(elem => {
        const productList = new Vue({
            components: {
                PalomaProductList
            }
        });

        productList.$mount(elem);
    });
}

const productViewElem = document.getElementById('paloma-product-view');
if (productViewElem) {

    // mount before PalomaProductSelect because of its mounted() event.

    const productSummary = new Vue(PalomaProductSummary);
    productSummary.$mount('#paloma-product-summary');

    const productImages = new Vue(PalomaProductImages);
    productImages.$mount('#paloma-product-images');

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
}