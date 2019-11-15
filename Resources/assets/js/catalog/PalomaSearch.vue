<template>
    <div class="quick-search">
        <a @click.prevent="toggleForm()" class="button is-white quick-search__button" href="">
            <span class="icon">
                <i class="fal fa-search"></i>
            </span>
        </a>
        <div class="quick-search__form-wrapper" :class="{'quick-search__form-wrapper--active': showForm}">
            <div class="quick-search__form" v-if="showForm" v-click-outside="closeForm" v-on:keyup.esc="closeForm">
                <div class="container">

                    <form method="get" :action="searchUrl">

                        <div class="field has-addons">
                            <div class="control is-expanded">
                                <input v-if="showForm"
                                       v-focus
                                       v-model="searchInput"
                                       class="input is-large" type="search" name="query"
                                       required="required"
                                       :placeholder="$trans('search.placeholder')"
                                       @keyup="keyUp">
                            </div>
                            <div class="control">
                                <button type="submit" class="button is-large">
                                    <span class="icon">
                                        <i class="far fa-search"></i>
                                    </span>
                                </button>
                            </div>
                        </div>

                    </form>

                    <paloma-search-suggestions :suggestions="suggestions" v-if="suggestions"></paloma-search-suggestions>

                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import paloma from '../paloma';
    import PalomaSearchSuggestions from './PalomaSearchSuggestions';

    const debounce = {
        duration: 250,
        timeout: null,
    };

    export default {
        name: 'PalomaSearch',

        components: {
            PalomaSearchSuggestions,
        },

        data() {
            return {
                searchInput: null,
                searchUrl: paloma.router.resolve('catalog_search'),
                showForm: false,
                opened: null,
                suggestions: null,
            }
        },

        methods: {

            toggleForm() {
                this.showForm = !this.showForm;
                if (this.showForm) {
                    this.opened = new Date();
                }
            },

            closeForm() {
                if (this.opened && (new Date().getTime() - this.opened.getTime()) > 200) {
                    this.showForm = false;
                }
            },

            keyUp() {

                debounce.timeout && window.clearTimeout(debounce.timeout);
                debounce.timeout = window.setTimeout(() => {

                    if (this.searchInput && this.searchInput.length < 2) {
                        return;
                    }

                    paloma.catalog.searchSuggestions(this.searchInput).then(data => {
                        this.suggestions = data;
                    });

                }, debounce.duration);

            }
        }
    }
</script>
