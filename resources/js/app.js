require('./bootstrap');

window.Vue = require('vue');

import Vuetify from 'vuetify';
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

Vue.use(Vuetify);
Vue.use(VueSweetalert2);

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('field-one', require('./components/fieldOne.vue').default);

const app = new Vue({
    el: '#app',
    vuetify: new Vuetify(),
});
