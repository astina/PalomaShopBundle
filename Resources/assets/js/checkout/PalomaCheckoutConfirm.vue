<template>
    <div class="checkout-confirm">

        <paloma-spinner :loading="loading"></paloma-spinner>

        <div v-if="!loading">

            <section class="checkout__section m-t">
                <paloma-checkout-coupons></paloma-checkout-coupons>
            </section>

            <form class="form form--purchase" @submit.prevent="purchase">
                <div class="field">
                    <div class="control">
                        <button class="button is-primary"
                                :class="{'is-loading': purchasing || redirecting}"
                                :disabled="purchasing || redirecting">
                            {{ $trans(paymentRequired ? 'checkout.purchase_and_pay' : 'checkout.purchase') }}
                        </button>
                    </div>
                    <paloma-content id="checkout-purchase-info"></paloma-content>
                </div>
            </form>

        </div>
    </div>
</template>

<script>

    import paloma from '../paloma';
    import PalomaCheckoutCoupons from "./PalomaCheckoutCoupons";
    import PalomaSpinner from "../common/PalomaSpinner";
    import PalomaContent from "../common/PalomaContent";

    export default {
        name: "PalomaCheckoutConfirm",

        components: {PalomaContent, PalomaSpinner, PalomaCheckoutCoupons},

        data() {
            return {
                purchasing: false,
                redirecting: false,
                loading: false
            }
        },

        computed: {
            paymentRequired() {

                const order = paloma.checkout.orderDraft();

                return order.requiresPaymentDuringCheckout;
            }
        },

        mounted() {

            this.loading = true;

            paloma.checkout
                .finalize()
                .then(() => {
                    this.loading = false;
                })
                .catch(() => {
                    window.location.href = paloma.router.resolve('checkout_start');
                });
        },

        methods: {
            purchase() {

                this.purchasing = true;

                paloma.checkout
                    .purchase()
                    .then(result => {
                        this.redirecting = true;
                        window.location.href = result._links.forward.href;
                    })
                    .finally(() => {
                        this.purchasing = false;
                    });
            }
        }
    }
</script>

<style scoped>

</style>