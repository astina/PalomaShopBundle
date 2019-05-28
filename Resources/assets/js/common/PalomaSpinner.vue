<template>
    <span class="icon" v-show="loading">
        <i class="fal fa-spinner-third fa-spin"></i>
    </span>
</template>

<script>
    export default {
        name: "PalomaSpinner",

        props: {
            loading: {
                type: Boolean,
                default: true
            }
        }
    }
</script>

<style scoped>

</style>