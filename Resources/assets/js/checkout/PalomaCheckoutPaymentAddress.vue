<template>
    <div>

        <h1 class="checkout__title">
            {{ $trans('checkout.state_payment.billing_address') }}
        </h1>

        <paloma-address-form :address="address"
                             :loading="loading"
                             @submit="submit"
                             @cancel="back"
                             submit-label="checkout.next"
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

            return {
                address: address,
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