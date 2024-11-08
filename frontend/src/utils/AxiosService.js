import axiosClient from '@configs/axios';

export const AxiosAuthService = {
    get(url, params) {
        return this.handleAuthRequest('get', url, { params });
    },

    post(url, data, headers = {}) {
        return this.handleAuthRequest('post', url, data, headers);
    },

    put(url, data, headers = {}) {
        return this.handleAuthRequest('put', url, data, headers);
    },

    delete(url, headers = {}) {
        return this.handleAuthRequest('delete', url, null, headers);
    },

    async handleAuthRequest(method, url, data = null, headers = {}) {
        try {
            const response = await axiosClient[method](url, data, { headers });
            return response;
        } catch (error) {
            console.error(`Axios ${method.toUpperCase()} request failed: `, error);
            throw error;
        }
    },
};

export const AxiosServices = {
    get(url, params) {
        return this.handleRequest('get', url, { params });
    },

    post(url, data) {
        return this.handleRequest('post', url, data);
    },

    put(url, data) {
        return this.handleRequest('put', url, data);
    },

    delete(url) {
        return this.handleRequest('delete', url);
    },

    async handleRequest(method, url, data = null) {
        try {
            const response = await axiosClient[method](url, data);
            return response;
        } catch (error) {
            console.error(`Axios ${method.toUpperCase()} request failed: `, error);
            throw error;
        }
    },
};
