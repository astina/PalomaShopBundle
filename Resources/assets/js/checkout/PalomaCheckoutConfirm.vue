<template>
    <div class="checkout-confirm">
        <h1 class="checkout__title">Confirm</h1>

        <a @click.prevent="purchase" class="button is-primary">Purchase</a>
    </div>
</template>

<script>

    import paloma from '../paloma';

    export default {
        name: "PalomaCheckoutConfirm",

        methods: {
            purchase() {
                paloma.checkout.purchase()
                    .then(() => {
                        window.location.href = paloma.router.resolve('checkout_success');
                    });
            }
        }
    }
</script>

<style scoped>

</style>