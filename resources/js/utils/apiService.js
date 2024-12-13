import axios from "axios";
import store from "@/store";

// Access the authToken dynamically to ensure it stays updated
const apiService = axios.create({
  baseURL: "http://127.0.0.1:8000/api",
  withCredentials: false,
  headers: {
    Accept: "application/json",
    "Content-Type": "application/json",
  },
});

// Interceptor to add Authorization dynamically
apiService.interceptors.request.use((config) => {
  const authToken = store.getters["user/authToken"];
  if (authToken) {
    config.headers.Authorization = `Bearer ${authToken}`;
  }
  return config;
});
// apiService.interceptors.response.use(
//     (response) => response,
//     (error) => {
//       if (error.response && error.response.status === 401) {
//         store.dispatch("user/logoutUser"); // Log the user out
//         window.location.href = "/login"; // Redirect to login page
//       }
//       return Promise.reject(error);
//     }
//   );

export default apiService;
