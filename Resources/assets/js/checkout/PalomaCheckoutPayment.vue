<template>
    <div class="checkout-payment">
        <router-view
                @payment-method-select="onPaymentMethodSelect"
                @address-update="onAddressUpdate"></router-view>
    </div>
</template>

<script>
    import paloma from "../paloma";

    export default {
        name: "PalomaCheckoutPayment",

        methods: {

            onPaymentMethodSelect() {

                const order = paloma.checkout.orderDraft();
                if (order.billing.address) {
                    this.$router.push({name: 'state_confirm'});
                } else {
                    this.$router.push({name: 'state_payment_address'});
                }
            },

            onAddressUpdate() {
                this.$router.push({name: 'state_confirm'});
            }
        }
    }
</script>

<style scoped>

</style>