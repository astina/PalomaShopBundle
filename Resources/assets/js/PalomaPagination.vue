<template>
    <nav class="pagination is-centered" role="navigation" aria-label="pagination">
        <a class="pagination-previous" @click.prevent="prevPage()" :disabled="page.first" href="">
            {{ $trans('paging.prev') }}
        </a>
        <a class="pagination-next" @click.prevent="nextPage()" :disabled="page.last" href="">
            {{ $trans('paging.next') }}
        </a>
        <ul class="pagination-list">
            <li>{{ page.number + 1 }} / {{ page.totalPages }}</li>
        </ul>
    </nav>
</template>

<script>
    export default {
        name: "PalomaPagination",

        props: {
            page: Object
        },

        methods: {

            prevPage() {
                this.$emit('page-prev');
            },

            nextPage() {
                this.$emit('page-next');
            }
        }
    }
</script>

<style scoped>

</style>