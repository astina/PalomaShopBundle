<template>
    <div class="cart-mini">

        <a class="button" @click.prevent="toggleCart" href="">
            <span class="icon">
                <span class="far fa-shopping-bag"></span>
            </span>
            <span v-if="cart.empty" class="is-hidden-mobile">
                {{ $trans('cart.title') }}
            </span>
            <span v-else>
                {{ $trans('cart.units_count', {count: cart.unitsCount}) }}
                <span class="cart-mini__items-price">({{cart.itemsPrice}})</span>
            </span>
        </a>

        <paloma-cart-overlay
                :show="showCart"
                :last-item="lastItem"></paloma-cart-overlay>

    </div>
</template>

<script>

    import paloma from '../paloma';
    import PalomaCartOverlay from "./PalomaCartOverlay";

    export default {

        name: "PalomaCart",
        components: {PalomaCartOverlay},
        props: {
            unitsCount: String,
            itemsPrice: String
        },

        data() {

            const unitsCount = parseInt(this.unitsCount);

            return {
                cart: {
                    empty: unitsCount === 0,
                    unitsCount: unitsCount,
                    itemsPrice: this.itemsPrice,
                },
                showCart: false,
                lastItem: null,
            }
        },

        mounted() {

            paloma.events.$on('paloma.cart_item_added', (item, cart) => {
                this.cart = cart;
            });

            paloma.events.$on('paloma.cart_updated', (cart) => {
                this.cart = cart;
            });

            paloma.events.$on('paloma.cart_item_update', (itemId, quantity) => {
                paloma.cart.updateItem(itemId, quantity);
            });

            paloma.events.$on('paloma.cart_item_remove', (itemId) => {
                paloma.cart.removeItem(itemId);
            });

            paloma.events.$on('paloma.cart_loaded', (cart) => {
                this.cart = cart;
            });

            paloma.events.$on('paloma.cart_show', (item) => {
                this.lastItem = item;
                this.showCart = true;
            });

            paloma.events.$on('paloma.cart_hide', () => {
                this.lastItem = null;
                this.showCart = false;
            });
        },

        methods: {
            toggleCart() {
                this.showCart = !this.showCart;
            }
        }
    }
</script>

<style scoped>

</style>