<template>
    <section class="section">

        <div class="content">
            <h1>{{ $trans('customer.account.email') }}</h1>
        </div>

        <form @submit.prevent="submit" class="form form--email" novalidate>

            <p v-for="error in errors" class="form__error">
                {{ $trans('error.paloma.' + error.message) }}
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

    import paloma from '../paloma';
    import {validationMixin} from 'vuelidate'
    import {email, required} from 'vuelidate/lib/validators'

    export default {
        name: "PalomaAccountEmail",

        mixins: [validationMixin],

        data() {
            return {
                emailInput: '',
                errors: [],
                loading: false
            }
        },

        validations: {
            emailInput: {
                required,
                email
            }
        },

        mounted() {
            this.loadCustomer();
        },

        methods: {

            submit() {

                this.$v.$touch();
                if (this.$v.$invalid) {
                    return;
                }

                this.loading = true;

                paloma.customer
                    .updateEmailAddress(this.emailInput)
                    .then(() => {
                        paloma.events.$emit('paloma.success', 'customer.account.email_saved');
                    })
                    .catch(e => {
                        this.errors = e.errors;
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            },

            loadCustomer() {

                this.loading = true;

                paloma.customer
                    .get()
                    .then(customer => {
                        this.emailInput = customer.emailAddress;
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            },
        }
    }
</script>

<style scoped>

</style>