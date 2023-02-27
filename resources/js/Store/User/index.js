import { http, httpFile } from "../../services/http_service";
import router from "../../router";
export default {
    state: {
        createdTempUser: {},
        users: [],
        page: 1,
        lastPage: null,
    },
    getters: {
        getAllUsers(state) {
            return state.users;
        },
    },
    mutations: {
        SET_CREATED_TEMP_USER(state, tempuser) {
            state.createdTempUser = tempuser;
        },
        ADD_USER(state, user) {
            state.users.unshift(user);
            router.push("/users-list");
        },
        REMOVE_USER(state, user) {
            console.log(user);
            state.users = state.users.filter((data) => data.id != user.id);
        },
    },
    actions: {
        registerUser({ state, commit, rootState }) {
            let type = state.createdTempUser.type == "admin" ? 0 : 1;
            let formData = new FormData();
            formData.append("name", state.createdTempUser.name);
            formData.append("email", state.createdTempUser.email);
            formData.append("password", state.createdTempUser.password);
            formData.append(
                "password_confirmation",
                state.createdTempUser.confirmPassword
            );
            formData.append("type", type);
            formData.append("phone", state.createdTempUser.phone);
            formData.append("dob", state.createdTempUser.dob);
            formData.append("address", state.createdTempUser.address);
            formData.append("profile", state.createdTempUser.profile);
            formData.append("create_user_id", rootState.auth.loginUser.id);
            formData.append("updated_user_id", rootState.auth.loginUser.id);
            httpFile()
                .post("/api/users", formData)
                .then((response) => {
                    commit("ADD_USER", response.data.data);
                    rootState.noti.hasMessage = true;
                    rootState.noti.message = "User create successfully";
                    state.createdTempUser = {};
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        getUsers(
            { state, rootState },
            { name, email, startDate, endDate, page }
        ) {
            if (startDate > endDate) {
                rootState.noti.hasError = true;
                rootState.noti.message = "end date must greater than start date";
            } else {
                http()
                    .get(
                        `api/users?name=${name}&email=${email}&startDate=${startDate}&endDate=${endDate}&page=${page}`
                    )
                    .then((response) => {
                        state.lastPage = response.data.last_page;
                        state.users = response.data.data;
                    });
            }
        },

        deleteUser({ commit, rootState }, id) {
            http()
                .delete(`api/users/${id}`)
                .then((response) => {
                    commit("REMOVE_USER", response.data.data);
                    rootState.noti.hasMessage = true;
                    rootState.noti.message = "User delete successfully";
                });
        },
    },
};
