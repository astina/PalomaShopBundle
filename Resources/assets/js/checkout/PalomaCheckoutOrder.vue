<template>
    <div class="checkout-order">

        <h2 class="checkout-order__title">
            {{ $trans('checkout.overview') }}
        </h2>

        <div v-if="order.shipping.address" class="checkout-order__address checkout-order__address--shipping">
            <h3 class="checkout-order__subtitle">
                {{ $trans('checkout.shipping_address') }}
            </h3>
            <paloma-address :address="order.shipping.address"></paloma-address>
        </div>

        <div v-if="order.billing.address" class="checkout-order__address checkout-order__address--billing">
            <h3 class="checkout-order__subtitle">
                {{ $trans('checkout.billing_address') }}
            </h3>
            <p v-if="order.sameShippingAndBillingAddress" class="checkout-order__address-same">
                {{ $trans('checkout.billing_address.same_as_shipping') }}
            </p>
            <paloma-address v-else :address="order.billing.address"></paloma-address>
        </div>

        <div class="checkout-order__items">

            <h3 class="checkout-order__subtitle">
                {{ $trans('order.items') }}
            </h3>

            <paloma-checkout-order-item
                    v-for="item in order.items"
                    :key="item.id"
                    :item="item"></paloma-checkout-order-item>

        </div>

        <paloma-checkout-order-adjustment
                :title="$trans('order.items_price')"
                :price="order.itemsPrice"
                type="subtotal"></paloma-checkout-order-adjustment>

        <div v-for="adjustment in order.surcharges">
            <paloma-checkout-order-adjustment
                    :title="adjustment.description"
                    :price="adjustment.price"
                    type="surcharge"></paloma-checkout-order-adjustment>
        </div>

        <paloma-checkout-order-adjustment
                v-if="order.shippingPrice"
                :title="shippingTitle"
                :price="order.shippingPrice"
                type="shipping"></paloma-checkout-order-adjustment>

        <div v-for="adjustment in order.reductions">
            <paloma-checkout-order-adjustment
                    :title="adjustment.description"
                    :price="adjustment.price"
                    type="reduction"></paloma-checkout-order-adjustment>
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
            <paloma-checkout-order-adjustment
                    :title="$trans('order.tax_incl') + ' ' + tax.description"
                    :price="tax.price"
                    type="tax"></paloma-checkout-order-adjustment>
        </div>

    </div>
</template>

<script>

    import paloma from '../paloma';
    import PalomaCheckoutOrderItem from "./PalomaCheckoutOrderItem";
    import PalomaCheckoutOrderAdjustment from "./PalomaCheckoutOrderAdjustment";
    import PalomaPrice from "../common/PalomaPrice";
    import PalomaAddress from "../common/PalomaAddress";

    export default {
        name: "PalomaCheckoutOrder",

        components: {
            PalomaCheckoutOrderAdjustment,
            PalomaCheckoutOrderItem,
            PalomaAddress,
            PalomaPrice
        },

        computed: {

            order() {
                return paloma.checkout.orderDraft();
            },

            shippingTitle() {

                const shippingMethod = this.$trans('shipping.' + this.order.shipping.shippingMethod);

                return this.$trans('order.shipping_price', {method: shippingMethod});
            }
        }
    }
</script>