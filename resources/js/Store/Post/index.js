import { http, httpdownload, httpFile } from "../../services/http_service";
import router from "../../router/index";
export default {
    state: {
        tempPost: {},
        posts: [],
        page: 1,
        lastPage: 0,
        currentPage:1,
    },
    mutations: {
        // set the temp post
        SET_TEMP_POST(state, post) {
            state.tempPost = post;
        },

        // get
        GET_POST_DATA(state, post) {
            state.tempPost = post;
            router.push("/edit-post");
        },

        // setting all the posts
        GET_POSTS(state, posts) {
            state.posts = posts;
        },

        // add a post on posts state
        ADD_POST(state, post) {
            state.posts.unshift(post);
        },

        // edit a post
        EIDT_POST(state, post) {
            let index = state.posts.findIndex((data) => data.id == post.id);
            state.posts[index].title = post.title;
            state.posts[index].description = post.description;
            state.posts[index].status = post.status ? 1 : 0;
        },

        // delete a post
        DELETE_POST(state, post) {
            state.posts = state.posts.filter((data) => {
                return data.id !== post.id;
            });
        },
    },
    actions: {
        // to clear a temp post
        clearPostData({ state }) {
            state.tempPost = {};
        },

        // get a post from api
        getPostData({ commit }, id) {
            http()
                .get(`/api/posts/${id}`)
                .then((response) => {
                    commit("GET_POST_DATA", response.data);
                });
        },

        // getting all the post from api
        getPosts({ state, commit }, payload) {
            http()
                .get(`/api/posts?search=${payload.search}&page=${payload.page}`)
                .then((response) => {
                    state.currentPage = response.data.current_page;
                    state.lastPage = response.data.last_page;
                    commit("GET_POSTS", response.data.data);
                })
        },

        // create a post
        createPost({ dispatch, state, commit, rootState }) {
            http()
                .post("/api/posts", {
                    title: state.tempPost.title,
                    description: state.tempPost.description,
                    create_user_id: rootState.auth.loginUser.id,
                    updated_user_id: rootState.auth.loginUser.id,
                })
                .then((response) => {
                    commit("ADD_POST", response.data.data);
                    dispatch("clearPostData");
                    rootState.noti.hasMessage = true;
                    rootState.noti.message = "Post created successfully";
                    router.push("/posts-list");
                })
                .catch((error) => {
                    router.push("/create-post");
                    rootState.noti.hasError = true;
                    rootState.noti.message = error.response.data.message;
                });
        },

        // edit a post
        editPost({ state, commit, rootState }) {
            let status = state.tempPost.status ? 1 : 0;
            http()
                .put(`/api/posts/${state.tempPost.id}`, {
                    title: state.tempPost.title,
                    description: state.tempPost.description,
                    updated_user_id: 1,
                    status: status,
                })
                .then((response) => {
                    commit("EIDT_POST", response.data.data);
                    rootState.noti.hasMessage = true;
                    rootState.noti.message = "Post update successfully";
                });
        },

        // delete a post
        deletePost({dispatch,state, commit, rootState }, id) {
            http()
                .delete(`api/posts/${id}`)
                .then((response) => {
                    if(state.posts.length == 1){
                        state.currentPage = state.lastPage-1;
                    }
                    dispatch('getPosts',{search:'',page:state.currentPage});
                    //commit("DELETE_POST", response.data.data);
                    rootState.noti.hasMessage = true;
                    rootState.noti.message = "Post delete successfully";
                });
        },
        // uploading csv to the api
        uploadCsv({ commit, rootState }, csv) {
            let formData = new FormData();
            formData.append("csv", csv);
            httpFile()
                .post("api/upload-csv", formData)
                .then((response) => {
                    response.data.data.forEach((data) => {
                        commit("ADD_POST", data);
                    });
                    rootState.noti.hasMessage = true;
                    rootState.noti.message = "All post upload successfully";
                    router.push("/posts-list");
                })
                .catch((error) => {
                    rootState.noti.hasError = true;
                    rootState.noti.message = error.response.data.message;
                });
        },
        downloadPost({ rootState }) {
            if (!rootState.auth.isLogin) {
                router.push("/login");
                return;
            }
            axios({
                url: "http://localhost:8000/api/download-posts",
                method: "GET",
                headers: {
                    Authorization: `Bearer ` + rootState.auth.token,
                },
                responseType: "blob",
            })
                .then((response) => {
                    var fileURL = window.URL.createObjectURL(
                        new Blob([response.data])
                    );

                    var fileLink = document.createElement("a");

                    fileLink.href = fileURL;

                    fileLink.setAttribute("download", "posts.csv");

                    document.body.appendChild(fileLink);

                    fileLink.click();
                })
        },
    },
    getters: {
        getTempPost(state) {
            return state.tempPost;
        },
        getPosts(state) {
            return state.posts;
        },
    },
};
