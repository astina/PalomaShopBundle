<template>
    <div class="product-list">

        <div v-if="showHead" class="product-list__head">

            <h4 class="product-list__title">
                <span v-if="!results"><i class="fas fa-spinner fa-spin"></i></span>
                <span v-if="results">
                    {{ results.totalElements }}
                    {{ $trans('catalog.products.title') }}
                </span>
            </h4>

            <div class="product-list__sort sort"
                 v-if="search.sort"
                 v-click-outside="hideSortDropdown">
                <span class="sort__label">
                    {{ $trans('catalog.products.sort_by') }}
                </span>
                <div class="dropdown is-right"
                     :class="{'is-active': sortDropdownOpen}">
                    <div class="dropdown-trigger sort__dropdown-trigger" @click="sortDropdownOpen = !sortDropdownOpen">
                        <div class="sort__button" aria-haspopup="true" aria-controls="sort-dropdown-menu">
                            <span>{{ $trans('catalog.products.sort.' + search.sort.current) }}</span>
                            <span class="icon is-small">
                                <i class="fal fa-angle-down" v-if="!sortDropdownOpen"></i>
                                <i class="fal fa-angle-up" v-if="sortDropdownOpen"></i>
                            </span>
                        </div>
                    </div>
                    <div class="dropdown-menu" id="sort-dropdown-menu" role="menu">
                        <div class="sort__options dropdown-content">
                            <a v-for="(option, name) in search.sort.options" class="dropdown-item"
                               @click.prevent="sort(name, option)"
                               href="">
                                {{ $trans('catalog.products.sort.' + name) }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div v-if="results">

            <div v-if="results.filterAggregates" class="product-list__filters">
                <paloma-product-filters
                        :filter-aggregates="results.filterAggregates"
                        :active-filters="search.request.filters"
                        @change="applyFilter"
                        @remove="removeFilterValue"
                        @clear="clearFilters"></paloma-product-filters>
            </div>

            <div class="columns is-multiline is-mobile">
                <div v-for="product in results.content"
                     class="column is-half-mobile is-one-third-tablet is-one-quarter-desktop">
                    <paloma-product-card :product="product" :category="category" :href="createHref(product)"></paloma-product-card>
                </div>
            </div>

            <div v-if="paging && results.totalPages > 1" class="product-list__pagination">
                <paloma-pagination
                        :page="results"
                        @page-prev="prevPage"
                        @page-next="nextPage"></paloma-pagination>
            </div>

        </div>

    </div>
</template>

<script>

    import paloma from "../paloma";
    import utils from "../utils";
    import PalomaProductCard from "./PalomaProductCard";
    import PalomaPagination from "./PalomaPagination";
    import PalomaProductFilters from "./PalomaProductFilters";

    export default {
        name: "PalomaProductList",

        components: {
            PalomaProductFilters,
            PalomaPagination,
            PalomaProductCard
        },

        props: {
            search: {
                type: Object,
                required: true
            },
            category: {
                type: Object,
                required: false
            },
            showHead: {
                type: Boolean,
                default: true
            },
            paging: {
                type: Boolean,
                default: true
            },
        },

        data() {

            this.search.request = this._applyQueryParams(this.search.request);

            return {
                results: null,
                sortDropdownOpen: false
            }
        },

        mounted() {
            this._searchProducts();
            this._initHistoryPopStateListener();
        },

        beforeDestroy() {
            this._destroyHistoryPopStateListener();
        },

        methods: {

            searchProducts() {
                this._pushHistoryState();
                this._searchProducts();
            },

            _searchProducts() {

                this.results = null;

                paloma.catalog
                    .searchProducts(this.search.request)
                    .then(results => {
                        this.results = results;
                    });
            },

            sort(name, options) {

                this.search.sort.current = name;
                this.search.request.sort = options.property;
                this.search.request.orderDesc = options.desc;

                this.search.request.page = 0;

                this.searchProducts();
            },

            hideSortDropdown() {
                this.sortDropdownOpen = false;
            },

            applyFilter(filter, values) {

                let filters = this.search.request.filters;

                // Remove existing filter
                const currentFilter = filters.find(f => f.name === filter.name);
                if (currentFilter) {
                    filters = utils.removeElem(filters, currentFilter);
                }

                filters.push({
                    name: filter.name,
                    values: values,
                    // TODO < >
                });

                this.search.request.filters = filters;

                this.search.request.page = 0;

                this.searchProducts();
            },

            removeFilterValue(filter, value) {

                filter.values = utils.removeElem(filter.values, value);

                this.search.request.page = 0;

                this.searchProducts();
            },

            clearFilters() {

                this.search.request.filters = [];

                this.search.request.page = 0;

                this.searchProducts();
            },

            nextPage() {

                if (this.search.request.page === this.results.totalPages - 1) {
                    return;
                }

                this.search.request.page++;
                this.searchProducts();
            },

            prevPage() {

                if (this.search.request.page === 0) {
                    return;
                }

                this.search.request.page--;
                this.searchProducts();
            },

            createHref(product) {

                const category = this.category || product.mainCategory;

                if (category) {
                    return paloma.router.resolve(
                        this.results._links.category_product.href,
                        Object.assign({
                            categorySlug: category.slug,
                            categoryCode: category.code,
                            itemNumber: product.itemNumber,
                            productSlug: product.slug
                        }, this._searchParams()));
                }

                return paloma.router.resolve(
                    this.results._links.product.href,
                    {
                        itemNumber: product.itemNumber,
                        productSlug: product.slug
                    });
            },

            _searchParams() {
                return {
                    page: this.search.request.page,
                    size: this.search.request.size,
                    sort: this.search.request.sort,
                    orderDesc: this.search.request.orderDesc,
                    filters: this.search.request.filters,
                };
            },

            _pushHistoryState() {

                const params = this._searchParams();

                const state = utils.clone(this.search.request);

                history.pushState(state, '', '?' + utils.queryString(params));
            },

            _initHistoryPopStateListener() {

                window.onpopstate = (event) => {

                    if (event.state) {
                        this.search.request = event.state;
                    } else {
                        const search = utils.clone(PALOMA['search']);
                        this.search.request = this._applyQueryParams(search.request);
                    }

                    this._refreshSort();

                    this._searchProducts();
                }
            },

            _destroyHistoryPopStateListener() {
                window.onpopstate = null;
            },

            _applyQueryParams(request) {

                const params = new URLSearchParams(window.location.search);

                if (params.has('page')) {
                    request.page = parseInt(params.get('page'));
                }
                if (params.has('size')) {
                    request.size = parseInt(params.get('size'));
                }
                if (params.has('sort')) {
                    request.sort = params.get('sort');
                }
                if (params.has('orderDesc')) {
                    request.orderDesc = params.get('orderDesc') === 'true';
                }
                if (params.has('filters')) {
                    try {
                        request.filters = JSON.parse(params.get('filters'));
                    } catch (e) {
                        console.log(e);
                        request.filters = [];
                    }
                }

                return request;
            },

            _refreshSort() {
                /**
                 * Set sort.current based on search request
                 */
                for (let name in this.search.sort.options) {
                    if (!this.search.sort.options.hasOwnProperty(name)) {
                        continue;
                    }
                    const sort = this.search.sort.options[name];
                    if (sort.property === this.search.request.sort
                        && sort.desc === this.search.request.orderDesc) {
                        sort.selected = true;
                        this.search.sort.current = name;
                    } else {
                        sort.selected = false;
                    }
                }
            }
        }
    }
</script>