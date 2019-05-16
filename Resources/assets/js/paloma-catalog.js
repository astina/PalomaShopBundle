import Vue from 'vue';
import PalomaProductList from "./PalomaProductList";
import PalomaProductSelect from "./PalomaProductSelect";
import PalomaProductRecommendations from "./PalomaProductRecommendations";

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

    new Vue({

        el: '#paloma-product-images',

        data() {
            return {
                imageEl: null,
                zoomUrl: null,
                zoomed: false
            }
        },

        mounted() {
            this.imageEl = document.querySelector('.product-images__image');
            this.thumbsEl = document.querySelector('.product-images__thumbs');
            this.zoomUrl = this.imageEl.getAttribute('data-image-full');
        },

        methods: {

            selectImage(event) {
                event.preventDefault();

                const activeThumb = this.thumbsEl.querySelector('.product-images__thumb--active');
                activeThumb.classList.remove('product-images__thumb--active');

                const thumb = event.currentTarget;
                thumb.classList.add('product-images__thumb--active');

                const img = this.imageEl.querySelector('img');
                img.src = thumb.getAttribute('data-image-large');

                this.zoomUrl = thumb.getAttribute('data-image-full');
            }
        }
    });

}