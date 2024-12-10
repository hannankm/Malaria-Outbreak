<template>
    <div>
      <el-input v-model="search" placeholder="Search regions..." @input="searchRegions" />
      <el-button type="primary" @click="openCreateModal">Create Region</el-button>
      <el-table :data="filteredRegions">
        <el-table-column prop="name" label="Region Name" />
        <el-table-column label="Actions">
          <template #default="scope">
            <el-button @click="editRegion(scope.row)">Edit</el-button>
            <el-button @click="deleteRegion(scope.row)">Delete</el-button>
          </template>
        </el-table-column>
      </el-table>
  
      <el-dialog title="Create Region" :visible.sync="isCreateModalVisible" @close="handleCloseCreateModal">
        <el-input v-model="newRegion.name" placeholder="Region Name" />
        <span slot="footer" class="dialog-footer">
          <el-button @click="isCreateModalVisible = false">Cancel</el-button>
          <el-button type="primary" @click="createRegion">Create</el-button>
        </span>
      </el-dialog>
    </div>
  </template>
  
  <script>
  import { ref, computed } from 'vue';
  import { ElMessage } from 'element-plus';
  
  export default {
    setup() {
      const search = ref('');
      const regions = ref([
        { id: 1, name: 'Region 1' },
        { id: 2, name: 'Region 2' },
      ]);
  
      const isCreateModalVisible = ref(false);
      const newRegion = ref({ name: '' });
  
      const filteredRegions = computed(() =>
        regions.value.filter(region =>
          region.name.toLowerCase().includes(search.value.toLowerCase())
        )
      );
  
      const searchRegions = () => {};
  
      const openCreateModal = () => {
        isCreateModalVisible.value = true;
      };
  
      const createRegion = () => {
        if (newRegion.value.name.trim()) {
          regions.value.push({ ...newRegion.value });
          newRegion.value.name = '';
          isCreateModalVisible.value = false;
          ElMessage.success('Region created successfully');
        } else {
          ElMessage.error('Please enter a region name');
        }
      };
  
      const handleCloseCreateModal = () => {
        newRegion.value.name = '';
      };
  
      const editRegion = (region) => {
        // Implement edit functionality
      };
  
      const deleteRegion = (region) => {
        regions.value = regions.value.filter(r => r.id !== region.id);
        ElMessage.success('Region deleted successfully');
      };
  
      return {
        search,
        filteredRegions,
        isCreateModalVisible,
        newRegion,
        searchRegions,
        openCreateModal,
        createRegion,
        handleCloseCreateModal,
        editRegion,
        deleteRegion,
      };
    },
  };
  </script>
  