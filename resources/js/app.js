
import './bootstrap';
import "bootstrap/dist/css/bootstrap.css"
import 'bootstrap-vue/dist/bootstrap-vue.css'
window.Vue = require('vue').default;
Vue.use(BootstrapVue)
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import App from './App.vue';
import VueRouter from 'vue-router';
import router from './router';
import {ValidationProvider,ValidationObserver} from 'vee-validate';
import './services/validation';
import  store from './Store';
import VuePapaParse from 'vue-papa-parse';
import Vue from 'vue';

Vue.use(VuePapaParse)
Vue.use(IconsPlugin)
Vue.use(VueRouter)
Vue.component('ValidationProvider', ValidationProvider);
Vue.component('ValidationObserver', ValidationObserver);
const app = new Vue({
    el: '#app',
    router,
    store,
    render:h => h(App),
});
