require('./bootstrap');

window.Vue = require('vue');

import Vuetify from 'vuetify';

Vue.use(Vuetify);

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('field-one', require('./components/fieldOne.vue').default);

const app = new Vue({
    el: '#app',
    vuetify: new Vuetify(),
});
