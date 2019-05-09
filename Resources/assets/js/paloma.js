import Vue from 'vue';
import axios from 'axios';

const routes = window.PALOMA.routes;

const events = new Vue();

const catalog = {

    searchProducts(searchRequest) {

        return axios
            .post(routes['api_search'], searchRequest)
            .then(response => {
                return response.data;
            })
            .catch(e => {
                events.$emit('paloma.error', e);
            });
    },
};

export default {

    events: events,

    catalog: catalog
}