import { http, httpFile } from "../../services/http_service";
import router from "../../router";
import { addNumberToPaginate } from "../../services/addNumber";
export default {
    state: {
        createdTempUser: {},
        users: [],
        to: 0,
        per_page: 0,
        page: 1,
        lastPage: 0,
        currentPage:0,
        no: [],
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
        },

        /**
         * getting all the user of bulletin board
         */
        getUsers(
            { state, rootState },
            { name, email, startDate, endDate, page }
        ) {
            if (startDate > endDate) {
                rootState.noti.hasError = true;
                rootState.noti.message =
                    "end date must greater than start date";
            } else {
                http()
                    .get(
                        `api/users?name=${name}&email=${email}&startDate=${startDate}&endDate=${endDate}&page=${page}`
                    )
                    .then((response) => {
                        let no = addNumberToPaginate(
                            response.data.to,
                            response.data.per_page
                        );
                        let users = response.data.data;
                        users.forEach((user, index) => {
                            user.no = no[index];
                        });
                        state.lastPage = response.data.last_page;
                        state.currentPage = response.data.current_page;
                        state.users = users;
                    });
            }
        },

        /**
         *delete a user
         * @param {int} id
         */
        deleteUser({ commit,dispatch, rootState ,state}, id) {
            http()
                .delete(`api/users/${id}`)
                .then((response) => {
                    if(state.users.length == 1){
                        state.currentPage = state.lastPage-1;
                    }
                    dispatch('getUsers', { name:'', email:'', startDate:'', endDate:'', page:state.currentPage });
                    //commit("REMOVE_USER", response.data.data);
                    rootState.noti.hasMessage = true;
                    rootState.noti.message = "User delete successfully";
                })
                .catch((error) => {
                    rootState.noti.hasError = true;
                    rootState.noti.message = "Cannot delete this user";
                });
        },
    },
};
