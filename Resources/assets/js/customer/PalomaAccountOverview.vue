<template>

    <div>

        <section class="section">
            <div class="content">
                <paloma-content id="account-overview"></paloma-content>
            </div>
        </section>

        <section class="section">

            <div class="account__section-title">
                <h3>{{ $trans('customer.account.last_order') }}</h3>
                <router-link v-if="!order.loading && !order.no_orders" :to="{name: 'order_list'}">
                    {{ $trans('nav.show_all') }}
                </router-link>
            </div>

            <paloma-spinner :loading="order.loading"></paloma-spinner>

            <paloma-customer-order-summary
                    v-if="order.last_order"
                    :order="order.last_order"></paloma-customer-order-summary>

            <p v-if="order.no_orders" class="text-muted">
                {{ $trans('customer.account.no_orders') }}
            </p>

        </section>

        <section class="section p-b0">

            <div class="account__section-title">
                <h3>{{ $trans('customer.account.address_list') }}</h3>
                <router-link v-if="!customer.loading && !customer.no_addresses" :to="{name: 'address_list'}">
                    {{ $trans('nav.show') }}
                </router-link>
            </div>

            <paloma-spinner :loading="customer.loading"></paloma-spinner>

            <div v-if="customer.customer && !customer.no_addresses" class="account__addresses">

                <div v-if="customer.customer.contactAddress"  class="account-address account-address--contact">
                    <p class="account-address__title">
                        {{ $trans('customer.account.address.contact') }}
                        <span class="icon account-address__icon">
                            <i class="fal fa-address-book"></i>
                        </span>
                    </p>
                    <paloma-address :address="customer.customer.contactAddress"></paloma-address>
                </div>

                <div v-if="customer.customer.shippingAddress" class="account-address account-address--shipping">
                    <p class="account-address__title">
                        {{ $trans('customer.account.address.shipping') }}
                        <span class="icon account-address__icon">
                            <i class="fal fa-truck"></i>
                        </span>
                    </p>
                    <paloma-address :address="customer.customer.shippingAddress"></paloma-address>
                </div>

                <div v-if="customer.customer.billingAddress" class="account-address account-address--billing">
                    <p class="account-address__title">
                        {{ $trans('customer.account.address.billing') }}
                        <span class="icon account-address__icon">
                            <i class="fal fa-file-invoice"></i>
                        </span>
                    </p>
                    <paloma-address :address="customer.customer.billingAddress"></paloma-address>
                </div>

            </div>

            <p v-if="customer.no_addresses" class="text-muted">
                {{ $trans('customer.account.no_addresses') }}
            </p>

        </section>

        <section class="section">

            <div class="account__section-title">
                <h3>{{ $trans('customer.account.user') }}</h3>
                <router-link :to="{name: 'email'}" v-if="!customer.loading">
                    {{ $trans('nav.edit') }}
                </router-link>
            </div>

            <p v-if="customer.customer">{{ customer.customer.emailAddress }}</p>

        </section>

    </div>

</template>

<script>

    import paloma from '../paloma';
    import PalomaContent from "../common/PalomaContent";
    import PalomaSpinner from "../common/PalomaSpinner";
    import PalomaCustomerOrderSummary from "./PalomaCustomerOrderSummary";
    import PalomaAddress from "../common/PalomaAddress";

    export default {
        name: "PalomaAccountOverview",

        components: {PalomaAddress, PalomaCustomerOrderSummary, PalomaSpinner, PalomaContent},

        data() {
            return {
                order: {
                    loading: true,
                    last_order: null,
                    no_orders: false,
                },
                customer: {
                    loading: true,
                    customer: null,
                    no_addresses: false
                },
            }
        },

        mounted() {

            this.loadLastOrder();
            this.loadCustomer();

        },

        methods: {

            loadLastOrder() {

                this.order.loading = true;

                paloma.customer
                    .getLastOrder()
                    .then(order => {
                        this.order.last_order = order;
                        this.order.no_orders = !order;
                    })
                    .finally(() => {
                        this.order.loading = false;
                    });
            },

            loadCustomer() {

                this.customer.loading = true;

                paloma.customer
                    .get()
                    .then(customer => {
                        this.customer.customer = customer;
                        this.customer.no_addresses =
                            customer.contactAddress === null
                            && customer.shippingAddress === null
                            && customer.billingAddress === null
                        ;
                    })
                    .finally(() => {
                        this.customer.loading = false;
                    });
            }
        }
    }
</script>
