<template>
    <div class="filter" v-click-outside="closeDropdown">
        <div class="control">
            <div class="dropdown" :class="{'is-active': dropdownOpen}">
                <div class="dropdown-trigger" @click="dropdownOpen = !dropdownOpen">
                    <div class="button is-small filter__title">
                        <span>{{ filter.label }}</span>
                        <span class="icon is-small">
                            <i class="fal fa-angle-down" v-if="!dropdownOpen"></i>
                            <i class="fal fa-angle-up" v-if="dropdownOpen"></i>
                        </span>
                    </div>
                </div>
                <div class="dropdown-menu filter__menu">
                    <div class="dropdown-content">
                        <div class="filter__values">
                            <paloma-product-filter-value v-for="value in filter.values"
                                                         :key="value.value"
                                                         :value="value"
                                                         :active="isActive(value)"
                                                         @value-toggle="onValueToggle"></paloma-product-filter-value>
                        </div>
                        <div class="filter__control">
                            <a @click.prevent="apply" class="button is-small is-dark" href="">
                                {{ $trans('catalog.products.filter.apply') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import utils from "../utils";
    import PalomaProductFilterValue from "./PalomaProductFilterValue";

    export default {
        name: "PalomaProductFilter",

        components: {PalomaProductFilterValue},

        props: {
            filter: Object,
            active: Array,
        },

        data() {
            return {
                activeValues: utils.clone(this.active),
                dropdownOpen: false
            }
        },

        methods: {

            apply() {
                this.dropdownOpen = false;
                this.$emit('change', this.filter, this.activeValues);
            },

            isActive(value) {
                return this.activeValues.indexOf(value.value) !== -1;
            },

            onValueToggle(value) {

                if (this.isActive(value)) {
                    this.activeValues = utils.removeElem(this.activeValues, value.value);
                    return;
                }

                this.activeValues.push(value.value);
            },

            closeDropdown() {
                this.dropdownOpen = false;
            }
        }
    }
</script>