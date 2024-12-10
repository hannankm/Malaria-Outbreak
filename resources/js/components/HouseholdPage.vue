<template>
    <div>
      <el-input v-model="searchQuery" placeholder="Search Regions" class="mb-4"></el-input>
      <el-button type="primary" @click="showModal = true">Create Region</el-button>
      <el-table :data="filteredRegions" stripe>
        <el-table-column prop="name" label="Region Name"></el-table-column>
        <el-table-column label="Actions">
          <template #default="scope">
            <el-button size="small" @click="editRegion(scope.row)">Edit</el-button>
            <el-button size="small" type="danger" @click="deleteRegion(scope.row.id)">Delete</el-button>
            <el-button size="small" type="info" @click="goToZones(scope.row.id)">View Zones</el-button>
          </template>
        </el-table-column>
      </el-table>
      <modal-form 
        :is-visible="showModal" 
        :formData="currentRegion" 
        @close="showModal = false" 
        @save="saveRegion"
      />
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import ModalForm from './ModalForm.vue';
  
  export default {
    components: { ModalForm },
    data() {
      return {
        regions: [],
        searchQuery: '',
        showModal: false,
        currentRegion: { name: '' },
      };
    },
    computed: {
      filteredRegions() {
        return this.regions.filter(region => region.name.includes(this.searchQuery));
      },
    },
    methods: {
      async fetchRegions() {
        const response = await axios.get('/api/regions');
        this.regions = response.data;
      },
      saveRegion(region) {
        if (region.id) {
          // Update
          axios.put(`/api/regions/${region.id}`, region).then(this.fetchRegions);
        } else {
          // Create
          axios.post('/api/regions', region).then(this.fetchRegions);
        }
        this.showModal = false;
      },
      deleteRegion(id) {
        axios.delete(`/api/regions/${id}`).then(this.fetchRegions);
      },
      editRegion(region) {
        this.currentRegion = { ...region };
        this.showModal = true;
      },
      goToZones(regionId) {
        this.$router.push(`/region/${regionId}/zones`);
      },
    },
    mounted() {
      this.fetchRegions();
    },
  };
  </script>
  