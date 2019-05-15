<template>
    <div class="cart-item-added">
        <div class="message is-success">
            <div class="message-body">
                <p>
                    <span class="cart-item-added__product">{{cartItem.title}}</span>
                    {{ $trans('cart.item_added') }}
                </p>
                <div class="buttons">
                    <a class="button is-outlined" @click.prevent="showCart" href="">
                        {{ $trans('cart.open') }}
                    </a>
                    <a class="button is-primary" :href="checkoutUrl">
                        {{ $trans('cart.to_checkout') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import paloma from './paloma';

    export default {

        name: "PalomaCartAdded",

        props: {
            cartItem: Object,
        },

        computed: {
            checkoutUrl() {
                return paloma.router.resolve('checkout_start');
            }
        },

        methods: {
            showCart() {
                paloma.events.$emit('paloma.cart_show', this.cartItem);
            }
        }
    }
</script>

<style scoped>

</style>