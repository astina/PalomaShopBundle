<template>

    <section class="section">

        <div class="content">
            <h1>{{ $trans('customer.account.orders') }}</h1>
        </div>

        <paloma-spinner v-if="orders.length === 0" :loading="loading"></paloma-spinner>

        <div v-if="page && orders.length > 0" class="account__orders">

            <paloma-customer-order-summary
                    v-for="order in orders"
                    :key="order.orderNumber"
                    :order="order"></paloma-customer-order-summary>

            <div v-if="!page.last" class="buttons is-centered">

                <a @click.prevent="loadMore" class="button" :class="{'is-loading': loading}" href="">
                    {{ $trans('nav.show_more') }}
                </a>

            </div>

        </div>

        <p v-if="page && page.totalElements === 0" class="text-muted">
            {{ $trans('customer.account.no_orders') }}
        </p>

    </section>

</template>

<script>

    import paloma from '../paloma';
    import PalomaSpinner from "../common/PalomaSpinner";
    import PalomaCustomerOrderSummary from "./PalomaCustomerOrderSummary";

    export default {
        name: "PalomaAccountOrders",

        components: {
            PalomaSpinner,
            PalomaCustomerOrderSummary
        },

        data() {
            return {
                page: {
                    number: 0,
                },
                orders: [],
                loading: false
            }
        },

        mounted() {

            this.loadOrders();

        },

        methods: {
            loadOrders() {

                this.loading = true;

                paloma.customer
                    .listOrders(this.page.number)
                    .then(page => {
                        this.page = page;
                        page.content.forEach(order => this.orders.push(order));
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            },

            loadMore() {
                this.page.number++;
                this.loadOrders();
            }
        }
    }
</script>
