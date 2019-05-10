<template>
    <div class="cart-overlay" :class="{'cart-overlay--active': show}">

        <div class="cart-wrapper">
            <div v-if="show" class="cart">

                <div v-if="loading">
                    <span class="icon">
                        <i class="fas fa-spinner fa-spin"></i>
                    </span>
                </div>

                <div v-else>

                    <h2 class="cart__title">{{ $trans('cart.title') }}</h2>

                    <div class="cart__items">
                        <paloma-cart-item v-for="item in cart.items"
                                          :key="item.id"
                                          :item="item"
                                          :highlight="lastItem && item.id === lastItem.id"></paloma-cart-item>
                    </div>

                    <div class="buttons">

                        <a class="button is-primary" href="">
                            {{ $trans('cart.to_checkout') }}
                        </a>

                    </div>

                </div>

            </div>
        </div>

    </div>
</template>

<script>

    import paloma from './paloma';
    import PalomaCartItem from "./PalomaCartItem";

    export default {

        name: "PalomaCartOverlay",

        components: {PalomaCartItem},

        props: {
            show: Boolean,
            lastItem: Object,
        },

        data() {
            return {
                cart: null,
                loading: false,
            }
        },

        watch: {
            'show': function() {
                if (this.show) {

                    this.loading = true;

                    paloma.cart.get().then(cart => {
                        this.cart = cart;
                        this.loading = false;
                    });

                } else {
                    this.lastItem = null;
                }
            }
        }
    }
</script>

<style scoped>

</style>