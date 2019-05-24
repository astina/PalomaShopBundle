<template>
    <div class="checkout-auth">

        <h1 class="checkout__title">
            {{ $trans('checkout.state_auth.title') }}
        </h1>

        <router-view
                @email-input="onEmailInput"
                @login-success="onLoginSuccess"
                @customer-update="onCustomerUpdate"></router-view>

    </div>
</template>

<script>

    import paloma from '../paloma';

    export default {
        name: "PalomaCheckoutAuth",

        components: {
        },

        data() {
            return {
                emailAddress: null,
            }
        },

        methods: {

            onEmailInput(emailAddress, userExists) {

                paloma.checkout.setEmailAddress(emailAddress);

                if (userExists) {
                    this.$router.push({name: 'state_auth_login'});
                } else {
                    this.$router.push({name: 'state_auth_register'});
                }
            },

            onLoginSuccess() {

                // Order should have been populated with user/customer data
                paloma.checkout.refreshOrderDraft();

                this.$router.push({name: 'state_delivery'});
            },

            onCustomerUpdate() {

                // Order should have been populated with user/customer data
                paloma.checkout.refreshOrderDraft();

                this.$router.push({name: 'state_delivery'});
            }
        }
    }
</script>
