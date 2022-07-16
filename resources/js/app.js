/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Vue = require('vue').default;

import JwPagination from 'jw-vue-pagination';
import Lodash from 'lodash';
import moment from 'moment';
import VueTableDynamic from 'vue-table-dynamic';
import VueConfirmDialog from 'vue-confirm-dialog'
import jQuery from 'jQuery'
window.jQuery = jQuery

import Vue from "vue";
import vueToastr from "vue-toastr";
Vue.use(Lodash)
Vue.use(moment)
Vue.use(VueTableDynamic)
Vue.use(VueConfirmDialog)
Vue.component('vue-confirm-dialog', VueConfirmDialog.default)
Vue.use(vueToastr);

window.moment = require('moment');
window.moment.locale('ru');
/**

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('jw-pagination', JwPagination);
Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('all-client-demandes', require('./components/clients/allClientDemandes').default);
Vue.component('messages', require('./components/clients/Messages').default);

Vue.component('add-to-cart', require('./components/products/addToCart').default);
Vue.component('cart', require('./components/products/Cart').default);
Vue.component('checkout', require('./components/products/Checkout').default);

//Adminstration
Vue.component('demandes', require('./components/dashboard/Demandes.vue').default);
Vue.component('all-demandes', require('./components/dashboard/AllDemandes.vue').default);
Vue.component('mechanic-demandes', require('./components/dashboard/MechanicDemandes.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

 const app = new Vue({
    el: '#app',
});

const app1 = new Vue({
    el: '#dashboard',
});

