import store from "../Store";
import CryptoJS from "crypto-js";
/**
 * checking the user is remember me option
 */
export const isLogin = () => {
    let token = localStorage.getItem("token");
    if (token != null) {
        token = CryptoJS.AES.encrypt(token, "bulletinboard").toString(
            CryptoJS.enc.Utf8
        );
    }
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
    if (token != null) {
        return CryptoJS.AES.decrypt(token, "bulletinboard").toString(
            CryptoJS.enc.Utf8
        );
    }
    return token;
};
