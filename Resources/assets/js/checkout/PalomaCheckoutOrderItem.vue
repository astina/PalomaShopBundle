<template>
    <div class="checkout-order-item">

        <div class="checkout-order-item__image">
            <paloma-image :image="item.image" size="small"></paloma-image>
        </div>

        <div class="checkout-order-item__product">
            <div class="checkout-order-item__title">
                {{item.title}}
                <div v-if="options.length > 0" class="checkout-order-item__options">
                    <div v-for="option in options" class="checkout-order-item__option">
                        {{ option.label }}: {{ option.value }}
                    </div>
                </div>
            </div>
        </div>

        <div class="checkout-order-item__price">
            <div class="checkout-order-item__unit">
                {{ item.quantity }} &times;
                <paloma-price :price="item.unitPrice" :original="item.originalPrice"></paloma-price>
            </div>
            <paloma-price :price="item.itemPrice"></paloma-price>
        </div>

    </div>
</template>

<script>
    import PalomaPrice from "../common/PalomaPrice";
    import PalomaImage from "../common/PalomaImage";

    export default {
        name: "PalomaCheckoutOrderItem",

        components: {PalomaImage, PalomaPrice},

        props: {
            item: Object
        },

        data() {
            return {
                options: this._createOptions()
            }
        },

        methods: {

            _createOptions() {

                if (!this.item.productVariant || !this.item.productVariant.options) {
                    return [];
                }

                const options = [];
                for (let opt in this.item.productVariant.options) {
                    options.push(this.item.productVariant.options[opt]);
                }

                return options;
            }
        }
    }
</script>

<style scoped>

</style>