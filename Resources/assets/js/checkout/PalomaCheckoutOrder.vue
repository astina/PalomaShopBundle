<template>
    <div class="checkout-order" :class="{'checkout-order--expanded': expanded}">

        <div class="checkout-order__header" @click.prevent="toggleContent" >
            <h2 class="checkout-order__title">
                {{ $trans('checkout.overview') }}
            </h2>
            <div class="checkout-order__header-total">
                <paloma-price :price="order.totalPrice"></paloma-price>
                <a class="icon" href="">
                    <i v-if="!expanded" class="fal fa-angle-down"></i>
                    <i v-if="expanded" class="fal fa-angle-up"></i>
                </a>
            </div>
        </div>

        <div class="checkout-order__content">

            <paloma-checkout-order-modifications
                    v-if="order.modifications.length > 0"
                    :order="order"></paloma-checkout-order-modifications>

            <div v-if="order.shipping.address" class="checkout-order__address checkout-order__address--shipping">
                <div class="checkout-order__subtitle">
                    <h3>{{ $trans('checkout.shipping_address') }}</h3>
                    <router-link :to="{name: 'state_delivery_address'}" href="">{{ $trans('button.edit') }}</router-link>
                </div>
                <paloma-address :address="order.shipping.address"></paloma-address>
            </div>

            <div v-if="order.billing.address" class="checkout-order__address checkout-order__address--billing">
                <div class="checkout-order__subtitle">
                    <h3>{{ $trans('checkout.billing_address') }}</h3>
                    <router-link :to="{name: 'state_payment_address'}" href="">{{ $trans('button.edit') }}</router-link>
                </div>
                <p v-if="order.sameShippingAndBillingAddress" class="checkout-order__address-same">
                    {{ $trans('checkout.billing_address.same_as_shipping') }}
                </p>
                <paloma-address v-else :address="order.billing.address"></paloma-address>
            </div>

            <div class="checkout-order__items">

                <div class="checkout-order__subtitle">
                    <h3>{{ $trans('order.items') }}</h3>
                    <a :href="cartUrl">{{ $trans('button.edit') }}</a>
                </div>

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

            <div v-for="adjustment in order.taxes">
                <paloma-checkout-order-adjustment
                        :title="adjustment.description"
                        :price="adjustment.price"
                        type="tax"></paloma-checkout-order-adjustment>
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

    </div>
</template>

<script>

    import paloma from '../paloma';
    import PalomaCheckoutOrderItem from "./PalomaCheckoutOrderItem";
    import PalomaCheckoutOrderAdjustment from "./PalomaCheckoutOrderAdjustment";
    import PalomaCheckoutOrderModifications from "./PalomaCheckoutOrderModifications";
    import PalomaPrice from "../common/PalomaPrice";
    import PalomaAddress from "../common/PalomaAddress";

    export default {
        name: "PalomaCheckoutOrder",

        components: {
            PalomaCheckoutOrderModifications,
            PalomaCheckoutOrderAdjustment,
            PalomaCheckoutOrderItem,
            PalomaAddress,
            PalomaPrice
        },

        data() {
            return {
                cartUrl: paloma.router.resolve('catalog_home', null, 'cart'),
                expanded: false,
            }
        },

        watch: {
            '$route'() {

                // expand on 'confirm' state or if order has modifications
                this.expanded = this.$route.name === 'state_confirm'
                    || (this.order.modifications && this.order.modifications.length > 0);

                this.$emit('toggle-content', this.expanded);
            }
        },

        computed: {

            order() {
                return paloma.checkout.orderDraft();
            },

            shippingTitle() {

                const shippingMethod = this.$trans('shipping.' + this.order.shipping.shippingMethod.name);

                return this.$trans('order.shipping_price', {method: shippingMethod});
            }
        },

        methods: {
            toggleContent() {
                this.expanded = !this.expanded;
                this.$emit('toggle-content', this.expanded);
            }
        }
    }
</script>