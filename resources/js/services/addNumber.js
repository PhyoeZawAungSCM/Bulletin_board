/**
 *  adding the No column of User table auto increment with pagination
 * @param {Integer} to how long have you through the pagination elements
 * @param {Integer} per_page how many element are in one pagination
 * @returns {Array} array of column number of pagination
 */
export const addNumberToPaginate = (to, per_page) => {
    let no = [];

    /**if the pagination is the first paginate page
     * only return the array of number of element just like
     *  [1,2,3,4,5] for 5 element per paginate
     */

    if (to == per_page) {
        for (let i = 1; i <= to; i++) {
            no.push(i);
        }
        return no;
    }

    /**
     * if the pagination is not the fist page ,
     * "to" may greater than the "per_page" and
     * increment counter for 1 for one substraction from "to" to "per_page"
     */
    let count = 0;
    let remove_to = 0;
    let temp_to = to;

    while (temp_to > per_page) {
        temp_to = temp_to - per_page;
        count++;
    }
    // "removeTo" is the number where we want to remove start
    remove_to = per_page * count;
    // looping through to "to" and if the item is less than or equal to "remove_to" not insert in array;
    for (let i = 1; i <= to; i++) {
        if (i <= remove_to) continue;
        no.push(i);
    }
    return no;
};
