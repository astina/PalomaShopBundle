<template>
    <div class="checkout">

        <div class="checkout__header">
            <paloma-checkout-header></paloma-checkout-header>
        </div>

        <div class="checkout__order">
            <a class="checkout__abort" :href="abortUrl">
                {{ $trans('checkout.abort') }}
            </a>
            <paloma-checkout-order></paloma-checkout-order>
        </div>

        <div class="checkout__main">
            <router-view></router-view>
        </div>

        <paloma-notifications></paloma-notifications>
    </div>
</template>

<script>

    import VueRouter from 'vue-router';
    import paloma from '../paloma';
    import PalomaCheckoutOrder from './PalomaCheckoutOrder';
    import PalomaCheckoutHeader from "./PalomaCheckoutHeader";
    import PalomaCheckoutAuth from "./PalomaCheckoutAuth";
    import PalomaCheckoutAuthEmail from "./PalomaCheckoutAuthEmail";
    import PalomaCheckoutAuthLogin from "./PalomaCheckoutAuthLogin";
    import PalomaCheckoutAuthRegister from "./PalomaCheckoutAuthRegister";
    import PalomaCheckoutDelivery from "./PalomaCheckoutDelivery";
    import PalomaCheckoutPayment from "./PalomaCheckoutPayment";
    import PalomaCheckoutConfirm from "./PalomaCheckoutConfirm";
    import PalomaNotifications from "../common/PalomaNotifications";

    const routes = [
        {
            path: '/auth',
            name: 'state_auth',
            component: PalomaCheckoutAuth,
            meta: {
                step: 1
            },
            redirect: {
                name: 'state_auth_email'
            },
            children: [
                {
                    path: 'email',
                    name: 'state_auth_email',
                    component: PalomaCheckoutAuthEmail
                },
                {
                    path: 'login',
                    name: 'state_auth_login',
                    component: PalomaCheckoutAuthLogin
                },
                {
                    path: 'login',
                    name: 'state_auth_register',
                    component: PalomaCheckoutAuthRegister
                },
            ]
        },
        {
            path: '/delivery',
            name: 'state_delivery',
            component: PalomaCheckoutDelivery,
            meta: {
                step: 2
            }
        },
        {
            path: '/payment',
            name: 'state_payment',
            component: PalomaCheckoutPayment,
            meta: {
                step: 3
            }
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
            }
        },

        mounted() {

            const order = PALOMA.checkout.order;
        }
    }
</script>
