<template>
    <div class="checkout-payment">
        <router-view @address-update="onAddressUpdate"></router-view>
    </div>
</template>

<script>
    export default {
        name: "PalomaCheckoutPayment",

        methods: {
            onAddressUpdate() {
                this.$router.push({name: 'state_confirm'});
            }
        }
    }
</script>

<style scoped>

</style>