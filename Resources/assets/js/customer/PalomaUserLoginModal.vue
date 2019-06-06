<template>

    <div class="modal is-active">
        <div class="modal-background" @click="dismiss"></div>
        <div class="modal-card user-login">

            <header class="modal-card-head">
                <p v-if="view === 'login'" class="modal-card-title">
                    {{ $trans('customer.sign_in') }}
                </p>
                <p v-else-if="view === 'password-reset'" class="modal-card-title">
                    {{ $trans('customer.reset_password') }}
                </p>
                <button class="delete" @click="dismiss"></button>
            </header>

            <section class="modal-card-body">

                <paloma-user-login-form
                        v-if="view === 'login'"
                        @login-success="onLoginSuccess"
                        @start-password-reset="onStartPasswordReset"></paloma-user-login-form>

                <paloma-user-password-reset
                        v-else-if="view === 'password-reset'"
                        @cancel="onPasswordResetCancel"></paloma-user-password-reset>

            </section>
            <div class="modal-card-foot">
                <a class="button is-text" href="">
                    {{ $trans('customer.new_customer') }}
                </a>
            </div>
        </div>
    </div>

</template>

<script>

    import PalomaUserLoginForm from "./PalomaUserLoginForm";
    import PalomaUserPasswordReset from "./PalomaUserPasswordReset";

    export default {

        name: "PalomaUserLoginModal",

        components: {PalomaUserPasswordReset, PalomaUserLoginForm},

        data() {

            return {
                view: 'login',
            }
        },

        props: {
            show: Boolean
        },

        methods: {

            onLoginSuccess() {
                this.$emit('login-success');
            },

            onStartPasswordReset() {
                this.view = 'password-reset';
            },

            onPasswordResetCancel() {
                this.view = 'login';
            },

            dismiss() {
                this.$emit('dismiss');
            }
        }
    }
</script>