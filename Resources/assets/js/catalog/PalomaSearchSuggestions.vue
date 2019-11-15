<template>
    <div class="search-suggestions">

        <div class="columns">

            <div v-if="suggestions.products.length" class="column">

                <h4 class="search-suggestions__title">
                    {{ $trans('search.suggested_products') }}
                </h4>

                <ul>
                    <li v-for="product in suggestions.products" class="search-suggestions__item">
                        <a :href="createProductHref(product)">
                            {{ product.name }}
                        </a>
                    </li>
                </ul>

            </div>

            <div v-if="suggestions.categories.length" class="column">

                <h4 class="search-suggestions__title">
                    {{ $trans('search.suggested_categories') }}
                </h4>

                <ul>
                    <li v-for="category in suggestions.categories" class="search-suggestions__item">
                        <a :href="createCategoryHref(category)">
                            {{ category.name }}
                        </a>
                    </li>
                </ul>

            </div>

        </div>

    </div>
</template>

<script>

    import paloma from '../paloma';

    export default {
        name: 'PalomaSearchSuggestions',

        props: {
            suggestions: Object,
        },

        methods: {

            createProductHref(product) {

                const category = product.mainCategory || product.category;

                if (category) {
                    return paloma.router.resolve(
                        this.suggestions._links.category_product.href,
                        {
                            categorySlug: category.slug,
                            categoryCode: category.code,
                            itemNumber: product.itemNumber,
                            productSlug: product.slug
                        });
                }

                return paloma.router.resolve(
                    this.suggestions._links.product.href,
                    {
                        itemNumber: product.itemNumber,
                        productSlug: product.slug
                    });
            },

            createCategoryHref(category) {
                return paloma.router.resolve(
                    this.suggestions._links.category.href,
                    {
                        categorySlug: category.slug,
                        categoryCode: category.code
                    });
            }
        }
    }
</script>

<style scoped>

</style>