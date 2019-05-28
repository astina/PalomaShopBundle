<template>
    <div class="checkout-confirm">

        <h1 class="checkout__title">
            {{ $trans('checkout.state_confirm.title') }}
        </h1>

        <p class="checkout__info">
            {{ $trans('checkout.state_confirm.info') }}
        </p>

        <paloma-spinner :loading="loading"></paloma-spinner>

        <div v-if="!loading">

            <section class="checkout__section m-t">
                <paloma-checkout-coupons></paloma-checkout-coupons>
            </section>

            <form class="form form--purchase" @submit.prevent="purchase">
                <div class="field">
                    <div class="control">
                        <button class="button is-primary" :class="{'is-loading': purchasing}">
                            {{ $trans(paymentRequired ? 'checkout.purchase_and_pay' : 'checkout.purchase') }}
                        </button>
                    </div>
                    <p class="checkout__text checkout__text--small m-t">
                        {{ $trans('checkout.purchase.info') }}
                    </p>
                </div>
            </form>

        </div>
    </div>
</template>

<script>

    import paloma from '../paloma';
    import PalomaCheckoutCoupons from "./PalomaCheckoutCoupons";
    import PalomaSpinner from "../common/PalomaSpinner";

    export default {
        name: "PalomaCheckoutConfirm",

        components: {PalomaSpinner, PalomaCheckoutCoupons},

        data() {
            return {
                purchasing: false,
                loading: false
            }
        },

        computed: {
            paymentRequired() {

                const order = paloma.checkout.orderDraft();

                return order.billing.paymentMethod
                    && order.billing.paymentMethod.requiresPaymentDuringCheckout;
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