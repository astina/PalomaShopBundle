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
    }
};