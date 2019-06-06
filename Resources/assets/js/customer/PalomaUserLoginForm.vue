<template>

    <div>

        <p class="user-login__info">
            {{ $trans('customer.sign_in.info') }}
        </p>

        <form @submit.prevent="submit" class="form" novalidate>

            <p v-show="loginError" class="form__error">
                {{ $trans('customer.login_error') }}
            </p>

            <fieldset :disabled="loading">

                <div class="field form__field"
                     :class="{ 'form__field--invalid': $v.emailInput.$error }">

                    <label class="label">{{ $trans('field.email') }}</label>

                    <div class="control has-icons-left">
                        <input v-model.trim="emailInput"
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

                <div class="field form__field"
                     :class="{ 'form__field--invalid': $v.passwordInput.$error }">

                    <label class="label">{{ $trans('field.password') }}</label>

                    <div class="control has-icons-left">
                        <input v-model.trim="$v.passwordInput.$model"
                               :class="{ 'is-danger': $v.passwordInput.$error }"
                               class="input" type="password" name="password" required>
                        <span class="icon is-small is-left">
                              <i class="fal fa-key"></i>
                            </span>
                    </div>

                    <p v-if="!$v.passwordInput.required" class="help is-danger">
                        {{ $trans('error.field.required') }}
                    </p>

                </div>

                <div class="field is-grouped is-grouped-centered form__buttons">
                    <div class="control">
                        <a @click.prevent="startPasswordReset" class="button is-text user-login__password-reset" href="">
                            {{ $trans('customer.reset_password') }}
                        </a>
                    </div>
                    <div class="control">
                        <button class="button is-primary"
                                :class="{'is-loading': loading}"
                                type="submit">
                            {{ $trans('customer.sign_in') }}
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
        name: "PalomaUserLoginForm",

        mixins: [validationMixin],

        validations: {
            emailInput: {
                required,
                email
            },
            passwordInput: {
                required
            }
        },

        data() {

            return {
                emailInput: null,
                passwordInput: null,
                loading: false,
                loginError: false
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
                        this.loginError = false;
                        this.$emit('login-success');
                    })
                    .catch(() => {
                        this.loginError = true;
                        this.loading = false;
                    });
            },

            startPasswordReset() {
                this.$emit('start-password-reset');
            }
        }
    }
</script>
