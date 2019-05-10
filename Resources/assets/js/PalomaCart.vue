<template>
    <div>

        <a class="cart-mini" @click.prevent="toggleCart" href="">
            <span class="icon">
                <span class="fas fa-shopping-cart"></span>
            </span>
            {{ $trans('cart.units_count', {count: cart.unitsCount}) }}
            <span class="cart-mini__items-price">({{cart.itemsPrice}})</span>
        </a>

        <paloma-cart-overlay
                :show="showCart"
                :cart="cart"
                :last-item="lastItem"></paloma-cart-overlay>

    </div>
</template>

<script>

    import paloma from './paloma';
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

            paloma.events.$on('paloma.cart_loaded', (cart) => {
                this.cart = cart;
            });

            paloma.events.$on('paloma.cart_show', (item) => {
                this.lastItem = item;
                this.showCart = true;
            });
        },

        methods: {
            toggleCart() {

                if (this.cart.empty) {
                    this.showCart = false;
                    return;
                }

                this.showCart = !this.showCart;
            }
        }
    }
</script>

<style scoped>

</style>