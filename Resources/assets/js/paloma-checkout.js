import Vue from "vue";
import PalomaCheckout from "./PalomaCheckout";

const checkoutElem = document.getElementById('paloma-checkout');
if (checkoutElem) {

    const checkout = new Vue({
        components: {
            PalomaCheckout
        }
    });

    checkout.$mount(checkoutElem);
}