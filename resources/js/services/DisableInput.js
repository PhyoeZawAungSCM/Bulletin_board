/**
 * disable all the input field of the modal
 */
export const disableFormInputs = () => {
    const inputs = document.getElementsByTagName("input");
    const selects = document.getElementsByTagName("select");
    const textareas = document.getElementsByTagName("textarea");

    for (var i = 0; i < selects.length; i++) {
        selects[i].disabled = true;
    }

    for (let i = 0; i < inputs.length; i++) {
        inputs[i].disabled = true;
    }

    for (var i = 0; i < textareas.length; i++) {
        textareas[i].disabled = true;
    }
};
