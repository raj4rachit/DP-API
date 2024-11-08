import axios from 'axios';
import config from './config';
import { logout } from '../store/authSlice';
import { store } from '../store';

const axiosClient = axios.create({
    baseURL: `${config.apiBaseURL}`,
    withCredentials: true,
    headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
    },
});

// Request Interceptor: Attach Authorization token to every request
axiosClient.interceptors.request.use(
    (req) => {
        const auth = store.getState().auth;
        const authToken = auth.token;

        if (authToken) {
            req.headers.Authorization = `Bearer ${authToken}`;
        }
        return req;
    },
    (error) => {
        console.error('Axios Interceptors Request is Failed. ', error);
        return Promise.reject(error);
    },
);

// Response Interceptor: Handle Unauthorized Access (401)
axiosClient.interceptors.response.use(
    (res) => res,
    (error) => {
        if (error.response && error.response.status === 401) {
            store.dispatch(logout());
        }
        return Promise.reject(error);
    },
);

export default axiosClient;
