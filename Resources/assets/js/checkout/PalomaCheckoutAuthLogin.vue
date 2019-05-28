<template>
    <div>

        <p class="checkout__info">
            {{ $trans('checkout.state_auth.login_info') }}
        </p>

        <form @submit.prevent="submit" class="form" novalidate>

            <p v-show="loginError" class="form__error">
                {{ $trans('checkout.state_auth.login_error') }}
            </p>

            <fieldset :disabled="loading">

                <div class="field form__field"
                     :class="{ 'form__field--invalid': $v.emailInput.$error }">

                    <label class="label">{{ $trans('field.email') }}</label>

                    <div class="control has-icons-left">
                        <input v-model.trim="$v.emailInput.$model"
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

                <div class="field form__field"
                     :class="{ 'form__field--invalid': $v.passwordInput.$error }">

                    <label class="label">{{ $trans('field.password') }}</label>

                    <div class="control has-icons-left">
                        <input v-model.trim="$v.passwordInput.$model"
                               v-focus
                               :class="{ 'is-danger': $v.passwordInput.$error }"
                               class="input" type="password" name="password" required>
                        <span class="icon is-small is-left">
                          <i class="fal fa-key"></i>
                        </span>
                    </div>

                    <p v-if="!$v.emailInput.required" class="help is-danger">
                        {{ $trans('error.field.required') }}
                    </p>

                </div>

                <div class="field is-grouped is-grouped-right form__buttons">
                    <div class="control">
                        <router-link :to="{name: 'state_auth_email'}" class="button is-text">
                            {{ $trans('nav.back') }}
                        </router-link>
                    </div>
                    <div class="control">
                        <button class="button is-primary"
                                :class="{'is-loading': loading}"
                                type="submit">
                            {{ $trans('checkout.state_auth.login') }}
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
        name: "PalomaCheckoutAuthLogin",

        mixins: [validationMixin],

        data() {

            const emailAddress = paloma.checkout.emailAddress();

            return {
                emailInput: emailAddress,
                passwordInput: null,
                loading: false,
                loginError: false
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
            },
            passwordInput: {
                required
            }
        },

        methods: {
            submit() {

                this.$v.$touch();
                if (this.$v.$invalid) {
                    return;
                }

                this.loading = true;

                paloma.user
                    .authenticate(this.emailInput, this.passwordInput)
                    .then(() => {
                        this.$emit('login-success');
                    })
                    .catch(() => {
                        this.loginError = true;
                    })
                    .finally(() => {
                        this.loading = false;
                    });

            }
        }
    }
</script>
