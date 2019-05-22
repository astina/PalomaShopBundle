<template>
    <nav class="checkout-nav">
        <router-link v-for="item in items"
                     :key="item.name"
                     :to="item.path"
                     class="checkout-nav__item"
                     active-class="checkout-nav__item--active"
                     :class="{ 'checkout-nav__item--complete': item.complete, 'checkout-nav__item--disabled': item.disabled }">
            <span v-show="!item.complete" class="checkout-nav__item-number">
                {{ item.step }}
            </span>
            <span v-show="item.complete" class="checkout-nav__item-number">
                <span class="icon">
                    <i class="far fa-check"></i>
                </span>
            </span>
            <span class="checkout-nav__item-text">
                {{ $trans('checkout.' + item.name + '.name') }}
            </span>
        </router-link>
    </nav>
</template>

<script>
    export default {
        name: "PalomaCheckoutNav",

        computed: {

            items() {

                return this.$router.options.routes.map(route => {
                    return {
                        name: route.name,
                        path: route.path,
                        step: route.meta.step,
                        complete: route.meta.step < this.$route.meta.step,
                        disabled: route.meta.step > this.$route.meta.step
                    }
                });
            }
        }
    }
</script>