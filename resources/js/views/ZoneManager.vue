<template>
  <div class="zone-manager">
    <h1 class="header">Zone Manager</h1>

    <!-- Dropdown to select region -->
    <div class="form-group">
      <label for="region">Select Region:</label>
      <select v-model="selectedRegion" @change="loadZones" class="form-control">
        <option disabled value="">Select a Region</option>
        <option v-for="region in regions" :key="region.id" :value="region.id">
          {{ region.name }}
        </option>
      </select>
    </div>

    <!-- Search Input -->
    <div class="form-group">
      <label for="search">Search Zones:</label>
      <input v-model="searchQuery" @input="searchZones" class="form-control" placeholder="Search zones..." />
    </div>

    <!-- Add New Zone -->
    <div class="form-group">
      <input v-model="newZone.name" class="form-control" type="text" placeholder="Enter Zone Name" />
      <button @click="createZone" class="btn btn-primary">Create Zone</button>
    </div>

    <!-- Zone List -->
    <div v-if="zones.length > 0" class="zone-list">
      <h2 class="subheader">Zones</h2>
      <ul class="list-group">
        <li v-for="zone in zones" :key="zone.id" class="list-group-item">
          <span>{{ zone.name }}</span>
          <div class="button-group">
            <button @click="editZone(zone)" class="btn btn-warning">Edit</button>
            <button @click="deleteZone(zone.id)" class="btn btn-danger">Delete</button>
          </div>
        </li>
      </ul>
    </div>

    <div v-else class="empty-message">
      <p>No zones available. Please select a region or add one.</p>
    </div>

    <!-- Zone Create/Edit Modal -->
    <div v-if="showForm" class="modal">
      <div class="modal-content">
        <h3 class="modal-title">{{ isEditMode ? 'Edit Zone' : 'Create Zone' }}</h3>
        <input type="text" v-model="currentZone.name" class="form-control" placeholder="Enter Zone Name" />
        <div class="button-group">
          <button @click="saveZone" class="btn btn-success">Save Changes</button>
          <button @click="cancelForm" class="btn btn-secondary">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { getRegionsDropdown, getZonesByRegion, createZone, editZoneApi, deleteZoneApi } from '@/api/zone';

export default {
  data() {
    return {
      regions: [], // List of regions for the dropdown
      selectedRegion: null, // Currently selected region
      zones: [], // List of zones for the currently selected region
      searchQuery: '', // Search query input
      newZone: { name: '' }, // Data for new zone creation
      showForm: false, // Controls visibility of create/edit form
      isEditMode: false, // Tracks if the form is in Edit Mode or Create Mode
      currentZone: { id: null, name: '' }, // Tracks current zone data for forms
    };
  },

  async created() {
    await this.fetchRegions();
  },

  methods: {
    async fetchRegions() {
      try {
        const response = await getRegionsDropdown();
        console.log('API Response for regions:', response);
        this.regions = response.data.map(region => ({
          id: region.id,
          name: region.name,
        }));
      } catch (error) {
        console.error('Failed to fetch regions:', error);
      }
    },

    async loadZones() {
      if (!this.selectedRegion) return;

      try {
        const response = await getZonesByRegion(this.selectedRegion);
        console.log('Loaded zones:', response);
        this.zones = response.data || [];
      } catch (error) {
        console.error('Failed to load zones:', error);
      }
    },

    async searchZones() {
      try {
        this.zones = this.zones.filter(zone =>
          zone.name.toLowerCase().includes(this.searchQuery.toLowerCase())
        );
        console.log('Search results:', this.zones);
      } catch (error) {
        console.error('Failed to search zones:', error);
      }
    },

    async createZone() {
      if (!this.newZone.name.trim()) {
        console.warn('Zone name is required!');
        return;
      }
      try {
        await createZone(this.selectedRegion, this.newZone);
        this.newZone.name = '';
        this.loadZones();
      } catch (error) {
        console.error('Failed to create zone:', error);
      }
    },

    async saveZone() {
      try {
        if (this.isEditMode) {
          await editZoneApi(this.selectedRegion, this.currentZone);
        } else {
          await createZone(this.selectedRegion, this.currentZone);
        }
        this.loadZones();
        this.cancelForm();
      } catch (error) {
        console.error('Failed to save zone:', error);
      }
    },

    editZone(zone) {
      this.currentZone = { ...zone };
      this.showForm = true;
      this.isEditMode = true;
    },

    async deleteZone(zoneId) {
      try {
        await deleteZoneApi(this.selectedRegion, zoneId);
        this.loadZones();
      } catch (error) {
        console.error('Failed to delete zone:', error);
      }
    },

    cancelForm() {
      this.showForm = false;
      this.currentZone = { id: null, name: '' };
      this.isEditMode = false;
    },
  },
};
</script>

<style scoped>
.zone-manager {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
  background-color: #f9f9f9;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0,0, 0.1);
}

.header, .subheader {
  text-align: center;
  margin-bottom: 20px;
}

.form-group {
  margin-bottom: 20px;
}

.form-control {
  width: 100%;
  padding: 10px;
  margin-top: 5px;
  font-size: 16px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.btn {
  padding: 10px 20px;
  margin-top: 10px;
  font-size: 16px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.btn-primary {
  background-color: #007bff;
  color: #fff;
}

.btn-warning {
  background-color: #ffc107;
  color: #000;
}

.btn-danger {
  background-color: #dc3545;
  color: #fff;
}

.btn-success {
  background-color: #28a745;
  color: #fff;
}

.btn-secondary {
  background-color: #6c757d;
  color: #fff;
}

.button-group {
  display: flex;
  gap: 10px;
  margin-top: 10px;
}

.zone-list ul {
  list-style: none;
  padding: 0;
}

.list-group-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px;
  background-color: #fff;
  border: 1px solid #ccc;
  border-radius: 4px;
  margin-bottom: 10px;
}

.empty-message {
  text-align: center;
  font-size: 16px;
  color: #666;
}

.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-content {
  background: #fff;
  padding: 20px;
  border-radius: 8px;
  width: 400px;
  text-align: center;
}

.modal-title {
  margin-bottom: 20px;
}
</style>
