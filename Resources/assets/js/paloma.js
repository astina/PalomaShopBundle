import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';

const events = new Vue();

Vue.use(Vuex);

axios.interceptors.request.use(config => {

    const csrfToken = document.querySelector('meta[data-csrf-token]');
    if (csrfToken) {
        config.headers['x-csrf-token'] = csrfToken.getAttribute('data-csrf-token');
    }

    return config;
});

function onHttpError(error) {

    if (error.response && error.response.data) {

        if (error.response.status >= 500) {
            events.$emit('paloma.error', error);
        }

        return Promise.reject(error.response.data);
    }

    events.$emit('paloma.error', error);

    throw error;
}

const routes = window.PALOMA.routes;

const router = {

    resolve(link, params) {

        params = params ||{};

        let url = routes[link] || link;

        const query = [];
        for (const param in params) {
            if (params.hasOwnProperty(param)) {
                const placeholder = '__' + param + '__';
                let value = params[param];
                if (url.indexOf(placeholder) !== -1) {
                    url = url.replace(placeholder, value);
                } else {
                    if (typeof value === 'object') {
                        value = JSON.stringify(value);
                    }
                    query.push(encodeURIComponent(param) + '=' + encodeURIComponent(value));
                }
            }
        }

        return url
            + (query.length === 0 ? '' : '?' + query.join('&'));
    }
};

const catalog = {

    searchProducts(searchRequest) {
        return axios
            .post(routes['api_search'], searchRequest)
            .then(response => {
                return response.data;
            })
            .catch(onHttpError);
    },

    product(itemNumber) {
        return axios
            .get(routes['api_products_get'], {params: {itemNumber: itemNumber}})
            .then(response => {
                return response.data;
            })
            .catch(onHttpError);
    },

    purchasedTogether(itemNumber, max) {
        return axios
            .get(routes['api_products_purchased_together'], {params: {itemNumber: itemNumber, max: max}})
            .then(response => {
                return response.data;
            })
            .catch(onHttpError);
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
            .catch(onHttpError);

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
            .catch(onHttpError);
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
            .catch(onHttpError);
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
            .catch(onHttpError);
    },

    getRecommendations(size) {

        return axios
            .get(routes['api_cart_recommendations'], { params: {
                size: size
            }})
            .then(response => {
                return response.data;
            })
            .catch(onHttpError);
    }
};

const checkout = {

    existsCustomerByEmailAddress(emailAddress) {

        return axios
            .get(routes['api_customer_exists_email'], { params: {
                    emailAddress: emailAddress
            }})
            .then(response => {
                return response.data
            })
            .catch(onHttpError);
    },

    setEmailAddress(emailAddress) {
        this.store.commit('setEmailAddress', emailAddress);
    },

    setShippingAddress(address) {

        return axios
            .post(routes['api_checkout_shipping_address_update'], address)
            .then(response => {
                this.store.commit('updateOrder', response.data);
            })
            .catch(onHttpError);
    },

    setBillingAddress(address) {

        return axios
            .post(routes['api_checkout_billing_address_update'], address)
            .then(response => {
                this.store.commit('updateOrder', response.data);
            })
            .catch(onHttpError);
    },

    purchase() {

        return axios
            .post(routes['api_checkout_purchase'])
            .then(response => {
                response.data;
            })
            .catch(onHttpError);
    },

    orderDraft() {
        return this.store.state.order;
    },

    refreshOrderDraft() {

        axios
            .get(routes['api_checkout_order'])
            .then(response => {
                this.store.commit('updateOrder', response.data);
            })
            .catch(onHttpError);
    },

    emailAddress() {
        return this.store.state.emailAddress;
    },

    // Vuex

    store: new Vuex.Store({

        state: {
            order: (PALOMA && PALOMA.checkout && PALOMA.checkout.order) || {},
        },

        mutations: {

            setEmailAddress(state, emailAddress) {
                state.emailAddress = emailAddress;
            },

            updateOrder(state, order) {
                state.order = order;
            },

            setCustomer(state, customer) {
            }

        }
    })
};

const customer = {

    register(draft) {

        return axios
            .post(routes['api_customer_register'], draft)
            .then(response => {
                response.data;
            })
            .catch(onHttpError);
    }
};

const user = {

    authenticate(username, password) {

        return axios
            .post(routes['api_user_authenticate'], {
                username: username,
                password: password
            })
            .then(response => {
                return response.status >= 200 && response.status < 300;
            })
            .catch(onHttpError);
    }

};

export default {

    events: events,

    router: router,

    catalog: catalog,

    cart: cart,

    checkout: checkout,

    customer: customer,

    user: user,
}