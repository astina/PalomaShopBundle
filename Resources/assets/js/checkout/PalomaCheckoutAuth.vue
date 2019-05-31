<template>
    <div class="checkout-auth">

        <paloma-spinner :loading="loading"/>

        <router-view
                v-if="!loading"
                @email-input="onEmailInput"
                @login-success="onLoginSuccess"
                @customer-update="onCustomerUpdate"></router-view>

    </div>
</template>

<script>

    import paloma from '../paloma';
    import PalomaSpinner from "../common/PalomaSpinner";

    export default {
        name: "PalomaCheckoutAuth",

        components: {
            PalomaSpinner
        },

        data() {
            return {
                emailAddress: null,
                loading: false
            }
        },

        mounted() {

            if (this.$route.name !== 'state_auth') {
                return;
            }

            const user = paloma.user.get();

            if (user) {
                this.$router.push({'name': 'state_auth_user'});
            } else {
                this.$router.push({'name': 'state_auth_email'});
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

                this.loading = true;

                // Order should have been populated with user/customer data
                paloma.checkout
                    .refreshOrderDraft()
                    .then(() => {
                        this.$router.push({name: 'state_delivery'});
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            },

            onCustomerUpdate() {

                this.loading = true;

                // Order should have been populated with user/customer data
                paloma.checkout
                    .refreshOrderDraft()
                    .then(() => {
                        this.$router.push({name: 'state_delivery'});
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            }
        }
    }
</script>
