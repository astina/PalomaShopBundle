<template>
    <div class="image">
        <img v-if="sourceUrl" v-bind:src="sourceUrl" v-bind:alt="imageTitle"/>
    </div>
</template>

<script>
    export default {
        name: 'PalomaImage',

        props: {
            image: Object,
            size: String
        },

        computed: {
            sourceUrl() {

                if (!this.image) {
                    return;
                }

                const source = this.image.sources[this.size];

                return source && source.url;
            },
            imageTitle() {

                if (!this.image) {
                    return;
                }
                return (this.image && this.image.title) || this.image.name;
            }
        }
    }
</script>

<style scoped>

</style>