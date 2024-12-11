<template>
    <div>
      <el-input v-model="searchQuery" placeholder="Search Regions" @input="fetchRegions" class="mb-4"></el-input>
      <el-button type="primary" @click="showCreateDialog">Create Region</el-button>
      
      <el-table :data="regions" stripe style="width: 100%" class="mt-4">
        <el-table-column prop="name" label="Region Name"></el-table-column>
        <el-table-column label="Actions">
          <template slot-scope="scope">
            <el-button size="mini" @click="showEditDialog(scope.row)">Edit</el-button>
            <el-button size="mini" type="danger" @click="deleteRegion(scope.row.id)">Delete</el-button>
          </template>
        </el-table-column>
      </el-table>
  
      <!-- Create/Edit Dialog -->
      <el-dialog :visible.sync="isDialogVisible" title="Region">
        <el-form :model="regionForm">
          <el-form-item label="Name" :label-width="formLabelWidth">
            <el-input v-model="regionForm.name"></el-input>
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="isDialogVisible = false">Cancel</el-button>
          <el-button type="primary" @click="saveRegion">Save</el-button>
        </div>
      </el-dialog>
    </div>
  </template>
  
  <script>
  import api from '../api/regions';
  
  export default {
    data() {
      return {
        regions: [],
        searchQuery: '',
        isDialogVisible: false,
        isEditMode: false,
        regionForm: {
          id: '',
          name: '',
        },
        formLabelWidth: '120px',
      };
    },
    methods: {
      async fetchRegions() {
        try {
          const response = await api.getRegions(this.searchQuery);
          this.regions = response.data.data;
        } catch (error) {
          console.error('Error fetching regions:', error);
        }
      },
      showCreateDialog() {
        this.isEditMode = false;
        this.regionForm = { id: '', name: '' };
        this.isDialogVisible = true;
      },
      showEditDialog(region) {
        this.isEditMode = true;
        this.regionForm = { ...region };
        this.isDialogVisible = true;
      },
      async saveRegion() {
        try {
          if (this.isEditMode) {
            await api.updateRegion(this.regionForm.id, { name: this.regionForm.name });
          } else {
            await api.createRegion({ name: this.regionForm.name });
          }
          this.isDialogVisible = false;
          this.fetchRegions();
        } catch (error) {
          console.error('Error saving region:', error);
        }
      },
      async deleteRegion(regionId) {
        try {
          await api.deleteRegion(regionId);
          this.fetchRegions();
        } catch (error) {
          console.error('Error deleting region:', error);
        }
      },
    },
    mounted() {
      this.fetchRegions();
    },
  };
  </script>
  
  <style scoped>
  .mb-4 {
    margin-bottom: 1rem;
  }
  .mt-4 {
    margin-top: 1rem;
  }
  </style>
  