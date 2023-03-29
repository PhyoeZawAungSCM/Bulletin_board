/**
 * change the date format
 * @param {Date} date 
 * @returns {Date} date
 */
export const changeDateFormat = (date) => {
    const unformatDate = new Date(date);
    let formatDate = unformatDate.getFullYear() + '/' +
    (unformatDate.getMonth() + 1 < 10 ? '0' : '') + (unformatDate.getMonth() + 1) + '/' +
    (unformatDate.getDate() < 10 ? '0' : '') + unformatDate.getDate();
    return formatDate;
}