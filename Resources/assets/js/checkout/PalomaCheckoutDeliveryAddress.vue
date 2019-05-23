<template>
    <div>

        <h1 class="checkout__title">
            {{ $trans('checkout.state_delivery.shipping_address') }}
        </h1>

        <form @submit.prevent="submit" class="checkout-form checkout-form--address" novalidate>

            <fieldset :disabled="loading">

                <div class="field checkout-form__field checkout-form__field--required"
                     :class="{ 'checkout-form__field--invalid': $v.address.title.$error }">
                    <label class="label">{{ $trans('field.address_title') }}</label>
                    <div class="control">
                        <label class="radio">
                            <input v-model="address.title" :value="$trans('field.address_title.ms')" type="radio" name="title">
                            {{ $trans('field.address_title.ms') }}
                        </label>
                        <label class="radio">
                            <input v-model="address.title" :value="$trans('field.address_title.mr')" type="radio" name="title">
                            {{ $trans('field.address_title.mr') }}
                        </label>
                    </div>
                    <p v-if="!$v.address.title.required" class="help is-danger">
                        {{ $trans('error.field.required') }}
                    </p>
                </div>

                <div class="columns">
                    <div class="column">
                        <div class="field checkout-form__field checkout-form__field--required"
                             :class="{ 'checkout-form__field--invalid': $v.address.firstName.$error }">
                            <label class="label" for="da__first_name">{{ $trans('field.first_name') }}</label>
                            <div class="control">
                                <input v-model="address.firstName" class="input" type="text" id="da__first_name" required name="first_name">
                            </div>
                            <p v-if="!$v.address.firstName.required" class="help is-danger">
                                {{ $trans('error.field.required') }}
                            </p>
                            <p v-if="!$v.address.firstName.maxLength" class="help is-danger">
                                {{ $trans('error.field.too_long', {max: $v.address.firstName.$params.maxLength.max}) }}
                            </p>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field checkout-form__field checkout-form__field--required"
                             :class="{ 'checkout-form__field--invalid': $v.address.lastName.$error }">
                            <label class="label" for="da__last_name">{{ $trans('field.last_name') }}</label>
                            <div class="control">
                                <input v-model="address.lastName" class="input" type="text" id="da__last_name" required name="last_name">
                            </div>
                            <p v-if="!$v.address.lastName.required" class="help is-danger">
                                {{ $trans('error.field.required') }}
                            </p>
                            <p v-if="!$v.address.lastName.maxLength" class="help is-danger">
                                {{ $trans('error.field.too_long', {max: $v.address.lastName.$params.maxLength.max}) }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="field checkout-form__field"
                     :class="{ 'checkout-form__field--invalid': $v.address.company.$error }">
                    <label class="label" for="da__company">{{ $trans('field.company') }}</label>
                    <div class="control">
                        <input v-model="address.company" class="input" type="text" id="da__company" name="company">
                    </div>
                    <p v-if="!$v.address.company.maxLength" class="help is-danger">
                        {{ $trans('error.field.too_long', {max: $v.address.company.$params.maxLength.max}) }}
                    </p>
                </div>

                <div class="field checkout-form__field checkout-form__field--required"
                     :class="{ 'checkout-form__field--invalid': $v.address.street.$error }">
                    <label class="label" for="da__street">{{ $trans('field.street') }}</label>
                    <div class="control">
                        <input v-model="address.street" class="input" type="text" id="da__street" required name="street">
                    </div>
                    <p v-if="!$v.address.street.required" class="help is-danger">
                        {{ $trans('error.field.required') }}
                    </p>
                    <p v-if="!$v.address.street.maxLength" class="help is-danger">
                        {{ $trans('error.field.too_long', {max: $v.address.street.$params.maxLength.max}) }}
                    </p>
                </div>

                <div class="columns">
                    <div class="column">
                        <div class="field checkout-form__field checkout-form__field--required"
                             :class="{ 'checkout-form__field--invalid': $v.address.zipCode.$error }">
                            <label class="label" for="da__zip_code">{{ $trans('field.zip_code') }}</label>
                            <div class="control">
                                <input v-model="address.zipCode" class="input" type="text" id="da__zip_code" required name="zip_code">
                            </div>
                            <p v-if="!$v.address.zipCode.required" class="help is-danger">
                                {{ $trans('error.field.required') }}
                            </p>
                            <p v-if="!$v.address.zipCode.maxLength" class="help is-danger">
                                {{ $trans('error.field.too_long', {max: $v.address.zipCode.$params.maxLength.max}) }}
                            </p>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field checkout-form__field checkout-form__field--required"
                             :class="{ 'checkout-form__field--invalid': $v.address.city.$error }">
                            <label class="label" for="da__city">{{ $trans('field.city') }}</label>
                            <div class="control">
                                <input v-model="address.city" class="input" type="text" id="da__city" required name="city">
                            </div>
                            <p v-if="!$v.address.city.required" class="help is-danger">
                                {{ $trans('error.field.required') }}
                            </p>
                            <p v-if="!$v.address.city.maxLength" class="help is-danger">
                                {{ $trans('error.field.too_long', {max: $v.address.city.$params.maxLength.max}) }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="columns">
                    <div class="column">
                        <div class="field checkout-form__field checkout-form__field--required"
                             :class="{ 'checkout-form__field--invalid': $v.address.country.$error }">
                            <label class="label" for="da__country">{{ $trans('field.country') }}</label>
                            <div class="control">
                                <div class="select is-fullwidth">
                                    <select v-model="address.country" id="da__country" required name="country">
                                        <option>CH</option>
                                    </select>
                                </div>
                            </div>
                            <p v-if="!$v.address.country.required" class="help is-danger">
                                {{ $trans('error.field.required') }}
                            </p>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field checkout-form__field"
                             :class="{ 'checkout-form__field--invalid': $v.address.phoneNumber.$error }">
                            <label class="label" for="da__phone_number">{{ $trans('field.phone_number') }}</label>
                            <div class="control">
                                <input v-model="address.phoneNumber" class="input" type="text" id="da__phone_number" name="phone_number">
                            </div>
                            <p v-if="!$v.address.phoneNumber.maxLength" class="help is-danger">
                                {{ $trans('error.field.too_long', {max: $v.address.phoneNumber.$params.maxLength.max}) }}
                            </p>
                        </div>
                    </div>
                </div>

            </fieldset>

            <div class="field is-grouped is-grouped-right checkout-form__buttons">
                <div class="control">
                    <router-link :to="{name: 'state_auth'}" class="button is-text">
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

    import utils from "../utils";
    import paloma from "../paloma";
    import {validationMixin} from 'vuelidate';
    import {maxLength, required} from 'vuelidate/lib/validators';

    export default {
        name: "PalomaCheckoutDeliveryAddress",

        mixins: [validationMixin],

        components: {
        },

        data() {

            const order = paloma.checkout.orderDraft();

            return {
                address: utils.clone(order.shipping.address || {}),
                loading: false,
            }
        },

        validations: {
            address: {
                title: {
                    required
                },
                firstName: {
                    required,
                    maxLength: maxLength(30)
                },
                lastName: {
                    required,
                    maxLength: maxLength(30)
                },
                company: {
                    maxLength: maxLength(50)
                },
                street: {
                    required,
                    maxLength: maxLength(50)
                },
                zipCode: {
                    required,
                    maxLength: maxLength(10)
                },
                city: {
                    required,
                    maxLength: maxLength(30)
                },
                country: {
                    required,
                    maxLength: maxLength(2)
                },
                phoneNumber: {
                    maxLength: maxLength(30)
                },
            }
        },

        methods: {
            submit() {

                this.$v.$touch();
                if (this.$v.$invalid) {
                    return;
                }

                this.loading = true;

                paloma.checkout
                    .setShippingAddress(this.address)
                    .then(() => {
                        this.$emit('address-update');
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            }
        }
    }
</script>

<style scoped>

</style>