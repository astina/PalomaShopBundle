<template>
    <div class="checkout-delivery">
        <router-view
                @address-update="onAddressUpdate"
                @shipping-method-select="onShippingMethodSelect"
        ></router-view>
    </div>
</template>

<script>



    export default {
        name: "PalomaCheckoutDelivery",

        methods: {

            onAddressUpdate() {
                this.$router.push({name: 'state_delivery_method'});
            },

            onShippingMethodSelect() {
                this.$router.push({name: 'state_payment'});
            }
        }
    }
</script>
