<template>
    <div v-show="hasModifications()" class="checkout-order__modifications">

        <div class="message is-info">
            <div class="message-body">
                <p>{{ $trans('checkout.order_modifications.info') }}</p>
                <ul>
                    <li v-for="modification in order.modifications">
                        {{ $trans('checkout.order_modifications.' + modification.code, modification.params) }}
                    </li>
                </ul>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        name: 'PalomaCheckoutOrderModifications',

        props: {
            order: Object
        },

        methods: {
            hasModifications() {
                return this.order.modifications && this.order.modifications.length > 0;
            }
        }
    }
</script>
