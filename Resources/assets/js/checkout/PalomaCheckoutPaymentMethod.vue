<template>
    <div>

        <section class="checkout__section">

            <h1 class="checkout__title">
                {{ $trans('checkout.state_payment.payment_method') }}
            </h1>

            <p class="checkout__info">
                {{ $trans('checkout.state_payment.payment_method_info') }}
            </p>

            <div v-show="methods === null">
                <paloma-spinner></paloma-spinner>
            </div>

            <div v-for="method in methods"
                 @click.prevent="selected = method.name"
                 :class="{'checkout-option--active': method.name === selected}"
                 class="checkout-option">

                <div class="checkout-option__control">
                    <span v-if="method.name === selected" class="icon is-large">
                        <i class="far fa-dot-circle fa-2x"></i>
                    </span>
                    <span v-else class="icon is-large">
                        <i class="far fa-circle fa-2x"></i>
                    </span>
                </div>

                <div class="checkout-option__info">
                    <h3 class="checkout-option__title">{{ $trans('payment.' + method.name) }}</h3>
                    <p class="checkout-option__text">{{ $trans('payment.info.' + method.name) }}</p>
                    <div class="checkout-option__content" v-html="paymentMethodContent(method.name)"></div>
                </div>

            </div>

        </section>

        <section v-if="order.billing.address" class="checkout__section">

            <h2 class="checkout__subtitle">
                {{ $trans('checkout.state_payment.billing_address') }}
            </h2>

            <div class="m-b">
                <p v-if="order.sameShippingAndBillingAddress" class="checkout__text">
                    {{ $trans('checkout.billing_address.same_as_shipping') }}
                </p>
                <paloma-address v-else :address="order.billing.address"></paloma-address>
            </div>

            <router-link :to="{name: 'state_payment_address'}" class="button is-small">
                <span class="icon">
                    <i class="fal fa-pencil"></i>
                </span>
                <span>{{ $trans('button.edit') }}</span>
            </router-link>

            <form class="form" @submit.prevent="submit">
                <div class="field is-grouped is-grouped-right form__buttons">
                    <div class="control">
                        <router-link :to="{name: 'state_delivery'}" class="button is-text">
                            {{ $trans('nav.back') }}
                        </router-link>
                    </div>
                    <div class="control">
                        <button class="button is-primary"
                                :class="{'is-loading': loading}"
                                type="submit">
                            {{ $trans('checkout.next') }}
                        </button>
                    </div>
                </div>
            </form>
        </section>

    </div>
</template>

<script>

    import paloma from '../paloma';
    import PalomaAddress from "../common/PalomaAddress";
    import PalomaSpinner from "../common/PalomaSpinner";

    export default {
        name: "PalomaCheckoutPaymentMethod",
        components: {PalomaSpinner, PalomaAddress},
        data() {

            const order = paloma.checkout.orderDraft();

            return {
                order: order,
                methods: null,
                selected: null,
                loading: false,
            }
        },

        mounted() {

            this.loading = true;

            paloma.checkout
                .fetchPaymentMethods()
                .then(data => {
                    this.methods = data;
                    this.selected = this.methods.find(m => m.selected).name;
                })
                .finally(() => {
                    this.loading = false;
                });
        },

        methods: {

            submit() {

                this.loading = true;

                paloma.checkout
                    .setPaymentMethod(this.selected)
                    .then(() => {
                        this.$emit('payment-method-select');
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            },

            paymentMethodContent(name) {

                const elem = document.getElementById('content--payment-' + name);

                if (!elem) {
                    return '';
                }

                return elem.innerHTML;
            }
        }
    }
</script>
