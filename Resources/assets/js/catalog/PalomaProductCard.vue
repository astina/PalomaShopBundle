<template>

    <div class="product-card">
        <div class="product-card__inner">

            <div class="product-card__image">
                <div class="product-card__image-inner">
                    <paloma-image
                            :image="product.firstImage"
                            size="medium"
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
                    <div v-for="badge in badges" class="product-card__badge" :class="'product-card__badge--' + badge.code">
                        <span class="badge">{{ badge.value }}</span>
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

            const badges = this.createBadges();

            return {
                productCategory: productCategory,
                badges: badges
            }
        },

        methods: {
            createBadges() {

                const badges = [];

                if (this.product.reductionPercent) {
                    badges.push({
                        value: this.product.reductionPercent,
                        code: 'reduction'
                    });
                }

                if (this.product.attributes.badge && this.product.attributes.badge.values) {
                    this.product.attributes.badge.values.forEach((badge) => {
                        badges.push(badge);
                    });
                }

                return badges;
            }
        }
    }
</script>

<style scoped>

</style>