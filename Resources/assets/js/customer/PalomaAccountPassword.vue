<template>
    <section class="section">

        <div class="content">
            <paloma-content id="account-password"></paloma-content>
        </div>

        <form @submit.prevent="submit" class="form form--password" novalidate>

            <p v-if="error" class="form__error">
                {{ $trans('customer.account.password_error') }}
            </p>

            <fieldset :disabled="loading">

                <div class="field form__field"
                     :class="{ 'form__field--invalid': $v.password_current.$error }">
                    <label class="label" for="p__password_current">{{ $trans('field.password_current') }}</label>
                    <div class="control">
                        <input v-model="password_current" class="input" type="password" id="p__password_current"
                               required
                               name="password">
                    </div>
                    <p v-if="!$v.password_current.required" class="help is-danger">
                        {{ $trans('error.field.required') }}
                    </p>
                </div>

                <div class="field form__field"
                     :class="{ 'form__field--invalid': $v.password_new.$error }">
                    <label class="label" for="p__password_new">{{ $trans('field.password_new') }}</label>
                    <div class="control">
                        <input v-model="password_new" class="input" type="password" id="p__password_new" required
                               name="password">
                    </div>
                    <p v-if="!$v.password_new.required" class="help is-danger">
                        {{ $trans('error.field.required') }}
                    </p>
                    <p v-if="!$v.password_new.minLength" class="help is-danger">
                        {{ $trans('error.password.too_short', {min: $v.password_new.$params.minLength.min}) }}
                    </p>
                </div>

                <div class="field form__field"
                     :class="{ 'form__field--invalid': $v.password_confirm.$error }">
                    <label class="label" for="p__password_confirm">{{ $trans('field.password_confirm') }}</label>
                    <div class="control">
                        <input v-model="password_confirm" class="input" type="password" id="p__password_confirm"
                               required
                               name="password_confirm">
                    </div>
                    <p v-if="!$v.password_confirm.confirmPassword" class="help is-danger">
                        {{ $trans('error.password.differs') }}
                    </p>
                </div>

                <div class="field is-grouped is-grouped-right form__buttons">
                    <div class="control">
                        <button class="button is-primary"
                                :class="{'is-loading': loading}"
                                type="submit">
                            {{ $trans('button.save') }}
                        </button>
                    </div>
                </div>

            </fieldset>

        </form>

    </section>
</template>

<script>

    import paloma from "../paloma";
    import PalomaContent from "../common/PalomaContent";
    import {validationMixin} from 'vuelidate';
    import {minLength, required, sameAs} from 'vuelidate/lib/validators';

    export default {
        name: "PalomaAccountPassword",

        components: {PalomaContent},

        mixins: [validationMixin],

        data() {

            return {
                password_current: null,
                password_new: null,
                password_confirm: null,
                error: false,
                loading: false
            }
        },

        validations: {
            password_current: {
                required,
            },
            password_new: {
                required,
                minLength: minLength(6)
            },
            password_confirm: {
                confirmPassword: sameAs('password_new')
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
                    .updatePassword(this.password_current, this.password_new)
                    .then(() => {
                        paloma.events.$emit('paloma.success', 'customer.account.password_saved');
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

<style scoped>

</style>