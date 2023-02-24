/**
 * change the date format
 * @param {Date} date 
 * @returns {Date} date
 */
export const changeDateFormat = (date) => {
    const unformatDate = new Date(date);
    let formatDate = unformatDate.getFullYear() + '/' +
    (unformatDate.getMonth() < 10 ? '0' : '') + unformatDate.getMonth() + '/' +
    (unformatDate.getDay() < 10 ? '0' : '') + unformatDate.getDay() ;
    return formatDate;
}