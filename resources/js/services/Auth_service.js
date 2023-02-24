import store from "../Store";
/**
 * checking the user is remember me option
 */
export const isLogin = () => {
    let token = localStorage.getItem("token");
    if (token == null) {
        token = store.state.auth.token == "" ? null : store.state.auth.token;
    }
    return token != null;
};
/**
 * get the token
 * @returns token
 */
export const token = () => {
    const token = localStorage.getItem("token");
    return token;
};
