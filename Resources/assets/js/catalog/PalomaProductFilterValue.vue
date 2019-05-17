<template>
    <div :class="{'filter__value--active': active }"
         @click="toggle"
         class="dropdown-item filter__value">
        <span class="filter__label">{{ value.label }}</span>
        <span class="filter__count">{{ value.count }}</span>
    </div>
</template>

<script>
    export default {
        name: "PalomaProductFilterValue",

        props: {
            value: Object,
            active: Boolean,
        },

        methods: {
            toggle() {
                this.$emit('value-toggle', this.value);
            }
        }
    }
</script>

<style scoped>

</style>