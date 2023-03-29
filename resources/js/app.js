
import "./bootstrap";
import "bootstrap/dist/css/bootstrap.css"
import "bootstrap-vue/dist/bootstrap-vue.css"
window.Vue = require('vue').default;
Vue.use(BootstrapVue)
import { BootstrapVue, IconsPlugin } from "bootstrap-vue"
import App from "./App.vue";
import VueRouter from "vue-router";
import router from "./router";
import {ValidationProvider,ValidationObserver} from "vee-validate";
import "./services/validation";
import  store from "./Store";
import VuePapaParse from "vue-papa-parse";
import VueProgressBar from "vue-progressbar"
const options = {
  transition: {
    speed: '0.2s',
    opacity: '0.6s',
    termination: 300
  },
  autoRevert: true,
  inverse: false
}
Vue.use(VueProgressBar, options)
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

export default app;

