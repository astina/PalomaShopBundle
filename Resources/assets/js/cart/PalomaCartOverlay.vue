<template>
    <div class="cart-overlay" :class="{'cart-overlay--active': show}" @click="closeOverlay">

        <div class="cart-wrapper">
            <div v-if="show" class="cart">

                <div v-if="loading">
                    <span class="icon">
                        <i class="far fa-spinner fa-spin"></i>
                    </span>
                </div>

                <div v-else>

                    <a class="cart-wrapper__close" @click.prevent="close" href="">
                        <span class="icon">
                            <i class="fal fa-times"></i>
                        </span>
                    </a>

                    <h2 class="cart__title">{{ $trans('cart.title') }}</h2>

                    <p v-if="cart.empty" class="cart__empty">
                        {{ $trans('cart.empty') }}
                    </p>

                    <div class="cart__items">
                        <paloma-cart-item v-for="item in cart.items"
                                          :key="item.id"
                                          :item="item"
                                          :highlight="lastItem && item.id === lastItem.id"></paloma-cart-item>
                    </div>

                    <div v-if="!cart.empty" class="cart__total">
                        <div class="cart__total-title">
                            {{ $trans('cart.total') }}
                        </div>
                        <div class="cart__total-price">
                            <paloma-price :price="cart.itemsPrice"></paloma-price>
                        </div>
                    </div>

                    <div class="cart__buttons">
                        <div v-if="!cart.empty" class="cart__button cart__button--checkout">
                            <a class="button is-primary" :href="checkoutUrl">
                                {{ $trans('cart.to_checkout') }}
                            </a>
                        </div>
                        <div class="cart__button">
                            <a class="button is-text" @click.prevent="close" href="">
                                {{ $trans('cart.continue_shopping') }}
                            </a>
                        </div>
                    </div>

                </div>

            </div>

            <paloma-cart-recommendations v-if="lastItem"></paloma-cart-recommendations>

        </div>

    </div>
</template>

<script>

    import paloma from '../paloma';
    import PalomaCartItem from "./PalomaCartItem";
    import PalomaPrice from "../common/PalomaPrice";
    import PalomaCartRecommendations from "./PalomaCartRecommendations";

    export default {

        name: "PalomaCartOverlay",

        components: {
            PalomaCartRecommendations,
            PalomaPrice,
            PalomaCartItem
        },

        props: {
            show: Boolean,
            lastItem: Object,
        },

        data() {
            return {
                cart: {},
                loading: this.show,
            }
        },

        computed: {
            checkoutUrl() {
                return paloma.router.resolve('checkout_start');
            }
        },

        watch: {
            'show': function() {
                this.show && this._loadCart();
            }
        },

        mounted() {

            this.show && this._loadCart();

            paloma.events.$on('paloma.cart_updated', (cart) => {
                this.cart = cart;
            });
        },

        methods: {

            close() {
                paloma.events.$emit('paloma.cart_hide');
            },

            closeOverlay(event) {

                // Only close on click on overlay shadow
                if (event.target !== event.currentTarget) {
                    return;
                }

                this.close();
            },

            _loadCart() {

                this.loading = true;

                paloma.cart.get().then(cart => {
                    this.cart = cart;
                    this.loading = false;
                });
            }
        }
    }
</script>

<style scoped>

</style>