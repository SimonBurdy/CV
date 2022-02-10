import axios from "axios";

axios.defaults.baseURL = '/admin/api'
axios.defaults.headers = {
    'Accept': 'application/json',
    'Content-Type': 'application/json'
}
axios.interceptors.response.use(
    response => {
        if (response.status === 200 || response.status === 201) {
            return Promise.resolve(response);
        } else {
            return Promise.reject(response);
        }
    },
    error => {
        if (error.response.status) {
            switch (error.response.status) {
                case 403:

                    alert('api : acces interdit');
                    break;

                case 401:
                    alert("session expired");
                    break;
                // case 403:
                //     router.replace({
                //         path: "/login",
                //         query: { redirect: router.currentRoute.fullPath }
                //     });
                //     break;
                case 404:
                    alert('api : 404');
                    break;
                // case 502:
                //     setTimeout(() => {
                //         router.replace({
                //             path: "/login",
                //             query: {
                //                 redirect: router.currentRoute.fullPath
                //             }
                //         });
                //     }, 1000);
            }
            return Promise.reject(error.response);
        }
    }
);
export default {
    async get(endpoint,params, config) {
        const {data} = await axios.get(endpoint,params,config);
        return data;
    },
    async post(endpoint, params, config) {
        const {data} = await axios.post(endpoint,params,config);
        return data;
    },
    async put(endpoint, params, config) {
        const {data} = await axios.put(endpoint,params,config);
        return data;
    },
    async delete(endpoint, params, config) {
        const {data} = await axios.delete(endpoint, params, config);
        return data;
    }
}
