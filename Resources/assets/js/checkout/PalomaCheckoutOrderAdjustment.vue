<template>
    <div :class="className">
        <div class="checkout-order__adjustment-title">
            {{ title }}
        </div>
        <div class="checkout-order__adjustment-price">
            <paloma-price :price="price"></paloma-price>
        </div>
    </div>
</template>

<script>
    import PalomaPrice from "../common/PalomaPrice";

    export default {
        name: "PalomaCheckoutOrderAdjustment",

        components: {PalomaPrice},

        props: {
            type: {
                type: String,
                default: 'default'
            },
            title: String,
            price: String,
        },

        data() {
            return {
                className: 'checkout-order__adjustment checkout-order__adjustment--' + this.type,
            }
        }
    }
</script>
