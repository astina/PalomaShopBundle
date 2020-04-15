<template>
    <div>

        <section class="checkout__section">

            <div v-show="methods === null">
                <paloma-spinner></paloma-spinner>
            </div>

            <div v-for="method in methods"
                 @click.prevent="selectPaymentMethod(method)"
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
                    <div class="checkout-option__content">
                        <paloma-content :id="'payment-' + method.name"></paloma-content>
                    </div>

                    <div class="checkout-option__saved" v-if="method.paymentInstruments.length > 0">

                        <div class="checkout-payment-instrument checkout-payment-instrument--none"
                             @click.prevent="selectedInstrument = null"
                             :class="{'checkout-payment-instrument--active': method.name === selected && !selectedInstrument}">
                            <div class="checkout-payment-instrument__control">
                                <span class="icon">
                                    <i v-if="method.name === selected && !selectedInstrument" class="far fa-dot-circle"></i>
                                    <i v-else class="far fa-circle"></i>
                                </span>
                            </div>
                            <div class="checkout-payment-instrument__info">
                                <span class="checkout-payment-instrument__name">
                                    {{ $trans('payment.instrument.none') }}
                                </span>
                            </div>
                        </div>

                        <div v-for="instrument in method.paymentInstruments"
                             @click.prevent="selectedInstrument = instrument.id"
                             :class="{'checkout-payment-instrument--active': instrument.id === selectedInstrument}"
                             class="checkout-payment-instrument">

                            <div class="checkout-payment-instrument__control">
                                <span class="icon">
                                    <i v-if="instrument.id === selectedInstrument" class="far fa-dot-circle"></i>
                                    <i v-else class="far fa-circle"></i>
                                </span>
                            </div>

                            <div class="checkout-payment-instrument__info">
                                <span class="checkout-payment-instrument__name">
                                    {{ instrument.type }}
                                    {{ instrument.maskedCardNumber }}
                                </span>
                                <span class="checkout-payment-instrument__expiration">
                                    {{ instrument.expirationMonth }}/{{ instrument.expirationYear }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </section>

        <section v-if="order.billing.address" class="checkout__section">

            <h2 class="checkout__subtitle">
                {{ $trans('checkout.state_payment_address.title') }}
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
                                v-focus
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
    import PalomaContent from "../common/PalomaContent";

    export default {
        name: "PalomaCheckoutPaymentMethod",
        components: {PalomaContent, PalomaSpinner, PalomaAddress},
        data() {

            const order = paloma.checkout.orderDraft();

            return {
                order: order,
                methods: null,
                selected: null,
                selectedInstrument: null,
                loading: false,
            }
        },

        mounted() {

            this.loading = true;

            paloma.checkout
                .fetchPaymentMethods()
                .then(data => {
                    this.methods = data;
                    const selectedMethod = this.methods.find(m => m.selected);
                    this.selected = selectedMethod && selectedMethod.name;
                    if (selectedMethod) {
                        const selectedInstrument = (selectedMethod.paymentInstruments || []).find(i => i.selected);
                        this.selectedInstrument = selectedInstrument && selectedInstrument.id;
                    }
                })
                .finally(() => {
                    this.loading = false;
                });
        },

        methods: {

            submit() {

                this.loading = true;

                paloma.checkout
                    .setPaymentMethod(this.selected, this.selectedInstrument)
                    .then(() => {
                        this.$emit('payment-method-select');
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            },

            selectPaymentMethod(method) {
                this.selected = method.name;
                if (this.selectedInstrument
                    && !(method.paymentInstruments || []).find(i => i.id === this.selectedInstrument)) {
                    this.selectedInstrument = null;
                }
            }
        }
    }
</script>
