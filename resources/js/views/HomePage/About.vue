<template>
    <div class="about">
      <Navigation />
      <el-card class="about-card">
        <h2>About Us</h2>
        <p>Welcome to the Malaria Outbreak Registration system. Our mission is to help track and manage malaria outbreaks effectively. This platform provides tools for registering outbreaks, monitoring progress, and ensuring timely responses to malaria cases.</p>
        <p>Our team is dedicated to improving public health and saving lives through efficient and accurate data management. Thank you for being a part of this initiative.</p>
      </el-card>
    </div>
  </template>
  
  <script>
  import Navigation from '../../layouts/components/Navigation.vue';
  
  export default {
    components: {
      Navigation,
    },
  };
  </script>
  
  <style scoped>
  .about {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
    background-color: #f5f5f5;
  }
  
  .about-card {
    width: 800px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    text-align: center;
  }
  </style>
  