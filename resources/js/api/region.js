import axios from 'axios';

const apiClient = axios.create({
  baseURL: 'http://127.0.0.1:8000/api',
  withCredentials: false,
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json',
  },
});

apiClient.interceptors.request.use(config => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export default {
  getRegions(search = '') {
    return apiClient.get('/regions', { params: { search } });
  },
  createRegion(region) {
    return apiClient.post('/regions', region);
  },
  updateRegion(regionId, region) {
    return apiClient.put(`/regions/${regionId}`, region);
  },
  deleteRegion(regionId) {
    return apiClient.delete(`/regions/${regionId}`);
  },
};
