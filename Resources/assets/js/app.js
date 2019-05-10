require('../css/paloma.scss');

import Vue from 'vue';

// TODO create plugin
Vue.prototype.$trans = function(key) {
    return Translator.trans(key);
};

require('./paloma-category-nav');

require('./paloma-catalog');
