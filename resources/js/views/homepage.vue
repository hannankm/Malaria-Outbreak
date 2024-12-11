<template>
    <div class="home">
      <Navigation />
      <div class="breadcrumb-container">
        <Breadcrumb :breadcrumbItems="breadcrumbItems" />
      </div>
      <div class="hero">
        <h1 class="animated-text">Welcome to Malaria Outbreak Registration</h1>
        <p>Track and manage malaria outbreaks effectively.</p>
        <el-button type="primary" @click="navigateTo('register')">Get Started</el-button>
        <el-button type="secondary" @click="navigateTo('regions')">Region Manager</el-button> 
      </div>
      <!-- <div class="link-buttons">
        <el-button @click="navigateTo('login')">Login</el-button>
        <el-button @click="navigateTo('register')">Register</el-button>
        <el-button @click="navigateTo('about')">About</el-button>
      </div> -->
    </div>
  </template>
  
  <script>
  import Navigation from '../layouts/components/Navigation.vue';
  import Breadcrumb from '../components/Breadcrumb.vue';
  
  export default {
    components: {
      Navigation,
      Breadcrumb,
    },
    data() {
      return {
        breadcrumbItems: [
          { label: 'Home', link: '/' },
          // Add more breadcrumb items if needed
        ],
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
  .home {
    background: url('../assets/img1.jpg') no-repeat center center;
    background-size: cover;
    height: 100vh;
    display: flex;
    flex-direction: column;
  }
  
  .breadcrumb-container {
    padding: 0 20px;
  }
  
  .hero {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    color: white;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
  }
  
  .hero h1 {
    font-size: 4rem;
    margin: 0;
    position: relative;
    overflow: hidden;
    white-space: nowrap;
    animation: slide-in 10s infinite alternate;
  }
  
  .animated-text {
    animation: slide-right 10s linear infinite;
  }
  
  @keyframes slide-in {
    0% {
      transform: translateX(-100%);
    }
    100% {
      transform: translateX(0);
    }
  }
  
  .hero p {
    font-size: 1.5rem;
    margin: 20px 0;
  }
  
  .hero .el-button {
    margin-top: 20px;
    animation: fade-in 3s ease-in-out;
  }
  .hero .el-button:nth-child(2) {
  margin-left: 10px;
}

  .link-buttons {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 20px;
  }
  
  @keyframes fade-in {
    0% {
      opacity: 0;
    }
    100% {
      opacity: 1;
    }
  }
  </style>
  