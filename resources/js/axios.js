import axios from 'axios';

const instance = axios.create({
  baseURL: '/api', // Set your API base URL
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
});

export default instance;
