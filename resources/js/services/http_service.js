import store from "../Store";
export const http = () => {
    return axios.create({
        baseURL: store.state.baseURL,
        headers: {
            Authorization: `Bearer ` + store.state.auth.token,
            "Content-Type": "application/json",
        },
    });
};

export const httpFile = () => {
    return axios.create({
        baseURL: store.state.baseURL,
        headers: {
            Authorization: `Bearer ` + store.state.auth.token,
            "Content-type": "multipart/form-data",
        },
    });
};

export const httpdownload = () => {
    return axios.create({
        baseURL: store.state.baseURL,
        headers: {
            Authorization: `Bearer ` + store.state.auth.token,
        },
        responseType: "blob",
    });
};
