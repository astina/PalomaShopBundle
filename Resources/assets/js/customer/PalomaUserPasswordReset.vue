<template>

    <div>

        <div v-if="success">

            <p class="user-login__info">
                {{ $trans('customer.reset_password.success', {'email': emailInput}) }}
            </p>

            <div class="field is-grouped is-grouped-centered form__buttons">
                <div class="control">
                    <a @click.prevent="cancel" class="button is-text user-login__password-reset" href="">
                        {{ $trans('customer.reset_password.back_to_login') }}
                    </a>
                </div>
            </div>

        </div>

        <div v-else>

            <p class="user-login__info">
                {{ $trans('customer.reset_password.info') }}
            </p>

            <form @submit.prevent="submit" class="form" novalidate>

                <p v-show="error" class="form__error">
                    {{ $trans('customer.reset_password.error') }}
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

                    <div class="field is-grouped is-grouped-centered form__buttons">
                        <div class="control">
                            <a @click.prevent="cancel" class="button is-text user-login__password-reset" href="">
                                {{ $trans('customer.reset_password.cancel') }}
                            </a>
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
        name: "PalomaUserPasswordReset",

        mixins: [validationMixin],

        validations: {
            emailInput: {
                required,
                email
            },
        },

        data() {

            return {
                emailInput: null,
                loading: false,
                error: false,
                success: false,
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

            cancel() {
                this.$emit('cancel');
            }
        }
    }
</script>
