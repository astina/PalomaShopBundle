<template>
    <div>

        <div class="product-summary__price">
            <paloma-price :price="variant.price" :original="variant.originalPrice"></paloma-price>
            <div v-if="variant.taxIncluded" class="product-summary__price-info">
                {{ $trans('catalog.products.vat_info_incl', {'rate': variant.taxRate}) }}
            </div>
            <div v-else class="product-summary__price-info">
                {{ $trans('catalog.products.vat_info_excl', {'rate': variant.taxRate}) }}
            </div>
        </div>

        <div class="product-summary__variants">


        </div>

        <div class="product-summary__cart-form">

            <form action="" @submit.prevent="addToCart">
                <div class="field is-grouped">

                    <div class="control">
                        <button class="button is-primary" type="submit"
                                :class="{'is-loading': loading}"
                                :disabled="!available || loading">
                            {{ $trans('catalog.products.add_to_cart') }}
                        </button>
                    </div>

                    <!--                    <div class="control">-->
                    <!--                        <a class="button is-outlined" title="Merken">-->
                    <!--                            <span class="icon">-->
                    <!--                                <i class="far fa-star"></i>-->
                    <!--                            </span>-->
                    <!--                        </a>-->
                    <!--                    </div>-->

                    <!--                    <div class="control">-->
                    <!--                        <a class="button is-outlined" title="Share">-->
                    <!--                            <span class="icon">-->
                    <!--                                <i class="fas fa-share-alt"></i>-->
                    <!--                            </span>-->
                    <!--                        </a>-->
                    <!--                    </div>-->

                </div>

            </form>

        </div>

    </div>
</template>

<script>

    import paloma from "./paloma";
    import PalomaPrice from "./PalomaPrice";

    export default {
        name: "PalomaProductSelect",

        components: {PalomaPrice},

        data() {

            const product = PALOMA['product'];

            const variant = product.variants[0];

            return {
                product: product,
                variant: variant,
                quantity: 1,
                loading: false
            }
        },

        computed: {
            available() {
                return this.variant && this.variant.availability.available;
            }
        },

        methods: {

            addToCart() {

                this.loading = true;

                paloma.cart.addItem(
                    this.variant.sku,
                    this.quantity
                ).then(() => {

                    // refresh product
                    paloma.catalog.product(this.product.itemNumber)
                        .then(product => {

                            this.loading = false;

                            this.product = product;
                            this.variant = product.variants[0];
                        });

                });
            }
        }
    }
</script>
