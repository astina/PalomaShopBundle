<template>
    <div class="account">

        <paloma-notifications position="top-center"></paloma-notifications>

        <div class="account__nav">
            <paloma-account-nav :routes="routes"></paloma-account-nav>
        </div>

        <div class="account__main">
            <router-view></router-view>
        </div>

    </div>
</template>

<script>

    import paloma from '../paloma';
    import config from '../paloma-config';
    import Vue from 'vue';
    import VueRouter from 'vue-router';
    import PalomaAccountNav from "./PalomaAccountNav";
    import PalomaAccountOverview from "./PalomaAccountOverview";
    import PalomaAccountOrderList from "./PalomaAccountOrderList";
    import PalomaAccountOrderDetails from "./PalomaAccountOrderDetails";
    import PalomaAccountAddressList from "./PalomaAccountAddressList";
    import PalomaAccountAddressForm from "./PalomaAccountAddressForm";
    import PalomaAccountEmail from "./PalomaAccountEmail";
    import PalomaAccountPassword from "./PalomaAccountPassword";
    import PalomaNotifications from "../common/PalomaNotifications";

    Vue.use(VueRouter);

    const routes = [
        {
            path: '*',
            redirect: { name: 'overview' }
        },
        {
            path: '/overview',
            name: 'overview',
            component: PalomaAccountOverview,
            meta: {
                order: 0,
                group: 'customer',
            }
        },
        {
            path: '/orders',
            name: 'order_list',
            component: PalomaAccountOrderList,
            meta: {
                order: 10,
                group: 'customer',
            }
        },
        {
            path: '/orders/:order_number',
            name: 'order_details',
            component: PalomaAccountOrderDetails
        },
        {
            path: '/addresses',
            name: 'address_list',
            component: PalomaAccountAddressList,
            meta: {
                order: 20,
                group: 'customer',
            }
        },
        {
            path: '/addresses/:type',
            name: 'address_form',
            component: PalomaAccountAddressForm
        },
        {
            path: '/email',
            name: 'email',
            component: PalomaAccountEmail,
            meta: {
                order: 0,
                group: 'user',
            }
        },
        {
            path: '/password',
            name: 'password',
            component: PalomaAccountPassword,
            meta: {
                order: 10,
                group: 'user',
            }
        },
    ];

    config.customAccountRoutes.forEach(customRoute => routes.push(customRoute));

    const router = new VueRouter({
        base: paloma.router.resolve('customer_account'),
        mode: 'history',
        routes: routes
    });

    export default {
        name: 'PalomaAccount',

        components: {PalomaNotifications, PalomaAccountNav},

        router,

        mounted() {

            paloma.events.$on('paloma.access.unauthorized', () => {
                // TODO show notification
                window.location.reload();
            });
            paloma.events.$on('paloma.access.forbidden', () => {
                // TODO
            });
        },

        data() {
            return {
                routes: routes
            }
        }
    }

</script>