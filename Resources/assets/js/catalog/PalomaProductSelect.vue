<template>
    <div>

        <div v-if="options.length > 0" class="product-summary__variants">
            <paloma-product-option v-for="option in options"
                                   :key="option.option"
                                   :option="option"
                                   @select-value="selectOptionValue"></paloma-product-option>
        </div>

        <div class="product-summary__price">
            <paloma-price :price="variant.price" :original="variant.originalPrice" :unit="variant.priceUnit"></paloma-price>
            <span v-if="variant.reductionPercent" class="product-summary__reduction">
                <span class="badge">{{ variant.reductionPercent }}</span>
            </span>
            <div v-if="variant.taxIncluded" class="product-summary__price-info">
                {{ $trans('catalog.products.vat_info_incl', {'rate': variant.taxRate}) }}
            </div>
            <div v-else class="product-summary__price-info">
                {{ $trans('catalog.products.vat_info_excl', {'rate': variant.taxRate}) }}
            </div>
        </div>

        <div class="product-summary__cart-form">

            <form action="" @submit.prevent="addToCart">
                <div class="field is-grouped">

                    <div class="control">
                        <button class="button is-primary product-summary__cart-form-button" type="submit"
                                :class="{'is-loading': loading}"
                                :disabled="!available || loading">
                            {{ $trans('catalog.products.add_to_cart') }}
                        </button>
                    </div>

                    <div v-if="!available" class="product-summary__out-of-stock">
                        {{ $trans('catalog.products.out_of_stock') }}
                    </div>

                </div>

            </form>

            <paloma-cart-item-added v-if="cartItem" :cart-item="cartItem"></paloma-cart-item-added>

        </div>

    </div>
</template>

<script>

import paloma from "../paloma";
import config from '../paloma-config';
import PalomaPrice from "../common/PalomaPrice";
import PalomaCartItemAdded from "../cart/PalomaCartItemAdded";
import PalomaProductOption from "./PalomaProductOption";

export default {
        name: "PalomaProductSelect",

        components: {
            PalomaProductOption,
            PalomaCartItemAdded,
            PalomaPrice,
        },

        props: {
            product: Object
        },

        data() {

            const product = this.product;

            const variant = product.variants[0];

            const options = this._createOptions(product);
            this._refreshOptions(options, product, variant);

            return {
                variant: variant,
                options: options,
                quantity: 1,
                loading: false,
                cartItem: null,
            }
        },

        mounted() {
            this.publishVariantSelection();
        },

        computed: {

            available() {
                return this.variant && this.variant.availability.available;
            }
        },

        methods: {

            publishVariantSelection() {
                paloma.events.$emit('paloma.variant_selected', this.variant, this.product);
            },

            addToCart() {

                this.loading = true;

                paloma.cart.addItem(
                    this.variant.sku,
                    this.quantity
                ).then(item => {

                    if (config.catalog.showCartAfterItemAdded) {
                        paloma.events.$emit('paloma.cart_show', item);
                    } else {

                        this.cartItem = item;

                        window.setTimeout(() => {
                            // make the "item added" box disappear
                            this.cartItem = null;
                        }, 5000);
                    }

                    // refresh product
                    paloma.catalog.product(this.product.itemNumber)
                        .then(product => {

                            Object.assign(this.product, product);
                            this.variant = product.variants.find(v => v.sku === item.sku);
                            this._refreshOptions(this.options, this.product, this.variant);
                        });

                }).finally(() => {
                    this.loading = false;
                });
            },

            selectOptionValue(option, value) {

                let variants = value.variants;

                // Find possible SKU for selected option values
                for (let i in this.options) {
                    const opt = this.options[i];
                    if (opt.option === option.option) {
                        opt.selectedValue = value.value;
                    } else {
                        opt.values.forEach(v => {
                            if (v.value === opt.selectedValue) {
                                variants = intersect(v.variants, variants);
                            }
                        });
                    }
                }

                const sku = variants[0];
                this.variant = this.product.variants.find(v => v.sku === sku);

                this._refreshOptions(this.options, this.product, this.variant);

                this.publishVariantSelection();
            },

            _refreshOptions(options, product, variant) {

                for (let opt in options) {
                    const option = options[opt];
                    for (let val in option.values) {

                        let value = option.values[val];

                        if (value.variants.find(sku => sku === variant.sku)) {
                            option.selectedValue = value.value;
                        }

                        /**
                         * If we have one option, each value probably has only one variant assigned.
                         * In this case, we assign the variant object to the option value.
                         */
                        if (options.length === 1) {
                            if (value.variants.length === 1) {
                                value.variant = product.variants.find(v => v.sku === value.variants[0]);
                            }
                        }
                    }
                }
            },

            _createOptions(product) {
                const options = [];
                for (let opt in product.options) {
                    options.push(product.options[opt]);
                }

                return options;
            }
        }
    }

    function intersect(a, b) {
        var t;
        if (b.length > a.length) t = b, b = a, a = t; // indexOf to loop over shorter
        return a.filter(function(e) {
            return b.indexOf(e) > -1;
        });
    }
</script>
