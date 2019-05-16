import Vue from 'vue';
import axios from 'axios';

const events = new Vue();

const routes = window.PALOMA.routes;

const router = {

    resolve(link, params) {

        params = params ||{};

        let url = routes[link] || link;

        const query = [];
        for (const param in params) {
            const placeholder = '__' + param + '__';
            const value = params[param];
            if (url.indexOf(placeholder) !== -1)  {
                url = url.replace(placeholder, value);
            } else {
                query.push(encodeURIComponent(param) + '=' + encodeURIComponent(value) + '&');
            }
        }

        return url
            + (query.length === 0 ? '' : '?' + query.reduce((p1, p2) => p1 + p2).replace(/&$/, ''));
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

    product(itemNumber) {
        return axios
            .get(routes['api_products_get'], {params: {itemNumber: itemNumber}})
            .then(response => {
                return response.data;
            })
            .catch(e => {
                events.$emit('paloma.error', e);
            });
    }
};

const cart = {

    get() {

        return axios
            .get(routes['api_cart'])
            .then(response => {

                const cart = response.data;

                events.$emit('paloma.cart_loaded', cart);

                return cart;
            })
            .catch(e => {
                events.$emit('paloma.error', e);
            });

    },

    addItem(sku, quantity) {

        return axios
            .post(routes['api_cart_item_add'], {
                sku: sku,
                quantity: quantity
            })
            .then(response => {

                const cart = response.data;

                const item = cart.items.find(i => i.sku === sku);

                events.$emit('paloma.cart_item_added', item, cart);

                return item;
            })
            .catch(e => {
                // TODO handle 400
                events.$emit('paloma.error', e)
            });
    },

    updateItem(itemId, quantity) {

        return axios
            .post(routes['api_cart_item_update'], {
                itemId: itemId,
                quantity: quantity
            })
            .then(response => {

                const cart = response.data;

                const item = cart.items.find(i => i.id === itemId);

                events.$emit('paloma.cart_updated', cart);

                return item;
            })
            .catch(e => {
                // TODO handle 400
                events.$emit('paloma.error', e)
            });
    },

    removeItem(itemId) {

        return axios
            .delete(routes['api_cart_item_remove'], { params: {
                    itemId: itemId
            }})
            .then(response => {

                const cart = response.data;

                events.$emit('paloma.cart_updated', cart);

                return cart;
            })
            .catch(e => {
                // TODO handle 400
                events.$emit('paloma.error', e)
            });
    },

    getRecommendations(size) {

        return axios
            .get(routes['api_cart_recommendations'], { params: {
                size: size
            }})
            .then(response => {
                return response.data;
            })
            .catch(e => {
                events.$emit('paloma.error', e)
            });
    }
};

export default {

    events: events,

    router: router,

    catalog: catalog,

    cart: cart
}