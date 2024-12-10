<template>
    <el-input
      :model-value="modelValue"
      @input="updateValue"
      placeholder="Search..."
    />
  </template>
  
  <script>
  export default {
    name: 'SearchBar',
    props: {
      modelValue: {
        type: String,
        required: true,
      },
    },
    methods: {
      updateValue(value) {
        this.$emit('update:modelValue', value); // Emit the update for v-model
      },
    },
  };
  </script>
  