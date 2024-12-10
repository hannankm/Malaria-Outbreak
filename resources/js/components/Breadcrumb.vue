<template>
    <el-breadcrumb separator="/">
      <el-breadcrumb-item v-for="(item, index) in breadcrumbItems" :key="index">
        <router-link :to="item.link">{{ item.label }}</router-link>
      </el-breadcrumb-item>
    </el-breadcrumb>
  </template>
  
  <script>
  export default {
    props: {
      breadcrumbItems: {
        type: Array,
        required: true,
      },
    },
  };
  </script>
  
  <style scoped>
  .el-breadcrumb {
    margin: 16px 0;
  }
  </style>
  