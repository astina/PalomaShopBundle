<template>
    <div class="cart-item" :class="{'cart-item--highlighted': highlight}">

        <div class="cart-item__image">
            <paloma-image :image="item.image" size="small"></paloma-image>
        </div>

        <div class="cart-item__product">
            <div class="cart-item__title">
                <a :href="productLink">
                    {{item.title}}
                </a>
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
            <paloma-price :price="item.itemPrice"></paloma-price>
        </div>

    </div>
</template>

<script>

    import paloma from "./paloma";
    import PalomaImage from "./PalomaImage";
    import PalomaPrice from "./PalomaPrice";

    export default {
        name: "PalomaCartItem",

        components: {PalomaPrice, PalomaImage},

        props: {
            item: Object,
            highlight: Boolean
        },

        data() {
            return {

                quantity: null,
                availableQuantity: 0,

                productLink: paloma.router.resolve('catalog_product_locate', { sku: this.item.sku })
            }
        },

        mounted() {
            this.quantity = this.item.quantity;
            this.availableQuantity = 100; // TODO
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
                paloma.events.$emit('paloma.cart_item_remove', this.item.id);
            },

            emitEvent() {
                paloma.events.$emit('paloma.cart_item_update', this.item.id, this.quantity);
            }
        }
    }
</script>

<style scoped>

</style>