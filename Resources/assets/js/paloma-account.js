import Vue from "vue";
import PalomaAccount from "./customer/PalomaAccount";

const accountElem = document.getElementById('paloma-account');
if (accountElem) {

    const user = new Vue({
        components: {
            PalomaAccount
        }
    });

    user.$mount(accountElem);
}