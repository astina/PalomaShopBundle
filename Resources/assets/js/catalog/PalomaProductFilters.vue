<template>

    <div class="product-filters">

        <div class="field is-grouped">

            <paloma-product-filter v-for="filter in filters"
                                   :key="filter.name"
                                   :filter="filter"
                                   :active="activeValues(filter)"
                                   @change="apply"></paloma-product-filter>

        </div>

        <div v-if="activeFilters.length > 0" class="product-filters__active">

            <div class="product-filters__active-title">
                {{ $trans('catalog.products.filter.active') }}
            </div>

            <div class="field is-grouped is-grouped-multiline">

                <div v-for="value in activeFilterValues" class="tags has-addons product-filters__active-filter-value"
                    @click="remove(value)">
                    <span class="tag">{{ value.value }}</span>
                    <a class="tag is-delete"></a>
                </div>

                <a class="product-filters__active-filter-clear" href=""
                    @click.prevent="clear">
                    {{ $trans('catalog.products.filter.clear') }}
                </a>

            </div>

        </div>

    </div>

</template>

<script>

    import PalomaProductFilter from "./PalomaProductFilter";

    export default {
        name: "PalomaProductFilters",
        components: {PalomaProductFilter},
        props: {
            filterAggregates: Array,
            activeFilters: Array,
        },

        data() {

            const filters = [];
            const activeFilterValues = [];

            (this.filterAggregates || []).forEach(aggregate => {
                if (aggregate.type === 'text') {

                    filters.push(aggregate);

                    const filter = this._findFilter(aggregate.name);
                    if (filter) {
                        filter.values.forEach(value => {
                            activeFilterValues.push({
                                filter: filter,
                                value: value
                            });
                        });
                    }
                }
                // TODO range filter (currency)
            });

            return {
                filters: filters,
                activeFilterValues: activeFilterValues
            };
        },

        methods: {

            apply(filter, values) {
                this.$emit('change', filter, values);
            },

            clear() {
                this.$emit('clear');
            },

            remove(filterValue) {
                this.$emit('remove', filterValue.filter, filterValue.value);
            },

            activeValues(filter) {

                const activeFilter = this._findFilter(filter.name);

                if (!activeFilter) {
                    return [];
                }

                return activeFilter.values;
            },

            _findFilter(name) {
                return this.activeFilters.find(f => f.name === name);
            }
        }
    }
</script>
