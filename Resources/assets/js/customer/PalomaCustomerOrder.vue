<template>
    <div class="customer-order">

        <div class="content">
            <h1>
                {{ $trans('customer.order.title', {order_number: order.orderNumber, order_date: orderDate}) }}
            </h1>
        </div>

        <div class="customer-order__info">
            <div class="customer-order__status" :class="'customer-order__status--' + order.status">
                {{ $trans('customer.order.status') }}:
                <span class="customer-order__status-value">
                    {{ $trans('customer.order.status_' + order.status) }}
                </span>
            </div>
            <a @click.prevent="goBack" class="button is-text is-pulled-right" href="">
                {{ $trans('nav.back_to_overview') }}
            </a>
        </div>

        <div v-if="invoicePdfDownloadUrl" class="customer-order__actions">

            <div class="buttons">
                <a class="button is-small" :href="invoicePdfDownloadUrl" target="_blank">
                    <span class="icon">
                        <i class="fal fa-file-invoice"></i>
                    </span>
                    <span>{{ $trans('customer.order.download_invoice_pdf') }}</span>
                </a>
            </div>

        </div>

        <div class="customer-order__section">

            <div class="customer-order__items">

                <p class="customer-order__title">
                    {{ $trans('order.items') }}
                </p>

                <paloma-customer-order-item
                        v-for="item in order.items"
                        :key="item.id"
                        :item="item"></paloma-customer-order-item>

            </div>

            <paloma-customer-order-adjustment
                    :title="$trans('order.items_price')"
                    :price="order.itemsPrice"
                    type="subtotal"></paloma-customer-order-adjustment>

            <div v-for="adjustment in order.surcharges">
                <paloma-customer-order-adjustment
                        :title="adjustment.description"
                        :price="adjustment.price"
                        type="surcharge"></paloma-customer-order-adjustment>
            </div>

            <paloma-customer-order-adjustment
                    v-if="order.shippingPrice"
                    :title="shippingTitle"
                    :price="order.shippingPrice"
                    type="shipping"></paloma-customer-order-adjustment>

            <div v-for="adjustment in order.reductions">
                <paloma-customer-order-adjustment
                        :title="adjustment.description"
                        :price="adjustment.price"
                        type="reduction"></paloma-customer-order-adjustment>
            </div>

            <div v-for="adjustment in order.taxes">
                <paloma-customer-order-adjustment
                        :title="adjustment.description"
                        :price="adjustment.price"
                        type="tax"></paloma-customer-order-adjustment>
            </div>

            <div class="checkout-order__total">
                <div class="checkout-order__total-title">
                    {{ $trans('order.total_price') }}
                </div>
                <div class="checkout-order__total-price">
                    <paloma-price :price="order.totalPrice"></paloma-price>
                </div>
            </div>

            <div v-for="tax in order.includedTaxes">
                <paloma-customer-order-adjustment
                        :title="$trans('order.tax_incl') + ' ' + tax.description"
                        :price="tax.price"
                        type="tax"></paloma-customer-order-adjustment>
            </div>

        </div>

        <div class="customer-order__section">

            <p class="customer-order__title customer-order__title--underlined">
                {{ $trans('customer.order.shipping.title') }}
                <span class="icon">
                    <i class="fal fa-truck"></i>
                </span>
            </p>

            <div class="columns">
                <div class="column">

                    <div class="customer-order__address">
                        <p class="customer-order__subtitle">
                            {{ $trans('customer.account.address.shipping') }}
                        </p>
                        <paloma-address :address="order.shipping.address"></paloma-address>
                    </div>

                </div>
                <div class="column">

                    <p class="customer-order__subtitle">
                        {{ $trans('customer.order.shipping.method') }}
                    </p>
                    <p>
                        {{ $trans('shipping.' + order.shipping.shippingMethod.name) }}
                    </p>

                    <div v-if="shippingTargetDate" class="m-t">
                        <p class="customer-order__subtitle">
                            {{ $trans('customer.order.shipping.targetDate') }}
                        </p>
                        <p>
                            {{ shippingTargetDate}}
                        </p>
                    </div>

                </div>
            </div>

        </div>

        <div class="customer-order__section">

            <p class="customer-order__title customer-order__title--underlined">
                {{ $trans('customer.order.billing.title') }}
                <span class="icon">
                    <i class="fal fa-file-invoice"></i>
                </span>
            </p>

            <div class="columns">
                <div class="column">

                    <div class="customer-order__address">
                        <p class="customer-order__subtitle">
                            {{ $trans('customer.account.address.billing') }}
                        </p>
                        <paloma-address :address="order.billing.address"></paloma-address>
                    </div>

                </div>
                <div class="column">

                    <p class="customer-order__subtitle">
                        {{ $trans('customer.order.billing.method') }}
                    </p>
                    <p>
                        {{ $trans('payment.' + order.billing.paymentMethod.name) }}
                    </p>

                </div>
            </div>

        </div>

    </div>
</template>

<script>

    import moment from 'moment';
    import config from '../paloma-config';
    import paloma from '../paloma';
    import PalomaCustomerOrderItem from "./PalomaCustomerOrderItem";
    import PalomaCustomerOrderAdjustment from "./PalomaCustomerOrderAdjustment";
    import PalomaPrice from "../common/PalomaPrice";
    import PalomaAddress from "../common/PalomaAddress";

    export default {
        name: "PalomaCustomerOrder",
        components: {PalomaAddress, PalomaPrice, PalomaCustomerOrderAdjustment, PalomaCustomerOrderItem},
        props: {
            order: {
                type: Object,
                required: true
            }
        },

        data() {

            const orderDate = moment(this.order.orderDate).format('DD.MM.YYYY HH:mm');

            const shippingMethod = this.$trans('shipping.' + this.order.shipping.shippingMethod.name);
            const shippingTitle = this.$trans('order.shipping_price', {method: shippingMethod});

            const shippingTargetDate = this.order.shipping.shippingMethod.targetDate
                && moment(this.order.shipping.shippingMethod.targetDate).format('DD.MM.YYYY');

            const invoicePdfDownloadUrl = config.account.orderInvoicePdfDownloadAvailable
                ? paloma.router.resolve('customer_order_invoice_pdf', {orderNumber: this.order.orderNumber})
                : null;

            return {
                orderDate: orderDate,
                shippingTitle: shippingTitle,
                shippingTargetDate: shippingTargetDate,
                invoicePdfDownloadUrl: invoicePdfDownloadUrl
            }
        },

        methods: {
            goBack() {
                this.$router.go(-1);
            }
        }
    }
</script>