<template>

    <section class="section">

        <div class="content">
            <h1>
                {{ $trans('customer.account.payment_instruments') }}
            </h1>
        </div>

        <paloma-spinner :loading="loading"></paloma-spinner>

        <p v-if="paymentInstruments && paymentInstruments.length === 0" class="text-muted">
            {{ $trans('customer.account.no_payment_instruments') }}
        </p>

        <div v-else class="account__payment-instruments">

            <div v-for="paymentInstrument in paymentInstruments" class="account-payment-instrument">
                <div class="account-payment-instrument__type">
                    {{ paymentInstrument.type }}
                </div>
                <div class="account-payment-instrument__card-number">
                    {{ paymentInstrument.maskedCardNumber }}
                </div>
                <div class="account-payment-instrument__expiration">
                    {{ paymentInstrument.expirationMonth }}/{{ paymentInstrument.expirationYear }}
                </div>
                <div class="account-payment-instrument__actions">
                    <button class="button is-text is-small"
                            @click.prevent="deletePaymentInstrument(paymentInstrument)">
                            <span class="icon">
                                <i class="far fa-trash-alt"></i>
                            </span>
                    </button>
                </div>
            </div>

        </div>

    </section>
</template>

<script>
    import PalomaSpinner from "../common/PalomaSpinner";
    import paloma from "../paloma";

    export default {
        name: "PalomaAccountPaymentInstruments",

        components: {PalomaSpinner},

        data() {
            return {
                loading: true,
                paymentInstruments: null,
            }
        },

        mounted() {
            this.loadPaymentInstruments();
        },

        methods: {

            loadPaymentInstruments() {
                this.loading = true;

                paloma.customer
                    .listPaymentInstruments()
                    .then(paymentInstruments => {
                        this.paymentInstruments = paymentInstruments;
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            },

            deletePaymentInstrument(paymentInstrument) {

                // TODO show modal
                if (confirm(this.$trans('customer.account.confirm_delete_payment_instrument'))) {
                    paloma.customer
                        .deletePaymentInstrument(paymentInstrument.id)
                        .then(this.loadPaymentInstruments);
                }
            }
        }
    }
</script>

<style scoped>

</style>