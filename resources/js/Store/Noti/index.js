export default {
    state: {
        message: "",
        hasMessage: false,
        hasError: false,
    },
    getters: {
        getMessage(state) {
            return state.message;
        },
        getHasMessage(state) {
            return state.hasMessage;
        },
        getHasError(state) {
            return state.hasError;
        },
    },
};
