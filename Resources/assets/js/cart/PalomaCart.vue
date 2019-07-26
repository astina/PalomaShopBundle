<template>
    <div class="cart-mini">

        <a class="button" @click.prevent="toggleCart" href="">
            <span class="icon">
                <span class="fal fa-shopping-bag"></span>
            </span>
            <div v-if="!cart.empty" class="cart-mini__indicator">
                {{ cart.unitsCount }}
            </div>
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
                showCart: window.location.hash === '#cart',
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