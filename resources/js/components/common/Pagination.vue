<template>
    <el-pagination
      :current-page="currentPage"
      :page-size="pageSize"
      :total="totalItems"
      @current-change="changePage"
    ></el-pagination>
  </template>
  
  <script>
  export default {
    props: {
      currentPage: {
        type: Number,
        required: true,
      },
      pageSize: {
        type: Number,
        default: 10,
      },
      totalItems: {
        type: Number,
        required: true,
      },
    },
    emits: ['page-change'],
    methods: {
      changePage(page) {
        this.$emit('page-change', page);
      },
    },
  };
  </script>
  