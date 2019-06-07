<template>
    <div class="image" :class="cssClass">
        <img v-if="sourceUrl" v-bind:src="sourceUrl" v-bind:alt="imageTitle"/>
    </div>
</template>

<script>
    export default {
        name: 'PalomaImage',

        props: {
            image: {
                type: Object,
                required: true
            },
            size: {
                type: String,
                default: 'large',
                required: false
            },
            dimension: {
                type: String,
                required: false
            }
        },

        data() {
          return {
              cssClass: (this.dimension ? 'is-' + this.dimension : null)
          }
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