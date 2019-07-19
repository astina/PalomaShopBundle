<template>

    <div class="product-card">
        <div class="product-card__inner">

            <div class="product-card__image">
                <div class="product-card__image-inner">
                    <paloma-image
                            :image="product.firstImage"
                            size="small"
                    ></paloma-image>
                </div>
            </div>

            <div class="product-card__info">
                <div v-if="product.attributes.brand" class="product-card__brand">
                    {{ product.attributes.brand.value }}
                </div>
                <div v-else-if="productCategory" class="product-card__brand">
                    {{ productCategory.name }}
                </div>
                <a :href="href" class="product-card__title">
                    {{ product.name }}
                </a>
            </div>

            <div class="product-card__controls">
                <div class="product-card__price">
                    <paloma-price
                            :price="product.basePrice"
                            :original="product.originalBasePrice"
                    ></paloma-price>
                </div>
            </div>

            <div class="product-card__meta">
                <div class="product-card__badges">
                    <div v-if="product.reductionPercent" class="product-card__badge">
                        <span class="badge badge--red">{{ product.reductionPercent}}</span>
                    </div>
                </div>
<!--                <div class="product-card__availability">-->
<!--                    <div class="availability availability&#45;&#45;green">-->
<!--                        <span class="availability__indicator"></span>-->
<!--                    </div>-->
<!--                </div>-->
            </div>

        </div>
        <a :href="href" class="product-card__link"></a>
    </div>

</template>

<script>
    import PalomaImage from "../common/PalomaImage";
    import PalomaPrice from "../common/PalomaPrice";

    export default {
        name: "PalomaProductCard",

        components: {PalomaPrice, PalomaImage},

        props: {
            product: Object,
            category: Object,
            href: String
        },

        data() {

            const productCategory = this.category || this.product.mainCategory;

            return {
                productCategory: productCategory
            }
        }
    }
</script>

<style scoped>

</style>