<template>
    <div>

        <h1 class="checkout__title">
            {{ $trans('checkout.state_delivery.shipping_method') }}
        </h1>

        <div v-show="methods === null">
            <paloma-spinner></paloma-spinner>
        </div>

        <div v-for="method in methods"
             @click.prevent="selected = method.name"
             :class="{'checkout-option--active': method.name === selected}"
             class="checkout-option">

            <div class="checkout-option__control">
                <span v-if="method.name === selected" class="icon is-large">
                    <i class="far fa-dot-circle fa-2x"></i>
                </span>
                <span v-else class="icon is-large">
                    <i class="far fa-circle fa-2x"></i>
                </span>
            </div>

            <div class="checkout-option__info">
                <h3 class="checkout-option__title">{{ $trans('shipping.' + method.name) }}</h3>
                <p class="checkout-option__text">{{ $trans('shipping.info.' + method.name) }}</p>
            </div>

            <div class="checkout-option__price">
                <span v-if="method.free" class="checkout-option__free">{{ $trans('shipping.free') }}</span>
                <paloma-price v-else :price="method.price"></paloma-price>
            </div>

        </div>

        <form class="form" @submit.prevent="submit">
            <div class="field is-grouped is-grouped-right form__buttons">
                <div class="control">
                    <router-link :to="{name: 'state_delivery_address'}" class="button is-text">
                        {{ $trans('nav.back') }}
                    </router-link>
                </div>
                <div class="control">
                    <button class="button is-primary"
                            :class="{'is-loading': loading}"
                            type="submit">
                        {{ $trans('checkout.next') }}
                    </button>
                </div>
            </div>
        </form>

    </div>
</template>

<script>

    import paloma from '../paloma';
    import PalomaPrice from "../common/PalomaPrice";
    import PalomaSpinner from "../common/PalomaSpinner";

    export default {
        name: "PalomaCheckoutDeliveryMethod",
        components: {PalomaSpinner, PalomaPrice},
        data() {
            return {
                methods: null,
                selected: null,
                loading: false,
            }
        },

        mounted() {

            this.loading = true;

            paloma.checkout
                .fetchShippingMethods()
                .then(data => {
                    this.methods = data;
                    this.selected = this.methods.find(m => m.selected).name;
                })
                .finally(() => {
                    this.loading = false;
                });
        },

        methods: {

            submit() {

                this.loading = true;

                paloma.checkout
                    .setShippingMethod(this.selected)
                    .then(() => {
                        this.$emit('shipping-method-select');
                    })
                    .finally(() => {
                        this.loading = false;
                    });

            }
        }
    }
</script>