<template>
  <div class="login">
    <Navigation />
    <el-card class="login-card">
      <h2>Login</h2>
      <el-form :model="loginForm" @submit.prevent="handleLogin">
        <el-form-item label="Email">
          <el-input v-model="loginForm.email" placeholder="Email"></el-input>
        </el-form-item>
        <el-form-item label="Password">
          <el-input type="password" v-model="loginForm.password" placeholder="Password"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" native-type="submit">Login</el-button>
        </el-form-item>
      </el-form>
      <el-alert v-if="errorMessage" :title="errorMessage" type="error" show-icon></el-alert>
      <el-alert v-if="successMessage" :title="successMessage" type="success" show-icon></el-alert>
    </el-card>
  </div>
</template>

<script>
import Navigation from '../layouts/components/Navigation.vue';
import api from '../api/login';
import { mapActions } from 'vuex';

export default {
  components: {
    Navigation,
  },
  data() {
    return {
      loginForm: {
        email: '',
        password: '',
      },
      errorMessage: '',
      successMessage: '',
    };
  },
  methods: {
    ...mapActions(['login']),
    async handleLogin() {
      try {
        console.log('Login form submitted:', this.loginForm);
        const response = await api.loginUser(this.loginForm);
        console.log('Login successful:', response);

        // Dispatch Vuex action to store user data
        this.login({
          token: response.token,
          user: {
            name: response.user.name,
            regionId: response.user.region_id,
            woreda: response.user.woreda,
            role: response.role[0], // Assuming the role is an array
          },
        });

        // Handle successful login
        this.successMessage = response.message;
        this.errorMessage = '';
        
        // Redirect to a different page after successful login
        this.$router.push('/dashboard'); // Change this to your desired route
      } catch (error) {
        console.error('Login failed:', error);
        // Handle login errors
        this.errorMessage = error.response?.data?.errors || error.response?.data?.message || 'Login failed';
        this.successMessage = '';
      }
    },
  },
};
</script>

<style scoped>
.login {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 100vh;
  background-color: #f5f5f5;
}

.login-card {
  width: 400px;
  padding: 20px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
</style>
