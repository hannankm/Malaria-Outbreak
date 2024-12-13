import apiService from "@/utils/apiService";

export default {
  // Fetch all users with an optional status filter
  fetchUsers(status) {
    const params = status ? { status } : {};
    return apiService.get('/user', { params });
  },

  // Fetch details of a specific user
  fetchUserDetails(userId) {
    return apiService.get(`/user/${userId}`);
  },

  // Create a new user
  createUser(userData) {
    return apiService.post('/user', userData);
  },

  // Update user information
  updateUser(userId, updatedData) {
    return apiService.put(`/user/${userId}`, updatedData);
  },

  // Delete a user
  deleteUser(userId) {
    return apiService.delete(`/user/${userId}`);
  },

  // Update user status (approve, reject, suspend)
  updateUserStatus(userId, newStatus) {
    return apiService.patch(`/user/${userId}/status`, { status: newStatus });
  },
};
