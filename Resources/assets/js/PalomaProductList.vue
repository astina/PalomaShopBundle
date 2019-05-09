<template>
    <div class="product-list">

        <div class="product-list__head">

            <h4 class="product-list__title">
                <span v-if="!results"><i class="fas fa-spinner fa-spin"></i></span>
                <span v-if="results">
                    {{ results.totalElements }}
                    {{ $trans('catalog.products.title') }}
                </span>
            </h4>

            <div v-if="results" class="product-list__sort sort">
                <span class="sort__label">
                    {{ $trans('catalog.products.sort_by') }}
                </span>
                <!-- TODO -->
            </div>
        </div>

        <div v-if="results">

            <div class="columns is-multiline is-mobile">
                <div v-for="product in results.content"
                     class="column is-half-mobile is-one-third-tablet is-one-quarter-desktop">
                    <paloma-product-card :product="product" :category="category" :href="createHref(product)"/>
                </div>
            </div>

            <div v-if="results.totalPages > 1" class="product-list__pagination">
                <paloma-pagination
                        :page="results"
                        @page-prev="prevPage"
                        @page-next="nextPage"></paloma-pagination>
            </div>

        </div>

    </div>
</template>

<script>

    import paloma from "./paloma";
    import PalomaProductCard from "./PalomaProductCard";
    import PalomaPagination from "./PalomaPagination";

    export default {
        name: "PalomaProductList",

        components: {
            PalomaPagination,
            PalomaProductCard
        },

        data() {

            const search = PALOMA['search'];
            const category = PALOMA['category'] || null;

            return {
                search: search,
                category: category,
                results: null,
            }
        },

        mounted() {
            this.searchProducts();
        },

        methods: {

            searchProducts() {
                paloma.catalog
                    .searchProducts(this.search.request)
                    .then(results => {
                        this.results = results;
                    });
            },

            nextPage() {

                if (this.search.request.page === this.results.totalPages - 1) {
                    return;
                }

                this.results = null;
                this.search.request.page++;
                this.searchProducts();
            },

            prevPage() {

                if (this.search.request.page === 0) {
                    return;
                }

                this.results = null;
                this.search.request.page--;
                this.searchProducts();
            },

            createHref(product) {
                return paloma.router.resolve(
                    this.results._links.category_product.href,
                    {
                        categorySlug: this.category.slug,
                        categoryCode: this.category.code,
                        itemNumber: product.itemNumber,
                        productSlug: product.slug
                    });
            }
        }
    }
</script>

<style scoped>

</style>