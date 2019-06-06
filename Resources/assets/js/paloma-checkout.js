import Vue from "vue";
import VueRouter from 'vue-router'
import PalomaCheckout from "./checkout/PalomaCheckout";

const checkoutElem = document.getElementById('paloma-checkout');
if (checkoutElem) {

    Vue.use(VueRouter);

    const checkout = new Vue({
        components: {
            PalomaCheckout
        }
    });

    checkout.$mount(checkoutElem);
}