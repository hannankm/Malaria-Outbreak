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

// Add a request interceptor to include the token
apiClient.interceptors.request.use(config => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// Export methods to interact with the backend
export default {
  // Handle user registration
  registerUser(user) {
    return apiClient.post('/register', user);
  },
  
  // Fetch regions
  async getRegions() {
    try {
      const response = await apiClient.get('/regions');
      console.log('API Response:', response);
      return response.data?.data || [];
    } catch (error) {
      console.error('API failed to fetch regions:', error);
      return [];
    }
  },

  // Fetch zones based on a selected region
  async getZones(regionId) {
    try {
      const response = await apiClient.get(`/regions/${regionId}/zones`);
      return response.data?.data || [];
    } catch (error) {
      console.error('Error fetching zones:', error);
      return [];
    }
  },

  // Fetch woredas based on a selected zone
  async getWoredas(zoneId) {
    try {
      const response = await apiClient.get(`/zones/${zoneId}/woredas`);
      return response.data?.data || [];
    } catch (error) {
      console.error('Error fetching woredas:', error);
      return [];
    }
  },
};
