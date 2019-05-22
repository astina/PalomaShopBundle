<template>
    <header class="checkout-header">
        <div class="checkout-header__logo">
            Logo
        </div>
        <div class="checkout-header__nav">
            <paloma-checkout-nav></paloma-checkout-nav>
        </div>
    </header>
</template>

<script>
    import PalomaCheckoutNav from "./PalomaCheckoutNav";

    export default {
        name: "PalomaCheckoutHeader",
        components: {PalomaCheckoutNav}
    }
</script>

<style scoped>

</style>