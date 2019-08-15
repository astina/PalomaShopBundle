<template>
    <div>

        <div v-if="success">

            <p class="checkout__info">
                {{ $trans('customer.reset_password.success', {'email': emailInput}) }}
            </p>

            <div class="field is-grouped is-grouped-right form__buttons">
                <div class="control">
                    <router-link :to="{name: 'state_auth_login'}" class="button is-text user-login__password-reset">
                        {{ $trans('customer.reset_password.back_to_login') }}
                    </router-link>
                </div>
            </div>

        </div>

        <div v-else>

            <p class="checkout__info">
                {{ $trans('customer.reset_password.info') }}
            </p>

            <form @submit.prevent="submit" class="form" novalidate>

                <p v-show="error" class="form__error">
                    {{ $trans('customer.reset_password.error') }}
                </p>

                <fieldset :disabled="loading">

                    <div class="field form__field"
                         :class="{ 'form__field--invalid': $v.emailInput.$error }">

                        <label class="label" for="username">{{ $trans('field.email') }}</label>

                        <div class="control has-icons-left">
                            <input v-model.trim="$v.emailInput.$model"
                                   :class="{ 'is-danger': $v.emailInput.$error }"
                                   class="input" type="email" name="email" id="username" required>
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

                    <div class="field is-grouped is-grouped-right form__buttons">
                        <div class="control">
                            <router-link :to="{name: 'state_auth_login'}" class="button is-text">
                                {{ $trans('nav.back') }}
                            </router-link>
                        </div>
                        <div class="control">
                            <button class="button is-primary"
                                    :class="{'is-loading': loading}"
                                    type="submit">
                                {{ $trans('button.send') }}
                            </button>
                        </div>
                    </div>

                </fieldset>

            </form>

        </div>

    </div>
</template>

<script>

    import paloma from '../paloma';
    import {validationMixin} from 'vuelidate'
    import {email, required} from 'vuelidate/lib/validators'

    export default {
        name: "PalomaCheckoutAuthPasswordReset",

        mixins: [validationMixin],

        data() {

            const emailAddress = paloma.checkout.emailAddress();

            return {
                emailInput: emailAddress,
                loading: false,
                error: false,
                success: false,
            }
        },

        mounted() {
          if (!paloma.checkout.emailAddress()) {
              this.$router.push({name: 'state_auth_email'});
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
                this.success = false;

                paloma.user
                    .startPasswordReset(this.emailInput)
                    .then(() => {
                        this.error = false;
                        this.success = true;
                    })
                    .catch(() => {
                        this.error = true;
                        this.success = false;
                        this.loading = false;
                    });
            },
        }
    }
</script>
