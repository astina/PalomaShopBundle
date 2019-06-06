import Vue from "vue";
import PalomaUser from "./customer/PalomaUser";
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

const passwordResetElem = document.getElementById('paloma-password-reset');
if (passwordResetElem) {

    const passwordReset = new Vue({
        components: {
            PalomaUserPasswordResetConfirm
        }
    });

    passwordReset.$mount(passwordResetElem);
}