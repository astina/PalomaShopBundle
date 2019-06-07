<template>
    <div class="checkout">

        <div class="checkout__header">
            <paloma-checkout-header></paloma-checkout-header>
        </div>

        <div class="checkout__order"
             :class="{'checkout__order--expanded': orderContentExpanded, 'checkout__order--hidden-mobile': !showOrderMobile}">
            <div class="checkout__order-inner">
                <a class="checkout__abort" :href="abortUrl">
                    <span class="icon">
                        <i class="fal fa-arrow-left"></i>
                    </span>
                    <span class="checkout__abort-label">{{ $trans('checkout.abort') }}</span>
                </a>
                <paloma-checkout-order @toggle-content="onToggleOrderContent"></paloma-checkout-order>
            </div>
        </div>

        <div class="checkout__main">
            <router-view></router-view>
        </div>

        <paloma-notifications :initial="errors"></paloma-notifications>
    </div>
</template>

<script>

    import paloma from '../paloma';
    import Vue from 'vue';
    import VueRouter from 'vue-router';
    import PalomaCheckoutOrder from './PalomaCheckoutOrder';
    import PalomaCheckoutHeader from "./PalomaCheckoutHeader";
    import PalomaCheckoutAuth from "./PalomaCheckoutAuth";
    import PalomaCheckoutAuthEmail from "./PalomaCheckoutAuthEmail";
    import PalomaCheckoutAuthUser from "./PalomaCheckoutAuthUser";
    import PalomaCheckoutAuthLogin from "./PalomaCheckoutAuthLogin";
    import PalomaCheckoutAuthRegister from "./PalomaCheckoutAuthRegister";
    import PalomaCheckoutDelivery from "./PalomaCheckoutDelivery";
    import PalomaCheckoutDeliveryAddress from "./PalomaCheckoutDeliveryAddress";
    import PalomaCheckoutDeliveryMethod from "./PalomaCheckoutDeliveryMethod";
    import PalomaCheckoutPayment from "./PalomaCheckoutPayment";
    import PalomaCheckoutPaymentAddress from "./PalomaCheckoutPaymentAddress";
    import PalomaCheckoutPaymentMethod from "./PalomaCheckoutPaymentMethod";
    import PalomaCheckoutConfirm from "./PalomaCheckoutConfirm";
    import PalomaNotifications from "../common/PalomaNotifications";

    Vue.use(VueRouter);

    const routes = [
        {
            path: '/auth',
            name: 'state_auth',
            component: PalomaCheckoutAuth,
            meta: {
                step: 1
            },
            children: [
                {
                    path: 'email',
                    name: 'state_auth_email',
                    component: PalomaCheckoutAuthEmail,
                    meta: {
                        step: 1,
                    }
                },
                {
                    path: 'user',
                    name: 'state_auth_user',
                    component: PalomaCheckoutAuthUser,
                    meta: {
                        step: 1
                    }
                },
                {
                    path: 'login',
                    name: 'state_auth_login',
                    component: PalomaCheckoutAuthLogin,
                    meta: {
                        step: 1
                    }
                },
                {
                    path: 'register',
                    name: 'state_auth_register',
                    component: PalomaCheckoutAuthRegister,
                    meta: {
                        step: 1
                    }
                },
            ]
        },
        {
            path: '/delivery',
            name: 'state_delivery',
            component: PalomaCheckoutDelivery,
            meta: {
                step: 2
            },
            redirect: {
                name: 'state_delivery_address'
            },
            children: [
                {
                    path: 'address',
                    name: 'state_delivery_address',
                    component: PalomaCheckoutDeliveryAddress,
                    meta: {
                        step: 2
                    }
                },
                {
                    path: 'method',
                    name: 'state_delivery_method',
                    component: PalomaCheckoutDeliveryMethod,
                    meta: {
                        step: 2
                    }
                }
            ]
        },
        {
            path: '/payment',
            name: 'state_payment',
            component: PalomaCheckoutPayment,
            meta: {
                step: 3
            },
            redirect: {
                name: 'state_payment_method'
            },
            children: [
                {
                    path: 'method',
                    name: 'state_payment_method',
                    component: PalomaCheckoutPaymentMethod,
                    meta: {
                        step: 3
                    }
                },
                {
                    path: 'address',
                    name: 'state_payment_address',
                    component: PalomaCheckoutPaymentAddress,
                    meta: {
                        step: 3
                    }
                }
            ]
        },
        {
            path: '/confirm',
            name: 'state_confirm',
            component: PalomaCheckoutConfirm,
            meta: {
                step: 4
            }
        }
    ];

    const router = new VueRouter({
        base: paloma.router.resolve('checkout_start'),
        mode: 'history',
        routes: routes
    });

    export default {
        name: 'PalomaCheckout',

        router,

        components: {
            PalomaNotifications,
            PalomaCheckoutHeader,
            PalomaCheckoutOrder
        },

        data() {
            return {
                abortUrl: paloma.router.resolve('catalog_home'),
                errors: PALOMA.checkout.errors.map(message => {
                    return {
                        message: message,
                        type: 'error',
                    };
                }),
                orderContentExpanded: false // only relevant on mobile
            }
        },

        computed: {
            showOrderMobile() {
                return this.$route.name.indexOf('state_auth') === -1;
            }
        },

        methods: {
            onToggleOrderContent(expanded) {
                this.orderContentExpanded = expanded;
            }
        }
    }
</script>
