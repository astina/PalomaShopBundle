<template>

    <aside class="menu">
        <p class="menu-label">
            {{ $trans('customer.account.my_account' )}}
        </p>
        <ul class="menu-list">
            <li v-for="route in customer">
                <router-link :to="{name: route.name}" active-class="is-active">
                    {{ $trans('customer.account.' + route.name)}}
                </router-link>
            </li>
        </ul>
        <p class="menu-label">
            {{ $trans('customer.account.user' )}}
        </p>
        <ul class="menu-list">
            <li v-for="route in user">
                <router-link :to="{name: route.name}" active-class="is-active">
                    {{ $trans('customer.account.' + route.name)}}
                </router-link>
            </li>
        </ul>
    </aside>

</template>

<script>

    function routesForGroup(routes, group) {

        const groupRoutes = routes.filter(route => {
            return route.meta && route.meta.group && route.meta.group === group;
        });

        groupRoutes.sort((r1, r2) => r1.meta.order - r2.meta.order);

        return groupRoutes;
    }

    export default {
        name: 'PalomaAccountNav',

        props: {
            routes: Array
        },

        data() {

            const customer = routesForGroup(this.routes, 'customer');
            const user = routesForGroup(this.routes, 'user');

            return {
                customer: customer,
                user: user
            }
        }
    }
</script>