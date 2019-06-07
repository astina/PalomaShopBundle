<template>
    <div :class="className">
        <div class="customer-order__adjustment-title">
            {{ title }}
        </div>
        <div class="customer-order__adjustment-price">
            <paloma-price :price="price"></paloma-price>
        </div>
    </div>
</template>

<script>
    import PalomaPrice from "../common/PalomaPrice";

    export default {
        name: "PalomaCustomerOrderAdjustment",

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
                className: 'customer-order__adjustment customer-order__adjustment--' + this.type,
            }
        }
    }
</script>
