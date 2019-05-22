<template>
    <div>

        <p class="checkout__info">
            {{ $trans('checkout.state_auth.email_info') }}
        </p>

        <form @submit.prevent="submit" class="checkout-form" novalidate>

            <fieldset :disabled="loading">

                <div class="field checkout-form__field"
                     :class="{ 'checkout-form__field--invalid': $v.emailInput.$error }">

                    <label class="label">{{ $trans('field.email') }}</label>

                    <div class="control has-icons-left">
                        <input v-model.trim="$v.emailInput.$model"
                               v-focus
                               :class="{ 'is-danger': $v.emailInput.$error }"
                               class="input" type="email" name="email" required>
                        <span class="icon is-small is-left">
                          <i class="fal fa-envelope"></i>
                        </span>
                    </div>

                    <p v-if="!$v.emailInput.required" class="help is-danger">
                        {{ $trans('error.field.required') }}
                    </p>
                    <p v-if="!$v.emailInput.email" class="help is-danger">
                        {{ $trans('error.email.invalid') }}
                    </p>

                </div>

                <div class="field is-grouped is-grouped-right checkout-form__buttons">
                    <div class="control">
                        <button class="button is-primary"
                                :class="{'is-loading': loading}"
                                type="submit">
                            {{ $trans('checkout.next') }}
                        </button>
                    </div>
                </div>

            </fieldset>

        </form>

    </div>
</template>

<script>

    import paloma from '../paloma';
    import {validationMixin} from 'vuelidate'
    import {email, required} from 'vuelidate/lib/validators'

    export default {
        name: "PalomaCheckoutAuthEmail",

        mixins: [validationMixin],

        data() {
            return {
                emailInput: '',
                loading: false
            }
        },

        validations: {
            emailInput: {
                required,
                email
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
                    .existsCustomerByEmailAddress(this.emailInput)
                    .then(data => {
                        this.$emit('email-input', this.emailInput, data.exists);
                    })
                    .catch(() => {})
                    .finally(() => {
                        this.loading = false;
                    });
            }
        }
    }
</script>
