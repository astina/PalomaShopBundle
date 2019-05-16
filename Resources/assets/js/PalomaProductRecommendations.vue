<template>
    <div class="product-recommendations" v-if="products.length > 0">

        <h3 class="product-recommendations__title">{{ $trans('catalog.products.recommendations') }}</h3>

        <div class="columns is-multiline is-mobile">
            <div v-for="product in products"
                 class="column is-half-mobile is-one-third-tablet is-one-quarter-desktop">
                <paloma-product-card :product="product" :href="createHref(product)"></paloma-product-card>
            </div>
        </div>

    </div>
</template>

<script>

    import paloma from './paloma';
    import PalomaProductCard from './PalomaProductCard';

    export default {
        name: "PalomaProductRecommendations",

        components: {PalomaProductCard},

        data() {

            const product = PALOMA['product'];

            return {
                product: product,
                products: []
            }
        },

        mounted() {
            paloma.catalog.purchasedTogether(this.product.itemNumber, 8)
                .then(results => {
                    this.products = results.content;
                });
        },

        methods: {
            createHref(product) {
                return paloma.router.resolve('catalog_product_locate', {itemNumber: product.itemNumber});
            }
        }
    }
</script>