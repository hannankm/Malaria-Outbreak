<template>
    <el-header>
      <el-menu :default-active="activeIndex" class="el-menu-demo" mode="horizontal">
        <el-menu-item index="1" @click="navigateTo('home')">Home</el-menu-item>
        <el-menu-item index="2" @click="navigateTo('about')">About</el-menu-item>
        <el-menu-item index="3" @click="navigateTo('login')">Login</el-menu-item>
        <el-menu-item index="4" @click="navigateTo('register')">Register</el-menu-item>
        <el-menu-item index="5" @click="navigateTo('region')">Region manager </el-menu-item>
      </el-menu>
    </el-header>
  </template>
  
  <script>
  export default {
    data() {
      return {
        activeIndex: '1',
      };
    },
    methods: {
      navigateTo(route) {
        this.$router.push(`/${route}`);
      },
    },
  };
  </script>
  
  <style scoped>
  .el-header {
    background-color: transparent;
    border-bottom: none;
  }
  </style>
  