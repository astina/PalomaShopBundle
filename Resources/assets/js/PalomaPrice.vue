<template>
    <span class="price">
        <span class="price__currency">
            {{ currency }}
        </span>
        <span class="price__amount">
            {{ amount }}
        </span>
        <span v-if="original" class="price__original">
            {{ original }}
        </span>
    </span>
</template>

<script>
    export default {
        name: "PalomaPrice",
        props: {
            price: String,
            original: String
        },

        data() {

            const pos = this.price.indexOf(' ');
            const currency = this.price.substring(0, pos);
            const amount = this.price.substring(pos + 1);

            return {
                currency: currency,
                amount: amount,
            }
        }

    }
</script>

<style scoped>

</style>