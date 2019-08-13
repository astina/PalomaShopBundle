<template>
    <div @click="showDetails" class="customer-order customer-order--summary">

        <div class="customer-order__title">

            <h4>{{ $trans('customer.order.title', {order_number: order.orderNumber, order_date: orderDate}) }}</h4>

            <router-link :to="{name: 'order_details', params: {order_number: order.orderNumber}}">
                {{ $trans('customer.order.view_details') }}
            </router-link>

        </div>

        <div class="customer-order__info">
            <div class="customer-order__status" :class="'customer-order__status--' + order.status">
                {{ $trans('customer.order.status') }}:
                <span class="customer-order__status-value">
                    {{ $trans('customer.order.status_' + order.status) }}
                </span>
            </div>
            <div class="customer-order__summary-total">
                {{ $trans('customer.order.order_total') }}
                <paloma-price :price="order.totalPrice"></paloma-price>
            </div>
        </div>

        <div class="customer-order__items">

            <div v-for="item in order.items" class="customer-order__item-tile">

                <div class="customer-order__item-image">
                    <paloma-image v-if="item.image" :image="item.image" size="small" dimension="64x64"></paloma-image>
                </div>

            </div>

        </div>

    </div>
</template>

<script>

    import moment from 'moment';
    import PalomaPrice from "../common/PalomaPrice";
    import PalomaImage from "../common/PalomaImage";

    export default {
        name: "PalomaCustomerOrderSummary",
        components: {PalomaImage, PalomaPrice},
        props: {
            order: Object
        },

        data() {

            const orderDate = moment(this.order.orderDate).format('DD.MM.YYYY HH:mm');

            return {
                orderDate: orderDate
            }
        },

        methods: {
            showDetails() {
                this.$router.push({name: 'order_details', params: {order_number: this.order.orderNumber}});
            }
        }
    }
</script>
