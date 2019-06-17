<template>
    <div>

        <paloma-address-form :address="address"
                             :model="addressModel"
                             :loading="loading"
                             @submit="submit"
                             @cancel="back"
                             submit-label="button.save"
                             cancel-label="nav.back"></paloma-address-form>


    </div>
</template>

<script>

    import utils from "../utils";
    import paloma from "../paloma";
    import PalomaAddressForm from "../common/PalomaAddressForm";

    export default {
        name: "PalomaCheckoutPaymentAddress",

        components: {
            PalomaAddressForm
        },

        data() {

            const order = paloma.checkout.orderDraft();

            const address = utils.clone(order.billing.address || {});

            if (!address.firstName && !address.lastName && order.customer) {
                address.firstName = order.customer.firstName;
                address.lastName = order.customer.lastName;
            }

            if (!address.company && order.customer) {
                address.company = order.customer.company;
            }

            if (!address.titleCode && order.customer && order.customer.gender) {
                address.titleCode = paloma.customer.titleCodeForGender(order.customer.gender);
            }

            return {
                address: address,
                addressModel: order._validation.billing.address,
                loading: false,
            }
        },

        methods: {

            submit() {

                this.loading = true;

                paloma.checkout
                    .setBillingAddress(this.address)
                    .then(() => {
                        this.$emit('address-update');
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            },

            back() {
                this.$router.push({name: 'state_payment'});
            }
        }
    }
</script>

<style scoped>

</style>