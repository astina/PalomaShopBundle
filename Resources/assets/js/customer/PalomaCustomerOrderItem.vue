<template>
    <div class="customer-order-item">

        <div class="customer-order-item__image">
            <paloma-image v-if="item.image" :image="item.image" size="small"></paloma-image>
        </div>

        <div class="customer-order-item__product">
            <div class="customer-order-item__title">
                <span class="customer-order-item__name">{{item.title}}</span>
                <span class="customer-order-item__sku">
                    {{ $trans('customer.order.item.sku') }}
                    {{item.sku}}
                    <span v-if="item.code && item.code !== item.sku" class="customer-order-item__code">({{item.code}})</span>
                </span>
                <div v-if="options.length > 0" class="customer-order-item__options">
                    <div v-for="option in options" class="customer-order-item__option">
                        {{ option.label }}: {{ option.value }}
                    </div>
                </div>
                <div v-else-if="item.productVariant && item.productVariant.name !== item.itemNumber" class="customer-order-item__options">
                    <div class="customer-order-item__option">
                        {{ item.productVariant.name }}
                    </div>
                </div>
            </div>
        </div>

        <div class="customer-order-item__price">
            <div class="customer-order-item__unit">
                {{ item.unit ? $trans('cart.n_items_at', {quantity: item.quantity, unit: item.unit}) : (item.quantity + '&times;') }}
                <paloma-price :price="item.unitPrice" :original="item.originalPrice"></paloma-price>
            </div>
            <paloma-price :price="item.itemPrice"></paloma-price>
        </div>
    </div>
</template>

<script>
import PalomaImage from "../common/PalomaImage";
import PalomaPrice from "../common/PalomaPrice";

export default {
        name: "PalomaCustomerOrderItem",

        components: {PalomaPrice, PalomaImage},

        props: {
            item: {
                type: Object,
                required: true
            }
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