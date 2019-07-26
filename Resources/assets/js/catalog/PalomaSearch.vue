<template>
    <div>
        <a :href="searchUrl" class="button is-white">
            <span class="icon">
                <i class="fal fa-search"></i>
            </span>
        </a>
    </div>
</template>

<script>

    import paloma from '../paloma';

    export default {
        name: 'PalomaSearch',

        data() {
            return {
                searchUrl: paloma.router.resolve('catalog_search')
            }
        }
    }
</script>
