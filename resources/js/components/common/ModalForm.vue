<template>
    <el-dialog :visible.sync="isVisible" title="Enter Details">
      <el-form :model="formData" label-width="80px">
        <el-form-item label="Name">
          <el-input v-model="formData.name" placeholder="Enter name"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="closeModal">Cancel</el-button>
        <el-button type="primary" @click="saveData">Save</el-button>
      </div>
    </el-dialog>
  </template>
  
  <script>
  export default {
    props: {
      isVisible: {
        type: Boolean,
        required: true,
      },
      formData: {
        type: Object,
        required: true,
      },
    },
    emits: ['close', 'save'],
    methods: {
      closeModal() {
        this.$emit('close');
      },
      saveData() {
        this.$emit('save', this.formData);
      },
    },
  };
  </script>
  