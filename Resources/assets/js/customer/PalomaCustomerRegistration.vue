<template>
    <div>

        <form @submit.prevent="submit" class="form form--customer" novalidate>

            <fieldset :disabled="loading">

                <div class="field form__field form__field--required"
                     :class="{ 'form__field--invalid': $v.customer.title.$error }">
                    <label class="label">{{ $trans('field.address_title') }}</label>
                    <div class="control">
                        <label class="radio">
                            <input v-model="customer.title" :value="$trans('field.address_title.ms')" type="radio" name="title">
                            {{ $trans('field.address_title.ms') }}
                        </label>
                        <label class="radio">
                            <input v-model="customer.title" :value="$trans('field.address_title.mr')" type="radio" name="title">
                            {{ $trans('field.address_title.mr') }}
                        </label>
                    </div>
                    <p v-if="!$v.customer.title.required" class="help is-danger">
                        {{ $trans('error.field.required') }}
                    </p>
                </div>

                <div class="columns">
                    <div class="column">
                        <div class="field form__field form__field--required"
                             :class="{ 'form__field--invalid': $v.customer.firstName.$error }">
                            <label class="label" for="da__first_name">{{ $trans('field.first_name') }}</label>
                            <div class="control">
                                <input v-model="customer.firstName" class="input" type="text" id="da__first_name" required name="first_name">
                            </div>
                            <p v-if="!$v.customer.firstName.required" class="help is-danger">
                                {{ $trans('error.field.required') }}
                            </p>
                            <p v-if="!$v.customer.firstName.maxLength" class="help is-danger">
                                {{ $trans('error.field.too_long', {max: $v.customer.firstName.$params.maxLength.max}) }}
                            </p>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field form__field form__field--required"
                             :class="{ 'form__field--invalid': $v.customer.lastName.$error }">
                            <label class="label" for="da__last_name">{{ $trans('field.last_name') }}</label>
                            <div class="control">
                                <input v-model="customer.lastName" class="input" type="text" id="da__last_name" required name="last_name">
                            </div>
                            <p v-if="!$v.customer.lastName.required" class="help is-danger">
                                {{ $trans('error.field.required') }}
                            </p>
                            <p v-if="!$v.customer.lastName.maxLength" class="help is-danger">
                                {{ $trans('error.field.too_long', {max: $v.customer.lastName.$params.maxLength.max}) }}
                            </p>
                        </div>
                    </div>
                </div>

            </fieldset>

            <div class="field is-grouped is-grouped-right form__buttons">
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

    import paloma from "../paloma";
    import {validationMixin} from 'vuelidate';
    import {maxLength, required} from 'vuelidate/lib/validators';

    export default {
        name: "PalomaCustomerRegistration",

        mixins: [validationMixin],

        data() {
            return {
                customer: {

                },
                loading: false
            }
        },

        validations: {
            customer: {
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

                paloma.customer
                    .register(this.customer)
                    .then(() => {
                        this.$emit('customer-registered');
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            }
        }
    }
</script>
