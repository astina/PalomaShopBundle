<template>
    <div class="cart-item" :class="{'cart-item--highlighted': highlight}">

        <div class="cart-item__image">
            <paloma-image :image="item.image" size="small"></paloma-image>
        </div>

        <div class="cart-item__product">
            <div class="cart-item__title">{{item.title}}</div>
            {{item.quantity}} &times;
        </div>

        <div class="cart-item__price">
            <paloma-price :price="item.itemPrice"></paloma-price>
        </div>

    </div>
</template>

<script>

    import PalomaImage from "./PalomaImage";
    import PalomaPrice from "./PalomaPrice";

    export default {
        name: "PalomaCartItem",

        components: {PalomaPrice, PalomaImage},

        props: {
            item: Object,
            highlight: Boolean
        }
    }
</script>

<style scoped>

</style>