<template>
  <div class="region-manager">
    <h1>Region Manager</h1>

    <!-- Search Section -->
    <div class="search-section">
      <input
        v-model="searchQuery"
        @input="searchRegions"
        type="text"
        placeholder="Search Regions..."
        class="form-control"
      />
    </div>

    <!-- Add New Region -->
    <div class="form-section">
      <input
        v-model="newRegion.name"
        type="text"
        placeholder="Enter Region Name"
        class="form-control"
      />
      <button @click="createRegion" class="btn btn-primary">Create Region</button>
    </div>

    <!-- List of Regions -->
    <div class="region-list">
      <table class="table">
        <thead>
          <tr>
            <th>Region Name</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="region in regions" :key="region.id">
            <td>{{ region.name }}</td>
            <td>
              <button @click="editRegion(region)" class="btn btn-warning">Edit</button>
              <button @click="deleteRegion(region.id)" class="btn btn-danger">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Edit Modal -->
    <div v-if="showEditModal" class="modal">
      <div class="modal-content">
        <h3>Edit Region</h3>
        <input v-model="currentRegion.name" type="text" class="form-control" />
        <div class="button-group">
          <button @click="updateRegion" class="btn btn-success">Save Changes</button>
          <button @click="closeEditModal" class="btn btn-secondary">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {
  fetchRegions,
  createRegion,
  updateRegion,
  deleteRegion,
} from "@/api/region";

export default {
  data() {
    return {
      regions: [], // List of regions
      searchQuery: "", // For search functionality
      newRegion: { name: "" }, // Input field for creating a region
      showEditModal: false, // Controls visibility of Edit Modal
      currentRegion: { id: null, name: "" }, // Currently selected region for editing
    };
  },
  created() {
    this.loadRegions();
  },
  methods: {
    // Fetch all regions
    async loadRegions() {
      try {
        console.log("Loading regions...");
        const response = await fetchRegions();
        console.log("Regions loaded successfully:", response);
        this.regions = response.data; // Update based on API structure
      } catch (error) {
        console.error("Error loading regions:", error);
        alert("Failed to load regions. Please check the console for details.");
      }
    },

    // Search regions dynamically
    async searchRegions() {
      try {
        if (this.searchQuery.trim() === "") {
          this.loadRegions();
        } else {
          console.log("Searching regions for query:", this.searchQuery);
          const response = await fetchRegions();
          this.regions = response.data.filter((region) =>
            region.name.toLowerCase().includes(this.searchQuery.toLowerCase())
          );
        }
      } catch (error) {
        console.error("Error searching regions:", error);
      }
    },

    // Create a new region
    async createRegion() {
      try {
        if (!this.newRegion.name.trim()) {
          alert("Please provide a valid name.");
          return;
        }

        console.log("Creating region with data:", this.newRegion);
        await createRegion({ name: this.newRegion.name });
        this.newRegion.name = "";
        this.loadRegions();
      } catch (error) {
        console.error("Error creating region:", error);
        alert("Failed to create region. Please check the console for details.");
      }
    },

    // Delete a region
    async deleteRegion(id) {
      try {
        if (confirm("Are you sure you want to delete this region?")) {
          console.log("Deleting region with ID:", id);
          await deleteRegion(id);
          this.loadRegions();
        }
      } catch (error) {
        console.error("Error deleting region:", error);
        alert("Failed to delete region. Please check the console for details.");
      }
    },

    // Open Edit Modal
    editRegion(region) {
      this.showEditModal = true;
      this.currentRegion = { ...region };
    },

    // Close Edit Modal
    closeEditModal() {
      this.showEditModal = false;
      this.currentRegion = { id: null, name: "" };
    },

    // Update region in database
    async updateRegion() {
      try {
        if (!this.currentRegion.name.trim()) {
          alert("Region name cannot be empty.");
          return;
        }

        console.log("Updating region:", this.currentRegion);
        await updateRegion(this.currentRegion.id, { name: this.currentRegion.name });
        this.closeEditModal();
        this.loadRegions();
      } catch (error) {
        console.error("Error updating region:", error);
        alert("Failed to update region. Please check the console for details.");
      }
    },
  },
};
</script>

<style scoped>
/* General Styling */
.region-manager {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
  background-color: #f9f9f9;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
  text-align: center;
  color: #333;
  margin-bottom: 20px;
  font-size: 2em;
}

.search-section {
  margin-bottom: 20px;
}

.form-control {
  width: 100%;
  padding: 10px;
  margin-top: 5px;
  border: 1px solid #ddd;
  border-radius: 4px;
  box-sizing: border-box;
}

button {
  margin: 10px 0;
  padding: 10px 20px;
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
  color: #fff;
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

.region-list {
  margin-top: 20px;
}

.table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 20px;
}

.table th,
.table td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

.table th {
  background-color: #f2f2f2;
}

/* Modal Styling */
.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.6);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-content {
  background: #fff;
  padding: 20px;
  border-radius: 8px;
  width: 400px;
}

.modal-title {
  margin-bottom: 15px;
  font-size: 1.5em;
}

.button-group {
  display: flex;
  justify-content: space-between;
  margin-top: 20px;
}
</style>
