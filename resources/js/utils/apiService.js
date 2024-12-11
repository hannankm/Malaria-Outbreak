import axios from "axios";
import store from "@/store";

const apiService = axios.create({
    baseURL: "http://127.0.0.1:8000/api",
    withCredentials: true,
    headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
    },
});

// Add a request interceptor to dynamically set the Authorization header
apiService.interceptors.request.use(
    (config) => {
        const authToken = store.getters["user/authToken"]; // Get the latest token
        if (authToken) {
            config.headers.Authorization = `Bearer ${authToken}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

export default apiService;
