<template>
    <section class="section">

        <div class="content">
            <h1>
                {{ $trans('customer.account.address_list') }}
            </h1>
        </div>

        <paloma-spinner :loading="loading"></paloma-spinner>

        <div v-if="customer" class="account__addresses">

            <div class="account-address account-address--contact">
                <p class="account-address__title">
                    {{ $trans('customer.account.address.contact') }}
                    <span class="icon account-address__icon">
                        <i class="fal fa-address-book"></i>
                    </span>
                </p>
                <paloma-address v-if="customer.contactAddress" :address="customer.contactAddress"></paloma-address>
                <router-link :to="{name: 'address_form', params: {type: 'contact'}}" class="button is-small">
                    <span class="icon is-small">
                        <i class="fal fa-pencil"></i>
                    </span>
                    <span>{{ $trans('button.edit') }}</span>
                </router-link>
            </div>

            <div class="account-address account-address--shipping">
                <p class="account-address__title">
                    {{ $trans('customer.account.address.shipping') }}
                    <span class="icon account-address__icon">
                        <i class="fal fa-truck"></i>
                    </span>
                </p>
                <paloma-address v-if="customer.shippingAddress" :address="customer.shippingAddress"></paloma-address>
                <router-link :to="{name: 'address_form', params: {type: 'shipping'}}" class="button is-small">
                    <span class="icon is-small">
                        <i class="fal fa-pencil"></i>
                    </span>
                    <span>{{ $trans('button.edit') }}</span>
                </router-link>
            </div>

            <div class="account-address account-address--billing">
                <p class="account-address__title">
                    {{ $trans('customer.account.address.billing') }}
                    <span class="icon account-address__icon">
                        <i class="fal fa-file-invoice"></i>
                    </span>
                </p>
                <paloma-address v-if="customer.billingAddress" :address="customer.billingAddress"></paloma-address>
                <router-link :to="{name: 'address_form', params: {type: 'billing'}}" class="button is-small">
                    <span class="icon is-small">
                        <i class="fal fa-pencil"></i>
                    </span>
                    <span>{{ $trans('button.edit') }}</span>
                </router-link>
            </div>

        </div>

    </section>
</template>

<script>

    import paloma from '../paloma';
    import PalomaSpinner from "../common/PalomaSpinner";
    import PalomaAddress from "../common/PalomaAddress";

    export default {
        name: "PalomaAccountAddressList",

        components: {PalomaAddress, PalomaSpinner},

        data() {
            return {
                loading: true,
                customer: null
            }
        },

        mounted() {
            this.loadCustomer();
        },

        methods: {

            loadCustomer() {

                this.loading = true;

                paloma.customer
                    .get()
                    .then(customer => {
                        this.customer = customer;
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            }
        }
    }
</script>
