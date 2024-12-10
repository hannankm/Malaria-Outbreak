import '@/bootstrap';
import axios from 'axios';
import { ElMessage } from 'element-plus';

// Create axios instance
const service = axios.create({
  baseURL: process.env.MIX_BASE_API || 'http://your-api-url/api', // Use .env variable or fallback URL
  timeout: 10000, // Request timeout
});

// Request interceptor
service.interceptors.request.use(
  config => {
    const token = localStorage.getItem('token');
    if (token) {
      config.headers['Authorization'] = 'Bearer ' + token; // Set JWT token
    }
    return config;
  },
  error => {
    console.log(error); // For debug
    return Promise.reject(error);
  }
);

// Response pre-processing
service.interceptors.response.use(
  response => {
    if (response.headers.authorization) {
      localStorage.setItem('token', response.headers.authorization);
      response.data.token = response.headers.authorization;
    }
    return response.data;
  },
  error => {
    let errorMessage = error.response.data;

    if (errorMessage.message) {
      errorMessage = errorMessage.message;
    }

    ElMessage({
      message: errorMessage,
      type: 'error',
      duration: 5 * 1000,
    });

    return Promise.reject(error);
  }
);

export default service;
