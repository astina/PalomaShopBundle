<template>
    <header class="checkout-header">

        <div class="checkout-header__logo">
            Logo
        </div>

        <div class="checkout-header__nav">
            <paloma-checkout-nav></paloma-checkout-nav>
        </div>

        <div class="checkout-header__content">
            <h1 class="checkout__title">{{ title }}</h1>
            <p v-if="info" class="checkout__info">{{ info }}</p>
        </div>

    </header>
</template>

<script>
    import PalomaCheckoutNav from "./PalomaCheckoutNav";

    export default {
        name: "PalomaCheckoutHeader",

        components: {PalomaCheckoutNav},

        computed: {

            title() {

                const state = this.$route.name;

                return this.$trans('checkout.' + state + '.title');

            },

            info() {

                const state = this.$route.name;
                const info = this.$trans('checkout.' + state + '.info');

                return info === ('checkout.' + state + '.info') ? null : info;
            }
        }
    }
</script>