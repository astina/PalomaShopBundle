<template>
    <div class="user-password-reset">

        <div v-if="valid">

            <div class="content">

                <h1>{{ $trans('customer.change_password') }}</h1>

                <p>{{ $trans('customer.change_password.info') }}</p>

            </div>

            <form @submit.prevent="submit" class="form" novalidate>

                <p v-show="error" class="form__error">
                    {{ $trans('customer.reset_password.error') }}
                </p>

                <fieldset :disabled="loading">

                    <div class="field form__field"
                         :class="{ 'form__field--invalid': $v.password.$error }">

                        <label class="label">{{ $trans('field.password_new') }}</label>

                        <div class="control has-icons-left">
                            <input v-model.trim="password"
                                   :class="{ 'is-danger': $v.password.$error }"
                                   class="input" type="password" name="password" required>
                            <span class="icon is-small is-left">
                              <i class="fal fa-key"></i>
                            </span>
                        </div>

                        <p v-if="!$v.password.required" class="help is-danger">
                            {{ $trans('error.field.required') }}
                        </p>

                        <p v-if="!$v.password.minLength" class="help is-danger">
                            {{ $trans('error.password.too_short', {min: $v.password.$params.minLength.min}) }}
                        </p>

                    </div>

                    <div class="field form__field"
                         :class="{ 'form__field--invalid': $v.passwordConfirm.$error }">

                        <label class="label">{{ $trans('field.password_confirm') }}</label>

                        <div class="control has-icons-left">
                            <input v-model.trim="passwordConfirm"
                                   :class="{ 'is-danger': $v.passwordConfirm.$error }"
                                   class="input" type="password" name="password" required>
                            <span class="icon is-small is-left">
                              <i class="fal fa-key"></i>
                            </span>
                        </div>

                        <p v-if="!$v.passwordConfirm.confirmPassword" class="help is-danger">
                            {{ $trans('error.password.differs') }}
                        </p>

                    </div>

                    <div class="field is-grouped is-grouped-centered form__buttons">
                        <div class="control">
                            <button class="button is-primary"
                                    :class="{'is-loading': loading}"
                                    type="submit">
                                {{ $trans('customer.change_password') }}
                            </button>
                        </div>
                    </div>

                </fieldset>

            </form>
        </div>

        <div v-else>

            <div class="content">

                <h1>{{ $trans('customer.change_password.token_invalid') }}</h1>

                <p>{{ $trans('customer.change_password.token_invalid.info') }}</p>

            </div>
        </div>
    </div>
</template>

<script>

    import paloma from "../paloma";
    import {validationMixin} from 'vuelidate';
    import {minLength, required, sameAs} from 'vuelidate/lib/validators';

    export default {
        name: "PalomaUserPasswordResetConfirm",

        mixins: [validationMixin],

        props: {
            token: String,
            valid: Boolean
        },

        data() {
            return {
                password: null,
                passwordConfirm: null,
                loading: false,
                error: false,
            }
        },

        validations: {
            password: {
                required,
                minLength: minLength(6)
            },
            passwordConfirm: {
                confirmPassword: sameAs('password')
            }
        },

        methods: {
            submit() {

                this.$v.$touch();
                if (this.$v.$invalid) {
                    return;
                }

                this.loading = true;
                this.error = false;

                paloma.user
                    .completePasswordReset(this.token, this.password)
                    .then(result => {
                        this.error = false;
                        window.location.href = result._links.forward.href;
                    })
                    .catch(() => {
                        this.error = true;
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            }
        }
    }
</script>
