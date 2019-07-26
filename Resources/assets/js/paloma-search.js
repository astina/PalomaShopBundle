import Vue from "vue";
import PalomaSearch from "./catalog/PalomaSearch";

const searchElem = document.getElementById('paloma-search');
if (searchElem) {

    const user = new Vue({
        components: {
            PalomaSearch
        }
    });

    user.$mount(searchElem);
}