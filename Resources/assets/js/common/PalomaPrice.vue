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
        <span v-if="unit" class="price__unit">
            {{ $trans('catalog.per_unit') }}
            {{ unit }}
        </span>
    </span>
</template>

<script>
    export default {
        name: "PalomaPrice",

        props: {
            price: String,
            original: String,
            unit: String,
        },

        computed: {

            currency() {
                const pos = this.price.indexOf(' ');
                return this.price.substring(0, pos);
            },

            amount() {
                const pos = this.price.indexOf(' ');
                return this.price.substring(pos + 1);
            }
        }
    }
</script>

<style scoped>

</style>