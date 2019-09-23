<script>

    import paloma from "../paloma";

    export default {
        name: "PalomaProductImages",

        data() {
            return {
                imageEl: null,
                thumbsEl: null,
                zoomUrl: null,
                zoomed: false
            }
        },

        mounted() {
            this.imageEl = document.querySelector('.product-images__image');
            this.thumbsEl = document.querySelector('.product-images__thumbs');

            if (this.imageEl) {
                this.zoomUrl = this.imageEl.getAttribute('data-image-full');
            }

            paloma.events.$on('paloma.variant_selected', variant => {
                this.showVariantImages(variant.sku);
            });
        },

        methods: {

            selectImage(event) {
                event.preventDefault();
                this.selectThumb(event.currentTarget);
            },

            selectThumb(thumb) {
                const activeThumb = this.thumbsEl.querySelector('.product-images__thumb--active');
                activeThumb && activeThumb.classList.remove('product-images__thumb--active');

                thumb.classList.add('product-images__thumb--active');

                const img = this.imageEl.querySelector('img');
                img.src = thumb.getAttribute('data-image-large');

                this.zoomUrl = thumb.getAttribute('data-image-full');
            },

            showVariantImages(sku) {
                if (!this.thumbsEl) {
                    return;
                }

                this.thumbsEl.querySelectorAll('[data-image-scope="variant"]')
                    .forEach(elem => {
                        elem.classList.add('product-images__thumb--hidden');
                        elem.classList.remove('product-images__thumb--active');
                    });

                const variantImages = this.thumbsEl.querySelectorAll('[data-image-sku="' + sku + '"]');

                variantImages.forEach(elem => elem.classList.remove('product-images__thumb--hidden'));

                if (variantImages.length > 0) {
                    this.selectThumb(variantImages.item(0));
                } else {
                    const productImages = this.thumbsEl.querySelectorAll('[data-image-scope="product"]');
                    productImages && productImages.length > 0 && this.selectThumb(productImages.item(0));
                }
            }
        }
    }
</script>