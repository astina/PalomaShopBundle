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

            </fieldset>
        </form>

    </div>
</template>

<script>

    export default {
        name: "PalomaCheckoutCoupons",

        data() {
            return {
                couponInput: null,
                loading: false
            }
        },

        methods: {

            submit() {

                if (!this.couponInput) {
                    return;
                }

            }
        }
    }
</script>

<style scoped>

</style>