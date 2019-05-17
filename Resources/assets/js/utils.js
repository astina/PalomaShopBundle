export default {

    clone(obj) {
        return JSON.parse(JSON.stringify(obj));
    },

    queryString(params) {

        const query = [];
        for (const param in params) {
            if (params.hasOwnProperty(param)) {
                const value = params[param];
                query.push(encodeURIComponent(param) + '=' + encodeURIComponent(value));
            }
        }

        return query.join('&');
    }
};