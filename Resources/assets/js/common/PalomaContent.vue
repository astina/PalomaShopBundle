<template>
    <div v-html="html"></div>
</template>

<script>
    export default {
        name: "PalomaContent",

        props: {
            id: String
        },

        data() {

            const elem = document.getElementById('content--' + this.id);

            return {
                html: elem && elem.innerHTML,
            }
        }
    }
</script>