<template>
    <div>
        <div class="dropdown is-right" :class="{'is-active': showDropdown}" v-click-outside="closeDropdown">
            <div class="dropdown-trigger">
                <a class="button is-white" @click.prevent="showDropdown = !showDropdown">
                    <span class="icon">
                        <i class="fal fa-user"></i>
                    </span>
                    <span class="is-hidden-mobile">
                        {{ name ? name : $trans('customer.sign_in') }}
                    </span>
                </a>
            </div>
            <div class="dropdown-menu user-dropdown">
                <div v-if="user" class="dropdown-content">
                    <a class="dropdown-item" :href="accountView()">
                        {{ $trans('customer.account.overview') }}
                    </a>
                    <a class="dropdown-item" :href="accountView('orders')">
                        {{ $trans('customer.account.orders') }}
                    </a>
                    <a class="dropdown-item" :href="accountView('addresses')">
                        {{ $trans('customer.account.addresses') }}
                    </a>
                    <hr class="dropdown-divider">
                    <div class="dropdown-item">
                        <a class="button is-fullwidth is-small user-dropdown__logout" :href="logoutUrl">
                            <span class="icon">
                                <i class="fal fa-sign-out"></i>
                            </span>
                            <span>{{ $trans('customer.logout') }}</span>
                        </a>
                    </div>
                </div>
                <div v-else class="dropdown-content">
                    <div class="dropdown-item">

                        <p class="user-dropdown__info">
                            {{ $trans('customer.existing_customer') }}
                        </p>

                        <a @click.prevent="showLoginModal = true" class="button is-primary is-fullwidth user-dropdown__login" href="">
                            {{ $trans('customer.sign_in') }}
                        </a>

                    </div>
                    <hr class="dropdown-divider">
                    <div class="dropdown-item">
                        <a class="user-dropdown__register" :href="registerUrl">
                            {{ $trans('customer.new_customer') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <paloma-user-login-modal
                v-if="showLoginModal"
                @dismiss="showLoginModal = false"
                @login-success="onLoginSuccess"></paloma-user-login-modal>

    </div>
</template>

<script>

    import paloma from '../paloma';
    import PalomaUserLoginModal from "./PalomaUserLoginModal";

    export default {
        name: "PalomaUser",

        components: {PalomaUserLoginModal},

        props: {
            user: Object
        },

        data() {

            return {
                logoutUrl: paloma.router.resolve('security_logout'),
                registerUrl: paloma.router.resolve('customer_register'),
                showDropdown: false,
                showLoginModal: false,
            }
        },

        computed: {
            name() {
                if (!this.user) {
                    return null;
                }

                if (this.user.customer) {
                    return (this.user.customer.firstName + ' ' + this.user.customer.lastName).trim();
                }

                return this.user.name;
            }
        },

        methods: {

            accountView(view) {
                return paloma.router.resolve('customer_account') + (view || '');
            },

            onLoginSuccess() {
                this.closeDropdown();
                window.location.href = this.accountView();
            },

            closeDropdown() {
                this.showDropdown = false;
            }
        }
    }
</script>