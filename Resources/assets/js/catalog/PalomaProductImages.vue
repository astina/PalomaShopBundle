<script>
    export default {
        name: "PalomaProductImages",

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

            if (this.imageEl) {
                this.zoomUrl = this.imageEl.getAttribute('data-image-full');
            }
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
    }
</script>