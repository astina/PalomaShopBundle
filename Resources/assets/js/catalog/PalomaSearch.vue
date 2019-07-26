<template>
    <div class="quick-search">
        <a @click.prevent="toggleForm()" class="button is-white quick-search__button" href="">
            <span class="icon">
                <i class="fal fa-search"></i>
            </span>
        </a>
        <div class="quick-search__form-wrapper" :class="{'quick-search__form-wrapper--active': showForm}">
            <div class="quick-search__form">
                <div class="container">

                    <form method="get" :action="searchUrl">

                        <div class="field has-addons">
                            <div class="control is-expanded">
                                <input v-if="showForm"
                                       v-focus
                                       class="input is-large" type="search" name="query"
                                       value=""
                                       :placeholder="$trans('search.placeholder')">
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
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import paloma from '../paloma';

    export default {
        name: 'PalomaSearch',

        data() {
            return {
                searchUrl: paloma.router.resolve('catalog_search'),
                showForm: false
            }
        },

        methods: {
            toggleForm() {
                this.showForm = !this.showForm;
            }
        }
    }
</script>
