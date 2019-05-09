import Vue from 'vue';
import axios from 'axios';

const events = new Vue();

const routes = window.PALOMA.routes;

const router = {

    resolve(link, params) {

        let url = routes[link] || link;

        for (const param in params) {
            url = url.replace('__' + param + '__', params[param]);
        }

        return url;
    }
};

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

    router: router,

    catalog: catalog
}