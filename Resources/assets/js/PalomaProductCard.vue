<template>

    <div class="product-card">
        <div class="product-card__inner">

            <div class="product-card__image">
                <div class="product-card__image-inner">
                    <paloma-image
                            v-bind:image="product.firstImage"
                            size="small"
                    ></paloma-image>
                </div>
            </div>

            <div class="product-card__info">
                <div v-if="product.attributes.brand" class="product-card__brand">
                    {{ product.attributes.brand.value }}
                </div>
                <a href="" class="product-card__title">
                    {{ product.name }}
                </a>
            </div>

            <div class="product-card__controls">
                <div class="product-card__price">
                    <paloma-price
                            v-bind:price="product.basePrice"
                            v-bind:original="product.originalBasePrice"
                    ></paloma-price>
                </div>
            </div>

        </div>
        <a href="" class="product-card__link"></a>
    </div>

</template>

<script>
    import PalomaImage from "./PalomaImage";
    import PalomaPrice from "./PalomaPrice";

    export default {
        name: "PalomaProductCard",

        components: {PalomaPrice, PalomaImage},

        props: {
            product: Object,
            category: Object
        }
    }
</script>

<style scoped>

</style>