// src/api/auth.api.js
import apiService from "@/utils/apiService";

export default {
    // Handle user login
    async loginUser(user) {
        try {
            const response = await apiService.post("/login", user);
            return response.data;
        } catch (error) {
            console.error("Error logging in:", error);
            throw error;
        }
    },

    // Handle user logout
    async logoutUser() {
        try {
            const response = await apiService.post("/logout");
            return response.data;
        } catch (error) {
            console.error("Error logging out:", error);
            throw error;
        }
    },

    // Handle user registration
    async registerUser(user) {
        try {
            const response = await apiService.post("/register", user);
            return response.data;
        } catch (error) {
            console.error("Error registering:", error);
            throw error;
        }
    }
};
