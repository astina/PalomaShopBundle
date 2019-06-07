<template>

    <aside class="menu">
        <p class="menu-label">
            {{ $trans('customer.account.my_account' )}}
        </p>
        <ul class="menu-list">
            <li>
                <router-link :to="{name: 'overview'}" active-class="is-active">
                    {{ $trans('customer.account.overview' )}}
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'order_list'}" active-class="is-active">
                    {{ $trans('customer.account.orders' )}}
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'address_list'}" active-class="is-active">
                    {{ $trans('customer.account.addresses' )}}
                </router-link>
            </li>
        </ul>
        <p class="menu-label">
            {{ $trans('customer.account.user' )}}
        </p>
        <ul class="menu-list">
            <li>
                <router-link :to="{name: 'email'}" active-class="is-active">
                    {{ $trans('customer.account.email_address' )}}
                </router-link>
            </li>
            <li>
                <router-link :to="{name: 'password'}" active-class="is-active">
                    {{ $trans('customer.account.password' )}}
                </router-link>
            </li>
        </ul>
    </aside>

</template>

<script>
    export default {
        name: "PalomaAccountNav"
    }
</script>