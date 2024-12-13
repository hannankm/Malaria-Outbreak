import apiService from "@/utils/apiService";
import store from "@/store";

export default {
    // Fetch all users with an optional status filter
    fetchUsers(status) {
        const params = status ? { status } : {};
        return apiService.get("/users", { params });
    },

    fetchUsersinRegion(status) {
        const regionId = store.getters["user/regionId"];

        const params = status ? { status } : {};
        return apiService.get(`/users/region/${regionId}`, { params });
    },

    // Fetch details of a specific user
    fetchUserDetails(userId) {
        return apiService.get(`/users/${userId}`);
    },

    // Create a new user
    createUser(userData) {
        return apiService.post("/users", userData);
    },

    // Update user information
    updateUser(userId, updatedData) {
        return apiService.put(`/users/${userId}`, updatedData);
    },

    // Delete a user
    deleteUser(userId) {
        return apiService.delete(`/users/${userId}`);
    },

    // Update user status (approve, reject, suspend)
    updateUserStatus(userId, newStatus) {
        return apiService.patch(`/users/${userId}/status`, {
            status: newStatus,
        });
    },
};
