export default {

    clone(obj) {
        return JSON.parse(JSON.stringify(obj));
    },

    removeElem(arr, elem) {

        const index = arr.indexOf(elem);
        if (index === -1) {
            return arr;
        }

        arr.splice(index, 1);

        return arr;
    },

    queryString(params) {

        const query = [];
        for (const param in params) {
            if (params.hasOwnProperty(param)) {

                let value = params[param];

                if (typeof value === 'object') {
                    value = JSON.stringify(value);
                }

                query.push(encodeURIComponent(param) + '=' + encodeURIComponent(value));
            }
        }

        return query.join('&');
    },

    validators: {
        isValidDate(value) {

            if (!value) {
                return true;
            }

            const match = value.match(/^(\d{1,2})\.(\d{1,2})\.(\d{4})$/);

            if (!match) {
                return false;
            }

            try {

                const day = parseInt(match[1].replace(/^0/, ''));
                const monthIndex = parseInt(match[2].replace(/^0/, '')) - 1;
                const year = parseInt(match[3]);

                const date = new Date(year, monthIndex, day);

                return date.getDate() === day
                    && date.getMonth() === monthIndex
                    && date.getFullYear() === year;

            } catch (e) {
                return false;
            }
        }
    }
};