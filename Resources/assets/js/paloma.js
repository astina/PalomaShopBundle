import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';
import utils from './utils';

const events = new Vue();

Vue.use(Vuex);

axios.interceptors.request.use(config => {

    const csrfToken = document.querySelector('meta[data-csrf-token]');
    if (csrfToken) {
        config.headers['x-csrf-token'] = csrfToken.getAttribute('data-csrf-token');
    }

    return config;
});

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

function onHttpError(error) {

    if (error.response && error.response.data) {

        if (error.response.status >= 500) {
            events.$emit('paloma.error', error);
        }

        if (error.response.status === 401) {
            events.$emit('paloma.access.unauthorized', error);
        }

        if (error.response.status === 403) {
            events.$emit('paloma.access.forbidden', error);
        }

        return Promise.reject(error.response.data);
    }

    events.$emit('paloma.error', error);

    throw error;
}

const routes = window.PALOMA.routes;

const router = {

    resolve(link, params, anchor) {

        params = params || {};

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
            + (query.length === 0 ? '' : '?' + query.join('&'))
            + (anchor ? '#' + anchor : '');
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

    setGuestCustomer(customer) {

        return axios
            .post(routes['api_checkout_customer_set_guest'], customer)
            .then(response => {
                this.store.commit('updateOrder', response.data);
                return response.data
            })
            .catch(onHttpError);
    },

    setShippingAddress(address) {

        return axios
            .post(routes['api_checkout_shipping_address_update'], address)
            .then(response => {
                this.store.commit('updateOrder', response.data);
                return response.data
            })
            .catch(onHttpError);
    },

    setBillingAddress(address) {

        return axios
            .post(routes['api_checkout_billing_address_update'], address)
            .then(response => {
                this.store.commit('updateOrder', response.data);
                return response.data
            })
            .catch(onHttpError);
    },

    fetchShippingMethods() {

        return axios
            .get(routes['api_checkout_shipping_methods_list'])
            .then(response => {
                return response.data
            })
            .catch(onHttpError);
    },

    setShippingMethod(method, targetDate) {

        return axios
            .post(routes['api_checkout_shipping_methods_set'], {
                method: method,
                targetDate: targetDate
            })
            .then(response => {
                this.store.commit('updateOrder', response.data);
                return response.data
            })
            .catch(onHttpError);
    },

    fetchPaymentMethods() {

        return axios
            .get(routes['api_checkout_payment_methods_list'])
            .then(response => {
                return response.data
            })
            .catch(onHttpError);
    },

    setPaymentMethod(method) {

        return axios
            .post(routes['api_checkout_payment_methods_set'], {
                method: method,
            })
            .then(response => {
                this.store.commit('updateOrder', response.data);
                return response.data
            })
            .catch(onHttpError);
    },

    addCouponCode(code) {

        return axios
            .post(routes['api_checkout_coupon_code_add'], {
                code: code
            })
            .then(response => {
                this.store.commit('updateOrder', response.data);
                return response.data
            })
            .catch(onHttpError);
    },

    removeCouponCode(code) {

        return axios
            .post(routes['api_checkout_coupon_code_remove'], {
                code: code
            })
            .then(response => {
                this.store.commit('updateOrder', response.data);
                return response.data
            })
            .catch(onHttpError);
    },

    finalize() {

        return axios
            .post(routes['api_checkout_finalize'])
            .then(response => {
                this.store.commit('updateOrder', response.data);
                return response.data;
            })
            .catch(onHttpError);
    },

    purchase() {

        return axios
            .post(routes['api_checkout_purchase'])
            .then(response => {
                return response.data;
            })
            .catch(onHttpError);
    },

    orderDraft() {
        return this.store.state.order;
    },

    refreshOrderDraft() {

        return axios
            .get(routes['api_checkout_order'])
            .then(response => {

                this.store.commit('updateOrder', response.data);

                return response.data;
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
            }
        }
    })
};

const customer = {

    register(draft) {

        return axios
            .post(routes['api_customer_register'], draft)
            .then(response => {
                return response.data;
            })
            .catch(onHttpError);
    },

    get() {

        return axios
            .get(routes['api_customer_get'])
            .then(response => {
                return response.data;
            })
            .catch(onHttpError);
    },

    updateEmailAddress(emailAddress) {

        return axios
            .post(routes['api_customer_update_email'], {emailAddress: emailAddress})
            .then(response => {
                return response.data;
            })
            .catch(onHttpError);
    },

    updateAddress(type, address) {

        const update = utils.clone(address);
        update.addressType = type;

        return axios
            .post(routes['api_customer_address_update'], update)
            .then(response => {
                return response.data;
            })
            .catch(onHttpError);
    },

    listOrders(page, size, orderDesc) {

        return axios
            .get(routes['api_orders_list'], { params: {
                page: page || 0,
                size: size || 5,
                orderDesc: orderDesc || true
            }})
            .then(response => {
                return response.data;
            })
            .catch(onHttpError);
    },

    getOrder(orderNumber) {

        return axios
            .get(routes['api_orders_get'], { params: {
                orderNumber: orderNumber
            }})
            .then(response => {
                return response.data;
            })
            .catch(onHttpError);
    },

    getLastOrder() {

        return axios
            .get(routes['api_orders_latest'])
            .then(response => {
                return response.data;
            })
            .catch(onHttpError);
    }
};

const security = {
    user: PALOMA && PALOMA.user
};

const user = {

    get() {
      return security.user;
    },

    authenticate(username, password) {

        return axios
            .post(routes['api_user_authenticate'], {
                username: username,
                password: password
            })
            .then(response => {

                const success = response.status >= 200 && response.status < 300;

                if (success) {
                    security.user = response.data;
                } else {
                    security.user = null;
                }

                return success;
            })
            .catch(onHttpError);
    },

    logout() {

        return axios
            .post(routes['security_logout'])
            .then(() => {
                security.user = null;
            })
            .catch(onHttpError);
    },

    updatePassword(currentPassword, newPassword) {

        return axios
            .post(routes['api_user_password_update'], {
                currentPassword: currentPassword,
                newPassword: newPassword
            })
            .catch(onHttpError);
    },

    startPasswordReset(emailAddress) {

        return axios
            .post(routes['api_user_password_reset_start'], {
                emailAddress: emailAddress
            })
            .catch(onHttpError);
    },

    completePasswordReset(token, password) {

        return axios
            .post(routes['api_user_password_reset_complete'], {
                token: token,
                newPassword: password
            })
            .then(response => {
                return response.data;
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