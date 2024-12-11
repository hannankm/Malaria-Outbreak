import axios from 'axios';

// Create the axios instance with baseURL configuration
const apiClient = axios.create({
  baseURL: 'http://127.0.0.1:8000/api', // Your backend URL
  withCredentials: false,
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json',
  },
});

// Export methods to interact with the backend
export default {
  // Handle user login
  async loginUser(user) {
    try {
      const response = await apiClient.post('/login', user);
      return response.data;
    } catch (error) {
      console.error('Error logging in:', error);
      throw error;
    }
  },
  // Handle user logout
  async logoutUser() {
    try {
      const response = await apiClient.post('/logout');
      return response.data;
    } catch (error) {
      console.error('Error logging out:', error);
      throw error;
    }
  },
};
