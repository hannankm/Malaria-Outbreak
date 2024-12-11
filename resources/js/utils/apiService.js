// src/services/apiService.js
import axios from "axios";
import store from "@/store";

// Access the authToken from the store directly
const authToken = store.getters["user/authToken"];

const apiService = axios.create({
    baseURL: "http://127.0.0.1:8000/api",
    withCredentials: false,
    headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
        Authorization: `Bearer ${authToken}`, // Add the Authorization header
    },
});

// Export the axios instance
export default apiService;
