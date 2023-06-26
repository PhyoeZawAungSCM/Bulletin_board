import Vue from "vue";
import Vuex from "vuex";
import user from "./User/index";
import post from "./Post/index";
import auth from "./Auth/index";
import noti from "./Noti/index";
Vue.use(Vuex);
const store = new Vuex.Store({
    modules: {
        user,
        post,
        auth,
        noti,
    },
    state: {
        baseURL: "http://127.0.0.1:8000",
        user: {},
    },
});

export default store;
