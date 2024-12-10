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
      </el-card>
    </div>
  </template>
  
  <script>
  import Navigation from '../layouts/components/Navigation.vue';
  
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
      };
    },
    methods: {
      handleLogin() {
        // Handle login logic here
        console.log('Login form submitted:', this.loginForm);
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
  