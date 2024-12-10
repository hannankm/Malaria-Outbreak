<template>
    <div>
      <el-input v-model="search" placeholder="Search regions..." @input="searchRegions" />
      <el-table :data="filteredRegions">
        <el-table-column prop="name" label="Region Name" />
        <el-table-column label="Actions">
          <template #default="scope">
            <el-button @click="editRegion(scope.row)">Edit</el-button>
            <el-button @click="deleteRegion(scope.row)">Delete</el-button>
          </template>
        </el-table-column>
      </el-table>
  
      <div>
        <h3>Create New State</h3>
        <el-input v-model="newRegion.name" placeholder="Region Name" />
        <el-button type="primary" @click="createRegion">Create</el-button>
      </div>
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
  
      const newRegion = ref({ name: '' });
  
      const filteredRegions = computed(() =>
        regions.value.filter(region =>
          region.name.toLowerCase().includes(search.value.toLowerCase())
        )
      );
  
      const searchRegions = () => {};
  
      const createRegion = () => {
        if (newRegion.value.name.trim()) {
          regions.value.push({ ...newRegion.value });
          newRegion.value.name = '';
          ElMessage.success('Region created successfully');
        } else {
          ElMessage.error('Please enter a region name');
        }
      };
  
      const editRegion = (region) => {};
  
      const deleteRegion = (region) => {
        regions.value = regions.value.filter(r => r.id !== region.id);
        ElMessage.success('Region deleted successfully');
      };
  
      return {
        search,
        filteredRegions,
        newRegion,
        searchRegions,
        createRegion,
        editRegion,
        deleteRegion,
      };
    },
  };
  </script>
  