import axios from 'axios';

export function useApi() {
    const defaultConfig = {
        baseURL: '/api/',
    };

    const get = (url, headers = {}, options = {}) => {
        const config = {
            ...defaultConfig,
            headers,
            ...options,
        };

        return new Promise((resolve, reject) => {
            axios.get(url, config)
                .then((response) => {
                    resolve(response.data);
                })
                .catch((error) => {
                    reject(error.response.data);
                });
        });
    };

    const post = (url, data, headers = {}, options = {}) => {
        const config = {
            ...defaultConfig,
            headers: {
                'Content-Type': 'application/json',
                ...headers,
            },
            ...options,
        };

        return new Promise((resolve, reject) => {
            axios.post(url, data, config)
                .then((response) => {
                    resolve(response.data);
                })
                .catch((error) => {
                    reject(error.response.data);
                });
        });
    };

    return {
        get,
        post,
    };
}
