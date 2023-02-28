import VueRouter from "vue-router";
import Login from "../Views/Auth/Login.vue";
import Home from "../Views/HomeView.vue";
import UserList from "../components/User/UserList.vue";
import ForgotPassword from "../Views/Auth/password/ForgotPassword.vue";
import ResetPassword from "../Views/Auth/password/ResetPassword.vue";
import PostList from "../components/Post/PostList.vue";
import UserRegister from "../Views/User/UserRegisterView.vue";
import UserRegisterConfirm from "../Views/User/UserRegisterConfirmView.vue";
import CreatePostView from "../Views/Post/CreatePostView.vue";
import CreatePostConfirmView from "../Views/Post/CreatePostConfirmView.vue";
import EditPost from "../Views/Post/EditPostView.vue";
import EditPostConfirm from "../Views/Post/EditPostConfirmView.vue";
import Profile from "../Views/Auth/Profile.vue";
import UploadPost from "../Views/Post/UploadPost.vue";
import EditProfile from "../Views/Auth/EditProfile.vue";
import ChangePassword from "../Views/Auth/password/ChangePassword.vue";
import PageNotFound from '../Views/PageNotFound.vue';
import store from "../Store";
import { isLogin } from "../services/Auth_service";
const router = new VueRouter({
    mode: "history",
    routes: [
        {
            path: "/",
            name: "/",
            beforeEnter: (to, form, next) => {
               
                    next("/posts-list");
               
                }
            },
        
        {
            path: "/home",
            name: "home",
            component: Home,
            children: [
                {
                    path: "/users-list",
                    name: "users-list",
                    component: UserList,
                    beforeEnter: (to, form, next) => {
                        if (!isLogin()) {
                            next("/login");
                        } else {
                            next();
                        }
                    },
                },
                {
                    path: "/posts-list",
                    name: "posts-list",
                    component: PostList,
                },
            ],
        },
        {
            path: "/login",
            name: "login",
            component: Login,
            beforeEnter: (to, form, next) => {
                if (isLogin()) {
                    next("/posts-list");
                } else {
                    next();
                }
            },
        },
        {
            path: "/forgotten-password",
            name: "forgotten-password",
            component: ForgotPassword,
            beforeEnter: (to, form, next) => {
                if (isLogin()) {
                    next("/posts-list");
                } else {
                    next();
                }
            },
        },

        {
            path: "/reset-password/:token",
            name: "reset-password",
            component: ResetPassword,
            props: true,
        },
        {
            path: "/register",
            name: "registerUser",
            component: UserRegister,
            beforeEnter: (to, form, next) => {
                if (!isLogin()) {
                    next("/login");
                } else {
                    next();
                }
            },
        },
        {
            path: "/register-confirm",
            name: "registerUserConfirm",
            component: UserRegisterConfirm,
            beforeEnter: (to, form, next) => {
                if (!isLogin()) {
                    next("/login");
                } else {
                    next();
                }
            },
        },
        {
            path: "/create-post",
            name: "createPost",
            component: CreatePostView,
            beforeEnter: (to, form, next) => {
                if (!isLogin()) {
                    next("/login");
                } else {
                    next();
                }
            },
        },
        {
            path: "/create-post-confirm",
            name: "createPostConfirm",
            component: CreatePostConfirmView,
            beforeEnter: (to, form, next) => {
                if (!isLogin()) {
                    next("/login");
                } else {
                    next();
                }
            },
        },
        {
            path: "/edit-post",
            name: "editPost",
            component: EditPost,
            beforeEnter: (to, form, next) => {
                if (!isLogin()) {
                    next("/login");
                } else {
                    next();
                }
            },
        },
        {
            path: "/edit-post-confirm",
            name: "editPostConfirm",
            component: EditPostConfirm,
            beforeEnter: (to, form, next) => {
                if (!isLogin()) {
                    next("/login");
                } else {
                    next();
                }
            },
        },
        {
            path: "/profile",
            name: "Profile",
            component: Profile,
            beforeEnter: (to, form, next) => {
                if (!isLogin()) {
                    next("/login");
                } else {
                    next();
                }
            },
        },
        {
            path: "/upload-post",
            name: "upload-post",
            component: UploadPost,
            beforeEnter: (to, form, next) => {
                if (!isLogin()) {
                    next("/login");
                } else {
                    next();
                }
            },
        },
        {
            path: "/edit-profile",
            name: "editProfile",
            component: EditProfile,
            beforeEnter: (to, form, next) => {
                if (!isLogin()) {
                    next("/login");
                } else {
                    next();
                }
            },
        },
        {
            path: "/change-password",
            name: "changePassword",
            component: ChangePassword,
            beforeEnter: (to, form, next) => {
                if (!isLogin()) {
                    next("/login");
                } else {
                    next();
                }
            },
        },
        {
            path:'/*',
            name:'page-not-found',
            component:PageNotFound,
        }
    ],
});

export default router;
