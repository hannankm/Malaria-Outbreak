<template>
    <div class="register">
      <Navigation />
      <el-card class="register-card">
        <h2>Register</h2>
        <el-form :model="registerForm" @submit.prevent="handleRegister">
          <el-form-item label="Name">
            <el-input v-model="registerForm.name" placeholder="Name"></el-input>
          </el-form-item>
          <el-form-item label="Email">
            <el-input v-model="registerForm.email" placeholder="Email"></el-input>
          </el-form-item>
          <el-form-item label="Password">
            <el-input type="password" v-model="registerForm.password" placeholder="Password"></el-input>
          </el-form-item>
          <el-form-item label="Confirm Password">
            <el-input type="password" v-model="registerForm.confirmPassword" placeholder="Confirm Password"></el-input>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" native-type="submit">Register</el-button>
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
        registerForm: {
          name: '',
          email: '',
          password: '',
          confirmPassword: '',
        },
      };
    },
    methods: {
      handleRegister() {
        // Handle registration logic here
        console.log('Register form submitted:', this.registerForm);
      },
    },
  };
  </script>
  
  <style scoped>
  .register {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
    background-color: #f5f5f5;
  }
  
  .register-card {
    width: 400px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }
  </style>
  