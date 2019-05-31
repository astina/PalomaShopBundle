<template>
    <div v-if="user">

        <p class="checkout__info">
            {{ $trans('checkout.state_auth.user_info', {username: user.username}) }}
        </p>

        <p>
            <a @click.prevent="logout" class="button is-small">
                    <span class="icon">
                        <i class="fal fa-sign-out"></i>
                    </span>
                <span>{{ $trans('button.logout') }}</span>
            </a>
        </p>

        <div class="field is-grouped is-grouped-right form__buttons">
            <div class="control">
                <router-link :to="{name: 'state_delivery'}" class="button is-primary">
                    {{ $trans('checkout.next') }}
                </router-link>
            </div>
        </div>

    </div>
</template>

<script>

    import paloma from '../paloma';

    export default {
        name: "PalomaCheckoutAuthUser",

        data() {
            return {
                user: null,
            }
        },

        mounted() {

            const user = paloma.user.get();

            if (user === null) {
                this.$router.push({name: 'state_auth'});
            }

            this.user = user;
        },

        methods: {
            logout() {
                paloma.user
                    .logout()
                    .then(() => {

                        // refresh to get a new csrf token
                        window.location.reload();

                        // this.$router.push({name: 'state_auth_email'});
                    });
            }
        }
    }
</script>