<template>
    <div class="cart-item" :class="{'cart-item--highlighted': highlight}" v-if="!deleted">

        <div class="cart-item__image">
            <paloma-image v-if="item.image" :image="item.image" size="small"></paloma-image>
        </div>

        <div class="cart-item__product">
            <div class="cart-item__title">
                <a :href="productLink">
                    {{item.title}}
                </a>
                <div v-if="options.length > 0" class="cart-item__options">
                    <div v-for="option in options" class="cart-item__option">
                        {{ option.label }}: {{ option.value }}
                    </div>
                </div>
                <div v-else-if="item.productVariant && item.productVariant.name !== item.itemNumber" class="cart-item__options">
                    <div class="cart-item__option">
                        {{ item.productVariant.name }}
                    </div>
                </div>
            </div>
            <form class="cart-item__quantity">
                <div class="field has-addons">
                    <div class="control">
                        <button class="button is-outlined is-small"
                                :disabled="quantity <= 1"
                                :class="{'is-disabled': quantity <= 1}"
                                @click.prevent="reduceQuantity">
                            <span class="icon">
                                <i class="fal fa-minus"></i>
                            </span>
                        </button>
                    </div>
                    <div class="control">
                        <input class="input is-small" type="text" maxlength="4"
                               v-model="quantity"
                               @change="applyQuantity"
                               @keypress="validateQuantity"/>
                    </div>
                    <div class="control">
                        <button class="button is-outlined is-small"
                                :disabled="quantity >= availableQuantity"
                                :class="{'is-disabled': quantity >= availableQuantity}"
                                @click.prevent="increaseQuantity">
                            <span class="icon">
                                <i class="fal fa-plus"></i>
                            </span>
                        </button>
                    </div>
                    <div class="control cart-item__remove-control">
                        <button class="button is-text is-small"
                                @click.prevent="removeItem">
                            <span class="icon">
                                <i class="far fa-trash-alt"></i>
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="cart-item__price">
            <paloma-price :price="priceDisplay === 'net' ? item.netItemPrice : item.itemPrice"></paloma-price>
        </div>

    </div>
</template>

<script>

    import paloma from "../paloma";
    import PalomaImage from "../common/PalomaImage";
    import PalomaPrice from "../common/PalomaPrice";

    export default {
        name: "PalomaCartItem",

        components: {PalomaPrice, PalomaImage},

        props: {
            item: Object,
            priceDisplay: String,
            highlight: Boolean,
        },

        data() {
            return {

                quantity: null,
                availableQuantity: 0,

                options: this._createOptions(),

                deleted: false,

                productLink: paloma.router.resolve('catalog_product_locate', { sku: this.item.sku })
            }
        },

        mounted() {
            this.quantity = this.item.quantity;
            this.availableQuantity = this.item.productVariant.availability.availableQuantity;
        },

        methods: {
            applyQuantity() {

                let quantity = this.quantity;

                if (isNaN(quantity)) {
                    quantity = this.item.quantity;
                }
                if (quantity === '') {
                    quantity = 1;
                }

                quantity = parseInt(quantity);

                if (quantity < 1) {
                    quantity = 1;
                }

                this.quantity = quantity;

                this.emitEvent();
            },

            reduceQuantity() {
                if (this.quantity <= 1) {
                    return;
                }

                this.quantity--;
                this.emitEvent();
            },

            increaseQuantity() {
                if (this.quantity >= this.availableQuantity) {
                    return;
                }

                this.quantity++;
                this.emitEvent();
            },

            validateQuantity(event) {
                const charCode = (event.which) ? event.which : event.keyCode;
                if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                    event.preventDefault();
                }
            },

            removeItem() {

                this.deleted = true;

                paloma.events.$emit('paloma.cart_item_remove', this.item.id);
            },

            emitEvent() {
                paloma.events.$emit('paloma.cart_item_update', this.item.id, this.quantity);
            },

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