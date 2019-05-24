<template>
    <div class="checkout-confirm">

        <h1 class="checkout__title">
            {{ $trans('checkout.state_confirm.title') }}
        </h1>

        <p class="checkout__info">
            {{ $trans('checkout.state_confirm.info') }}
        </p>

        <section class="checkout__section m-t">
            <paloma-checkout-coupons></paloma-checkout-coupons>
        </section>

        <form class="form form--purchase" @submit.prevent="purchase">
            <div class="field">
                <div class="control">
                    <button class="button is-primary" :class="{'is-loading': purchasing}">
                        {{ $trans('checkout.purchase_and_pay') }}
                    </button>
                </div>
                <p class="checkout__text checkout__text--small m-t">
                    {{ $trans('checkout.purchase.info') }}
                </p>
            </div>
        </form>
    </div>
</template>

<script>

    import paloma from '../paloma';
    import PalomaCheckoutCoupons from "./PalomaCheckoutCoupons";

    export default {
        name: "PalomaCheckoutConfirm",

        components: {PalomaCheckoutCoupons},

        data() {
            return {
                purchasing: false
            }
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
                        this.purchasing = true;
                    });
            }
        }
    }
</script>

<style scoped>

</style>