<template>

    <form @submit.prevent="submit" class="form form--address" novalidate>

        <fieldset :disabled="loading">

            <div class="field form__field"
                 :class="{
                        'form__field--invalid': $v.address.titleCode.$error,
                        'form__field--required': $v.address.titleCode.$params.required
                     }">
                <label class="label">{{ $trans('field.address_title') }}</label>
                <div class="control">
                    <label v-for="option in model.titleCode.options" class="radio">
                        <input v-model="address.titleCode" :value="option.value" type="radio" name="title">
                        {{ option.label }}
                    </label>
                </div>
                <p v-if="!$v.address.titleCode.required" class="help is-danger">
                    {{ $trans('error.field.required') }}
                </p>
            </div>

            <div class="columns">
                <div class="column">
                    <div class="field form__field"
                         :class="{
                                'form__field--invalid': $v.address.firstName.$error,
                                'form__field--required': $v.address.firstName.$params.required
                            }">
                        <label class="label" for="address__first_name">{{ $trans('field.first_name') }}</label>
                        <div class="control">
                            <input v-model="address.firstName" v-focus class="input" type="text" id="address__first_name" required name="first_name">
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
                    <div class="field form__field"
                         :class="{
                                'form__field--invalid': $v.address.lastName.$error,
                                'form__field--required': $v.address.lastName.$params.required
                            }">
                        <label class="label" for="address__last_name">{{ $trans('field.last_name') }}</label>
                        <div class="control">
                            <input v-model="address.lastName" class="input" type="text" id="address__last_name" required name="last_name">
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

            <div class="field form__field"
                 :class="{
                        'form__field--invalid': $v.address.company.$error,
                        'form__field--required': $v.address.company.$params.required
                    }">
                <label class="label" for="address__company">{{ $trans('field.company') }}</label>
                <div class="control">
                    <input v-model="address.company" class="input" type="text" id="address__company" name="company">
                </div>
                <p v-if="!$v.address.company.maxLength" class="help is-danger">
                    {{ $trans('error.field.too_long', {max: $v.address.company.$params.maxLength.max}) }}
                </p>
            </div>

            <div class="field form__field"
                 :class="{
                        'form__field--invalid': $v.address.street.$error,
                        'form__field--required': $v.address.street.$params.required
                     }">
                <label class="label" for="address__street">{{ $trans('field.street') }}</label>
                <div class="control">
                    <input v-model="address.street" class="input" type="text" id="address__street" required name="street">
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
                    <div class="field form__field"
                         :class="{
                                'form__field--invalid': $v.address.zipCode.$error,
                                'form__field--required': $v.address.zipCode.$params.required
                             }">
                        <label class="label" for="address__zip_code">{{ $trans('field.zip_code') }}</label>
                        <div class="control">
                            <input v-model="address.zipCode" class="input" type="text" id="address__zip_code" required name="zip_code">
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
                    <div class="field form__field"
                         :class="{
                                'form__field--invalid': $v.address.city.$error,
                                'form__field--required': $v.address.city.$params.required
                             }">
                        <label class="label" for="address__city">{{ $trans('field.city') }}</label>
                        <div class="control">
                            <input v-model="address.city" class="input" type="text" id="address__city" required name="city">
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
                    <div class="field form__field"
                         :class="{
                                'form__field--invalid': $v.address.country.$error,
                                'form__field--required': $v.address.country.$params.required
                            }">
                        <label class="label" for="address__country">{{ $trans('field.country') }}</label>
                        <div class="control">
                            <div class="select is-fullwidth">
                                <select v-model="address.country" id="address__country" required name="country">
                                    <option v-for="option in model.country.options" :value="option.value">
                                        {{ option.label }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <p v-if="!$v.address.country.required" class="help is-danger">
                            {{ $trans('error.field.required') }}
                        </p>
                    </div>
                </div>
                <div class="column">
                    <div class="field form__field"
                         :class="{
                                'form__field--invalid': $v.address.phoneNumber.$error,
                                'form__field--required': $v.address.phoneNumber.$params.required
                            }">
                        <label class="label" for="address__phone_number">{{ $trans('field.phone_number') }}</label>
                        <div class="control">
                            <input v-model="address.phoneNumber" class="input" type="text" id="address__phone_number" name="phone_number">
                        </div>
                        <p v-if="!$v.address.phoneNumber.required" class="help is-danger">
                            {{ $trans('error.field.required') }}
                        </p>
                        <p v-if="!$v.address.phoneNumber.maxLength" class="help is-danger">
                            {{ $trans('error.field.too_long', {max: $v.address.phoneNumber.$params.maxLength.max}) }}
                        </p>
                    </div>
                </div>
            </div>

        </fieldset>

        <div class="field is-grouped is-grouped-right form__buttons">
            <div class="control">
                <a @click.prevent="cancel" class="button is-text">
                    {{ $trans(cancelLabel) }}
                </a>
            </div>
            <div class="control">
                <button class="button is-primary"
                        :class="{'is-loading': loading}"
                        type="submit">
                    {{ $trans(submitLabel) }}
                </button>
            </div>
        </div>

    </form>
</template>

<script>

    import {validationMixin} from 'vuelidate';
    import {maxLength, required} from 'vuelidate/lib/validators';

    export default {
        name: "PalomaAddressForm",

        mixins: [validationMixin],

        components: {
        },

        props: {
            address: Object,
            model: Object,
            submitLabel: String,
            cancelLabel: String,
            loading: {
                type: Boolean,
                default: false
            }
        },

        validations() {

            const validations = {
                titleCode: {
                    required: required
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
            };

            for (let prop in validations) {

                if (this.model.hasOwnProperty(prop) && validations.hasOwnProperty(prop)) {

                    const current = validations[prop];
                    const overwrite = this.model[prop];

                    if (overwrite.hasOwnProperty('required')) {
                        current.required = overwrite.required ? required  : null;
                    }
                    if (overwrite.hasOwnProperty('maxLength')) {
                        current.maxLength = maxLength(overwrite.maxLength);
                    }
                }
            }

            return {
                address: validations
            }
        },

        methods: {
            submit() {

                this.$v.$touch();
                if (this.$v.$invalid) {
                    return;
                }

                this.$emit('submit');
            },
            
            cancel() {
                this.$emit('cancel');
            }
        }
    }
</script>

<style scoped>

</style>