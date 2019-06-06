import Vue from "vue";
import PalomaUser from "./customer/PalomaUser";
import PalomaCustomerRegistration from "./customer/PalomaCustomerRegistration";
import PalomaUserPasswordResetConfirm from "./customer/PalomaUserPasswordResetConfirm";

const userElem = document.getElementById('paloma-user');
if (userElem) {

    const user = new Vue({
        components: {
            PalomaUser
        }
    });

    user.$mount(userElem);
}

const registerElem = document.getElementById('paloma-customer-register');
if (registerElem) {

    const user = new Vue({
        components: {
            PalomaCustomerRegistration
        }
    });

    user.$mount(registerElem);
}

const passwordResetElem = document.getElementById('paloma-password-reset');
if (passwordResetElem) {

    const passwordReset = new Vue({
        components: {
            PalomaUserPasswordResetConfirm
        }
    });

    passwordReset.$mount(passwordResetElem);
}