
require('./bootstrap');

window.Vue = require('vue');

window.EventBus = new Vue();

import Vuetify from 'vuetify';
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

Vue.use(Vuetify);
Vue.use(VueSweetalert2);

Vue.component('field-one', require('./components/fieldOne.vue').default);
Vue.component('field-two', require('./components/fieldTwo.vue').default);
Vue.component('field-three', require('./components/fieldThree.vue').default);
Vue.component('show-modal-create', require('./components/showModalCreate.vue').default);

const app = new Vue({
    el: '#app',
    vuetify: new Vuetify(),
});
