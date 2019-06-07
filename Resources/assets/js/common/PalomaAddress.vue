<template>
    <address class="address">
        <span v-show="address.company" class="address__line address__line--company">
            {{ address.company }}
        </span>
        <span class="address__line address__line--name">
            {{ address.title }}
            {{ address.firstName }}
            {{ address.lastName }}
        </span>
        <span class="address__line address__line--street">
            {{ address.street }}
        </span>
        <span class="address__line address__line--zip-city-country">
            {{ address.country ? address.country + '-' : '' }}{{ address.zipCode }}
            {{ address.city }}
        </span>
        <span class="address__line address__line--phone">
            {{ address.phoneNumber }}
        </span>
    </address>
</template>

<script>
    export default {
        name: "PalomaAddress",

        props: {
            address: Object
        }
    }
</script>
