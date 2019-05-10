require('../css/paloma.scss');

import Vue from 'vue';

// TODO create plugin
Vue.prototype.$trans = function(key, params) {
    return Translator.trans(key, params);
};

require('./paloma-category-nav');

require('./paloma-catalog');

require('./paloma-cart');
