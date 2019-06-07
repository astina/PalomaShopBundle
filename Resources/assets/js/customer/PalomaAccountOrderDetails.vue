<template>
    <section class="section">

        <paloma-spinner :loading="loading"></paloma-spinner>

        <paloma-customer-order v-if="order" :order="order"></paloma-customer-order>

    </section>
</template>

<script>

    import paloma from '../paloma';
    import PalomaSpinner from "../common/PalomaSpinner";
    import PalomaCustomerOrder from "./PalomaCustomerOrder";

    export default {
        name: "PalomaAccountOrderDetails",

        components: {PalomaCustomerOrder, PalomaSpinner},

        data() {
            return {
                order: null,
                loading: false
            }
        },

        mounted() {

            const orderNumber = this.$route.params['order_number'];

            this.loading = true;

            paloma.customer
                .getOrder(orderNumber)
                .then(order => {
                    this.order = order;
                })
                .catch(error => {
                    // TODO
                })
                .finally(() => {
                    this.loading = false;
                });

        }
    }
</script>