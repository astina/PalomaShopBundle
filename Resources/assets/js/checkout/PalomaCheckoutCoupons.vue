<template>
    <div class="checkout-coupons">

        <h3 class="checkout-coupons__title">
            {{ $trans('checkout.coupons.title') }}
        </h3>

        <form @submit.prevent="submit">
            <fieldset :disabled="loading">

                <div class="field" :class="{'has-addons': couponInput}">
                    <div class="control">
                        <input v-model.trim="couponInput" class="input" type="text" :placeholder="$trans('checkout.coupons.enter')">
                    </div>
                    <div v-show="couponInput" class="control">
                        <button type="submit" class="button"
                                :class="{'is-loading': loading}">
                            {{ $trans('checkout.coupons.submit') }}
                        </button>
                    </div>
                </div>

                <p v-show="errors" class="help is-danger">
                    <span v-for="error in errors">
                        {{ $trans('error.coupon.' + error.status, {'coupon': error.coupon})}}
                    </span>
                </p>

            </fieldset>
        </form>

        <div v-for="coupon in coupons" class="checkout-coupons__coupon">
            <a @click.prevent="remove(coupon)" class="checkout-coupons__coupon-remove" href="">
                <span class="icon is-small">
                    <i class="fal fa-trash"></i>
                </span>
            </a>
            <span class="checkout-coupons__coupon-code">{{ coupon.code }}</span>
        </div>

    </div>
</template>

<script>

    import paloma from '../paloma';
    import utils from '../utils';

    export default {
        name: "PalomaCheckoutCoupons",

        data() {

            const order = paloma.checkout.orderDraft();

            return {
                couponInput: null,
                coupons: order.coupons,
                errors: null,
                loading: false
            }
        },

        methods: {

            submit() {

                if (!this.couponInput) {
                    return;
                }

                const code = String(this.couponInput);

                this.loading = true;
                this.errors = null;

                paloma.checkout
                    .addCouponCode(code)
                    .then(order => {
                        this.couponInput = null;
                        this.coupons = order.coupons;
                    })
                    .catch(error => {
                        this.errors = error.errors;
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            },

            remove(coupon) {

                this.loading = true;

                this.coupons = utils.removeElem(this.coupons, coupon);

                paloma.checkout
                    .removeCouponCode(coupon.code)
                    .then(order => {
                        this.coupons = order.coupons;
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            }
        }
    }
</script>

<style scoped>

</style>