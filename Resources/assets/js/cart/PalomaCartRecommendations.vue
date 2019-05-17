<template>
    <div class="cart-recommendations" v-if="products.length > 0">

        <h3 class="cart-recommendations__title">{{ $trans('cart.recommendations') }}</h3>

        <div class="columns is-multiline is-mobile">
            <div v-for="product in products"
                 class="column is-half">
                <paloma-product-card :product="product" :href="createHref(product)"></paloma-product-card>
            </div>
        </div>

    </div>
</template>

<script>

    import paloma from '../paloma';
    import PalomaProductCard from "../catalog/PalomaProductCard";

    export default {
        name: "PalomaCartRecommendations",

        components: {PalomaProductCard},

        data() {
            return {
                products: []
            }
        },

        mounted() {
            paloma.cart.getRecommendations(3)
                .then(products => this.products = products);
        },

        methods: {
            createHref(product) {
                return paloma.router.resolve('catalog_product_locate', {itemNumber: product.itemNumber});
            }
        }
    }
</script>

<style scoped>

</style>