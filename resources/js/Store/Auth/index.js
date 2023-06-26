import { http, httpFile } from "../../services/http_service";
import router from "../../router";
import { isLogin, token } from "../../services/Auth_service";
import cryptoJs from "crypto-js";
export default {
    state: {
        loginUser: {},
        isLogin: false,
        token: "",
        error: "",
        hasError: false,
    },
    mutations: {
        EDIT_PROFILE(state, profile) {
            state.loginUser = profile;
        },
    },
    actions: {
        /**
         *  check if the user is already login or not and 
            set the token and profile of the user in the state
         * 
         */
        isAlreadyLogin({ state, dispatch }) {
            if (isLogin()) {
                state.token = token();
                dispatch("getUserProfile");
                state.isLogin = true;
            }
        },
        //
        /**
         * Login the user and get the profile
         * @param User user boject of the user
         *
         */
        Login({ state, commit, dispatch, rootState }, user) {
                    http()
                        .post("/api/login", user)
                        .then((response) => {
                            console.log(response.data);
                            state.token = response.data.token;
                            dispatch("getUserProfile");
                            state.isLogin = true;
                            if (user.rememberMe) {
                                let token = cryptoJs.AES.encrypt(response.data.token,'bulletinboard').toString();
                                localStorage.setItem(
                                    "token",
                                     token
                                );
                            }
                            rootState.noti.hasMessage = true;
                            rootState.noti.message = "Login Success";
                            router.push("/posts-list");
                        })
                        .catch((error) => {
                            rootState.noti.hasError = true;
                            rootState.noti.message =
                                error.response.data.message;
                        });

        },

        //
        /**
         * getting the user profile and set the LoginUser to user
         *
         */
        getUserProfile({ state }) {
            http()
                .get("/api/profile")
                .then((response) => {
                    state.loginUser = response.data;
                })
                .catch((error) => console.log(error));
        },

        Logout({ state, rootState, dispatch }) {
            http()
                .post("/api/logout")
                .then((response) => {
                    state.loginUser = {};
                    (state.token = ""),
                        (state.isLogin = false),
                        localStorage.clear();
                    rootState.post.posts = [];
                    rootState.user.users = [];
                    dispatch("getPosts", { search: "", page: 1 });
                    router.push("/posts-list");
                });
        },
        updateProfile({ commit, state, rootState }, user) {
            console.log(user.name);
            let formData = new FormData();
            formData.append("name", user.name);
            formData.append("email", user.email);
            formData.append("dob", user.dob);
            formData.append("profile", user.profile);
            formData.append("type", user.type);
            formData.append("phone", user.phone);
            formData.append("address", user.address);
            formData.append("_method", "put");
            httpFile()
                .post(`api/update/${state.loginUser.id}`, formData)
                .then((response) => {
                    console.log(response);
                    commit("EDIT_PROFILE", response.data.data);
                    rootState.noti.hasMessage = true;
                    rootState.noti.message =
                        "User profile successfully updated";
                    router.push("/posts-list");
                })
                .catch((error) => {
                    console.log(error);
                });
        },
    },
    getters: {
        getProfile(state) {
            return state.loginUser;
        },
    },
};
